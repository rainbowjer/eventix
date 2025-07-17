<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResellController extends Controller
{
    public function show($transactionId)
    {
        $transaction = \App\Models\Transaction::with(['seat.event', 'ticket'])->findOrFail($transactionId);

        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        if (now()->gt($transaction->seat->event->event_date)) {
            return redirect()->route('book.history')->with('error', 'Event has expired. You cannot resell this ticket.');
        }

        return view('resell.show', compact('transaction'));
    }

    public function post(Request $request, $transactionId)
    {
        $request->validate([
            'resell_price' => 'required|numeric|min:1|max:9999',
        ]);

        $transaction = \App\Models\Transaction::with(['seat.event', 'ticket'])->findOrFail($transactionId);

        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket = $transaction->ticket;
        // Prevent duplicate resell
        if ($ticket->is_resell && in_array($ticket->resell_status, ['pending', 'approved'])) {
            return redirect()->route('resell.my')->with('error', 'This ticket is already posted for resell.');
        }
        $ticket->is_resell = true;
        $ticket->resell_status = 'pending';
        $ticket->resell_price = $request->resell_price;
        $ticket->save();

        // Notify the event organizer
        $event = $transaction->seat->event;
        $organizer = $event->organizer;
        if ($organizer) {
            $organizer->notify(new \App\Notifications\UserRequestedResell($event, $ticket, auth()->user()));
        }

        return redirect()->route('resell.my')->with('success', 'Ticket posted for resell successfully!');
    }

    public function myResellTickets()
    {
        $userId = auth()->id();

        // Log user resell activity
        $resellTicketCount = \App\Models\Ticket::where('user_id', $userId)
            ->where('is_resell', true)
            ->count();

        \Log::info('User ' . $userId . ' has ' . $resellTicketCount . ' resell tickets.');

        // Eager load only required fields and limit memory usage
        $tickets = \App\Models\Ticket::with([
                'seat:id,label',
                'event:id,event_name',
                'transaction:id,seat_id,amount'
            ])
            ->select('id', 'user_id', 'seat_id', 'event_id', 'resell_price', 'resell_status', 'is_resell')
            ->where('user_id', $userId)
            ->where('is_resell', true)
            ->latest()
            ->paginate(10); // You can increase this as needed

        return view('resell.my', compact('tickets'));
    }

    /**
     * Handle purchase of a resell ticket by a new user
     */
    public function buy($ticketId)
    {
        $ticket = \App\Models\Ticket::where('id', $ticketId)
            ->where('is_resell', true)
            ->where('resell_status', 'approved')
            ->whereNull('user_id') // Not currently owned
            ->firstOrFail();

        $user = auth()->user();
        // Assign ticket to new user
        $ticket->user_id = $user->id;
        $ticket->is_resell = false;
        $ticket->resell_status = null;
        $ticket->resell_price = null;
        $ticket->save();

        // Mark seat as booked
        if ($ticket->seat) {
            $ticket->seat->is_booked = true;
            $ticket->seat->save();
        }

        // Create transaction
        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'seat_id' => $ticket->seat_id,
            'ticket_id' => $ticket->id,
            'amount' => $ticket->price,
            'payment_status' => 'paid',
            'purchase_date' => now(),
        ]);

        // Send QR code email
        \Mail::to($user->email)->send(new \App\Mail\TicketPurchased($ticket));

        return redirect()->route('book.history')->with('success', 'Resell ticket purchased! Check your email for the QR code.');
    }
}