<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class FixTicketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:ticket-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all tickets with price 0 to match their seat\'s price, or 50 if seat is missing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tickets = Ticket::where('price', 0)->get();
        $count = 0;
        foreach ($tickets as $ticket) {
            $ticket->price = $ticket->seat ? $ticket->seat->price : 50;
            $ticket->save();
            $count++;
        }
        $this->info("Updated $count tickets with price 0.");
    }
} 