<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizerTicketController extends Controller
{
   public function index()
{
    $events = \App\Models\Event::where('organizer_id', auth()->id())
        ->with(['tickets.user', 'tickets.transaction'])
        ->withCount('tickets')
        ->get();

    // Calculate analytics
    $allTickets = $events->flatMap->tickets;
    $totalRevenue = $allTickets->reduce(function($carry, $ticket) {
        // Use transaction amount if available, else ticket price
        $amount = $ticket->transaction && $ticket->transaction->amount ? $ticket->transaction->amount : $ticket->price;
        return $carry + ($amount ?? 0);
    }, 0);
    $averagePrice = $allTickets->count() ? $allTickets->avg(function($ticket) {
        return $ticket->transaction && $ticket->transaction->amount ? $ticket->transaction->amount : $ticket->price;
    }) : 0;
    // Top buyers (by ticket count)
    $topBuyers = $allTickets->groupBy('user_id')
        ->map(function($tickets, $userId) {
            return [
                'user' => $tickets->first()->user,
                'count' => $tickets->count()
            ];
        })
        ->sortByDesc('count')
        ->take(3)
        ->values();

    return view('organizer.tickets', compact('events', 'totalRevenue', 'averagePrice', 'topBuyers'));
}
}
