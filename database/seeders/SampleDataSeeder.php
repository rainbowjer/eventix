<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Add events for existing organizers
        $organizers = User::where('role', 'organizer')->get();
        foreach ($organizers as $idx => $org) {
            Event::firstOrCreate([
                'event_name' => "Sample Event " . ($idx+1),
            ], [
                'event_date' => now()->addDays($idx+1),
                'organizer_id' => $org->id,
            ]);
        }

        // Add tickets for existing users and events
        $events = Event::all();
        $users = User::where('role', 'user')->get();
        foreach ($events as $event) {
            foreach ($users as $user) {
                Ticket::firstOrCreate([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ], [
                    'price' => 100,
                    'resell_price' => rand(80, 120),
                    'resell_status' => 'approved',
                    'is_resell' => true,
                ]);
            }
        }

        // Add transactions for existing tickets
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            Transaction::firstOrCreate([
                'ticket_id' => $ticket->id,
            ], [
                'user_id' => $ticket->user_id,
                'amount' => $ticket->price,
            ]);
        }
    }
} 