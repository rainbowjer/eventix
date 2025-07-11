<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

public function showScanner() {
    return view('organizer.scan');
}

public function validateTicket(Request $request) {
    $payload = $request->input('qr');
    try {
        $data = json_decode(Crypt::decryptString($payload), true);
        $transaction = Transaction::find($data['ticket_id'] ?? null);
        if (!$transaction) {
            return response()->json(['valid' => false, 'message' => 'Ticket not found.']);
        }
        if ($transaction->checked_in) {
            return response()->json(['valid' => false, 'message' => 'Ticket already used.']);
        }
        $transaction->checked_in = true;
        $transaction->save();
        return response()->json(['valid' => true]);
    } catch (\Exception $e) {
        return response()->json(['valid' => false, 'message' => 'Invalid QR code.']);
    }
}
}