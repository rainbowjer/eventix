<?php

namespace App\Http\Controllers;
use App\Notifications\TicketResellStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\AdminActivityLog;

class OrganizerResellController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();

        $tickets = Ticket::where('is_resell', true)
            ->where('resell_status', 'pending')
            ->whereHas('event', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })
            ->with(['event', 'seat', 'transaction.user'])
            ->get();

        return view('organizer.resell', compact('tickets'));
    }
  public function approve(Ticket $ticket)
{
    \Log::info('Approving ticket ID: ' . $ticket->id);
    $transaction = $ticket->transaction;
    if (!$transaction) {
        \Log::error('No transaction found for ticket ID: ' . $ticket->id);
    } else {
        \Log::info('Transaction found: ' . $transaction->id . ' for ticket ID: ' . $ticket->id);
        $user = $transaction->user;
        if (!$user) {
            \Log::error('No user found for transaction ID: ' . $transaction->id);
        } else {
            \Log::info('User found: ' . $user->id . ' for transaction ID: ' . $transaction->id);
        }
    }
    $ticket->resell_status = 'approved';
    $ticket->save();
    // Make the seat available again
    if ($ticket->seat) {
        $ticket->seat->is_booked = false;
        $ticket->seat->save();
    }
    $user = $ticket->transaction->user;
    $user->notify(new TicketResellStatusNotification($ticket, 'approved'));

    AdminActivityLog::create([
        'admin_id' => auth()->id(),
        'action' => 'approve_resell_ticket',
        'description' => "Approved resell for ticket ID: {$ticket->id}",
    ]);

    return redirect()->route('organizer.resell')->with('success', 'Ticket approved for resell.');
}

public function reject(Ticket $ticket)
{
    $ticket->resell_status = 'rejected';
    $ticket->resell_price = null;
    $ticket->save();

    $user = $ticket->transaction->user;
    $user->notify(new TicketResellStatusNotification($ticket, 'rejected'));

    AdminActivityLog::create([
        'admin_id' => auth()->id(),
        'action' => 'reject_resell_ticket',
        'description' => "Rejected resell for ticket ID: {$ticket->id}",
    ]);

    return redirect()->route('organizer.resell')->with('info', 'Ticket resell rejected.');
}
}