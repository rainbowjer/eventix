<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class UpdateResellSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resell:update-seats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set seats as available for all approved resell tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickets = Ticket::where('is_resell', true)
            ->where('resell_status', 'approved')
            ->with('seat')
            ->get();

        $count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->seat && $ticket->seat->is_booked) {
                $ticket->seat->is_booked = false;
                $ticket->seat->save();
                $count++;
            }
        }
        $this->info("Updated $count seats to available.");
    }
} 