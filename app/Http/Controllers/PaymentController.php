<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
{
   
public function confirm(Request $request)
{
    // Booking info passed from seat form
    $bookingData = $request->all(); // you can validate or store it in session

    return view('payments.confirm', compact('bookingData'));
}

public function process(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'seat_ids' => 'required|array',
        'seat_ids.*' => 'exists:seats,id',
        'payment_method' => 'required|string',
        'total_price' => 'required|numeric',
    ]);

    $userId = auth()->id();
    $eventId = $request->event_id;
    $now = Carbon::now();

    foreach ($request->seat_ids as $seatId) {
        $seat = Seat::where('id', $seatId)
                    ->where('event_id', $eventId)
                    ->where('is_booked', false)
                    ->first();

        if ($seat) {
            // Mark seat as booked
            $seat->is_booked = true;
            $seat->save();

            // Generate a single QR payload and store it
            $qrPayloadArr = [
                'ticket_id' => null, // will set after ticket is created
                'user_id' => $userId,
                'event_id' => $eventId,
                'seat' => $seat->label,
            ];
            // Create the ticket first to get its ID
            $ticket = Ticket::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'seat_id' => $seat->id,
                'price' => $seat->price,
                'qr_payload' => '', // temp, will update below
            ]);
            $qrPayloadArr['ticket_id'] = $ticket->id;
            $qrPayload = Crypt::encryptString(json_encode($qrPayloadArr));
            $ticket->qr_payload = $qrPayload;
            $ticket->save();

            // Create transaction for this seat
            Transaction::create([
                'user_id' => $userId,
                'seat_id' => $seat->id,
                'ticket_id' => $ticket->id,
                'amount' => $seat->price,
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'purchase_date' => $now,
            ]);
        }
    }

    return redirect()->route('book.history')->with('success', 'Payment successful! Your tickets are booked.');
}
}
