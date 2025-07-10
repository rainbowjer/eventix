<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Transaction;
use Illuminate\Support\Facades\Crypt;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\PdfWriter;





class BookingController extends Controller
{
    public function create(Event $event)
    {
        $event->load('seats');
        return view('book.create', compact('event'));
    }

    
public function store(Request $request, $event)
{
    if (!$event instanceof Event) {
        $event = Event::findOrFail($event);
    }
    $request->validate([
        'seat_ids' => 'required|array',
        'seat_ids.*' => 'exists:seats,id',
    ]);

    $bookedSeats = [];
    $totalAmount = 0;

    foreach ($request->seat_ids as $seatId) {
        $seat = Seat::where('id', $seatId)
            ->where('event_id', $event->id)
            ->where('is_booked', false)
            ->firstOrFail();

        $seat->is_booked = true;
        $seat->save();

        $ticket = \App\Models\Ticket::create([
            'seat_id' => $seat->id,
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'price' => $seat->price ?? 0,
            'type' => $seat->type ?? 'General',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'seat_id' => $seat->id,
            'ticket_id' => $ticket->id,
            'amount' => $seat->price ?? 0,
            'payment_status' => 'paid',
            'payment_method' => 'credit_card', // Default for direct booking
        ]);

        $bookedSeats[] = $seat;
        $totalAmount += $seat->price ?? 0;
    }

    $payload = 'TICKET-' . uniqid();
    $qr = new QrCode($payload);
    $writer = new PdfWriter();
    $result = $writer->write($qr);
    $pdfQrCode = $result->getString();

    return response($pdfQrCode)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="ticket-qr.pdf"');
}
public function history(Request $request)
{
    $userId = auth()->id();
    $transactionCount = Transaction::where('user_id', $userId)->count();
    \Log::info('User ' . $userId . ' has ' . $transactionCount . ' transactions.');

    $query = auth()->user()
        ->transactions()
        ->with(['seat.event', 'ticket', 'ticket.transaction'])
        ->latest();

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->whereHas('seat.event', function ($q) use ($search) {
            $q->where('event_name', 'like', "%$search%");
        });
    }

    $transactions = $query
    ->with([
        'seat:id,event_id,label',
        'seat.event:id,event_name',
        'ticket:id,is_resell,resell_status',
    ])
    ->select('id', 'user_id', 'seat_id', 'ticket_id', 'amount', 'created_at') // only needed columns
    ->paginate(20);
    $activeTransactions = $transactions->filter(function($txn) {
        // Only exclude tickets that are currently approved for resell
        return !($txn->ticket && $txn->ticket->is_resell && $txn->ticket->resell_status === 'approved');
    })->values();
    $resoldTransactions = $transactions->filter(function($txn) {
        return ($txn->ticket && $txn->ticket->is_resell && $txn->ticket->resell_status === 'approved');
    })->values();
    $totalSpent = $transactions->sum('amount');

    // 🔐 Add encrypted QR payload to each transaction
    foreach ($transactions as $txn) {
            $payload = [
            'ticket_id' => $txn->ticket_id,
            'user_id' => $txn->user_id,
            'event_id' => $txn->seat->event->id,
            'seat' => $txn->seat->label,
            'timestamp' => now()->timestamp,
        ];

        $txn->qr_payload = Crypt::encryptString(json_encode($payload));
    }

    return view('book.history', [
        'transactions' => $transactions,
        'activeTransactions' => $activeTransactions,
        'resoldTransactions' => $resoldTransactions,
        'totalSpent' => $totalSpent,
    ]);
}

public function resell(Transaction $transaction)
{
    // Check ownership
    if ($transaction->user_id !== auth()->id()) {
        abort(403, 'Unauthorized');
    }

    // Check if event has passed
    if (now()->gt($transaction->seat->event->event_date)) {
        return back()->with('error', 'Cannot resell ticket after event date.');
    }

    // Update seat to be available
    $seat = $transaction->seat;
    $seat->is_booked = false;
    $seat->save();

    // Mark ticket as resold
    $ticket = $transaction->ticket;
    $ticket->is_resell = true;
    $ticket->user_id = null; // or keep for record
    $ticket->save();

    // Delete or archive transaction
    $transaction->delete(); // or soft delete if preferred

    return back()->with('success', 'Ticket successfully resold and now available for others.');
}
public function preparePayment(Request $request, $event)
{
    if (!$event instanceof Event) {
        $event = Event::findOrFail($event);
    }
    $request->validate([
        'seat_ids' => 'required|array',
        'seat_ids.*' => 'exists:seats,id',
    ]);

    $seats = Seat::whereIn('id', $request->seat_ids)
        ->where('event_id', $event->id)
        ->where('is_booked', false)
        ->get();

    if ($seats->count() !== count($request->seat_ids)) {
        return back()->with('error', 'Some selected seats are already booked.');
    }

    $totalPrice = $seats->sum('price');

    // Store payment data in session
    session()->put('payment_data', [
        'event_id' => $event->id,
        'seat_ids' => $seats->pluck('id')->toArray(),
        'total_price' => $totalPrice,
    ]);

    // Redirect to GET payment confirmation page
    return redirect()->route('payments.confirm');
}

public function showPaymentConfirmation()
{
    $data = session('payment_data');

    if (!$data) {
        return redirect()->route('book.history')->with('error', 'Session expired.');
    }

    $event = Event::findOrFail($data['event_id']);
    $seats = Seat::whereIn('id', $data['seat_ids'])->get();
    $total = $data['total_price'];

    return view('payments.confirm', compact('event', 'seats', 'total'));
}

public function processPayment(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'seat_ids' => 'required|array',
        'seat_ids.*' => 'exists:seats,id',
        'total_price' => 'required|numeric',
        'payment_method' => 'required|string',
    ]);

    $event = Event::findOrFail($request->event_id);
    $bookedSeats = [];
    $totalAmount = 0;

    foreach ($request->seat_ids as $seatId) {
        $seat = Seat::where('id', $seatId)
            ->where('event_id', $event->id)
            ->where('is_booked', false)
            ->first();

        if (!$seat) {
            return back()->with('error', 'One or more seats are already booked.');
        }

        $seat->is_booked = true;
        $seat->save();

        $ticket = \App\Models\Ticket::create([
            'seat_id' => $seat->id,
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'price' => $seat->price,
            'type' => $seat->type,
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'seat_id' => $seat->id,
            'ticket_id' => $ticket->id,
            'amount' => $seat->price,
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'purchase_date' => now(),
        ]);

        $bookedSeats[] = $seat;
        $totalAmount += $seat->price;
    }

    // Save booking data to session for confirmation page
    session()->put('booking_data', [
        'event_id' => $event->id,
        'seats' => collect($bookedSeats)->pluck('id')->toArray(),
        'total' => $totalAmount,
    ]);

    // Redirect to GET booking confirmation page
    return redirect()->route('book.confirmation');
}

public function confirmation()
{
    $bookingData = session('booking_data');

    if (!$bookingData) {
        return redirect()->route('book.history')->with('info', 'Booking already confirmed.');
    }

    $event = Event::findOrFail($bookingData['event_id']);
    $seats = Seat::whereIn('id', $bookingData['seats'])->get();
    $total = $bookingData['total'];

    // Optionally clear booking data from session after showing
    // session()->forget('booking_data');

    return view('book.confirmation', compact('event', 'seats', 'total'));
}
public function downloadQr($transactionId)
{
    $transaction = Transaction::findOrFail($transactionId);

    $payload = $transaction->qr_payload ?? 'TICKET-' . $transaction->ticket_id;

    $qr = new QrCode($payload);
    $writer = new PdfWriter();
    $result = $writer->write($qr);

    return response($result->getString())
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename=\"ticket-qr-' . $transaction->id . '.pdf\"');
}
}