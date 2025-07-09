<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class FixTicketOriginalPrice extends Command
{
    protected $signature = 'fix:ticket-original-price';
    protected $description = 'Update all tickets with price 0 to use their transaction amount if available';

    public function handle()
    {
        $tickets = Ticket::where('price', 0)->get();
        $count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->transaction && $ticket->transaction->amount > 0) {
                $ticket->price = $ticket->transaction->amount;
                $ticket->save();
                $count++;
            }
        }
        $this->info("Updated $count tickets with price 0 to use their transaction amount.");
    }
} 