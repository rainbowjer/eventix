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
}