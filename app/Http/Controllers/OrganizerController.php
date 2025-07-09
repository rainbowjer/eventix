<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;
class OrganizerController extends Controller
{
    public function index()
    {
        $organizer = Auth::user();
        $events = Event::where('organizer_id', $organizer->id)->get();

        return view('organizer.dashboard', compact('organizer', 'events'));
    }

    public function resellRequests()
{
    $tickets = Ticket::where('is_resell', true)
        ->where('resell_status', 'pending')
        ->with(['seat.event', 'transaction.user'])
        ->get();

    return view('resell.initiate', compact('tickets'));
}
}