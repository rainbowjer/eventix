<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class FixRejectedResellTickets extends Command
{
    protected $signature = 'fix:rejected-resell-tickets';
    protected $description = 'Set is_resell to true for all tickets with resell_status rejected and is_resell false.';

    public function handle()
    {
        $count = 0;
        $tickets = Ticket::where('resell_status', 'rejected')->where('is_resell', false)->get();
        foreach ($tickets as $ticket) {
            $ticket->is_resell = true;
            $ticket->save();
            $count++;
        }
        $this->info("Updated $count rejected tickets to appear in the resell list.");
    }
} 