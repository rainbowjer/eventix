<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class FixResellTicketOriginalPrice extends Command
{
    protected $signature = 'fix:resell-ticket-original-price';
    protected $description = 'Update all resell tickets with original price 0 to the correct value based on seat type.';

    public function handle()
    {
        $count = 0;
        $tickets = Ticket::where('is_resell', true)->where(function($q){ $q->where('price', 0)->orWhereNull('price'); })->get();
        foreach ($tickets as $ticket) {
            $type = strtoupper(optional($ticket->seat)->type);
            if ($type === 'VIP') {
                $ticket->price = 150;
            } elseif ($type === 'GENERAL') {
                $ticket->price = 80;
            } elseif ($type === 'ECONOMY') {
                $ticket->price = 50;
            } else {
                continue; // skip if type is unknown
            }
            $ticket->save();
            $count++;
        }
        $this->info("Updated $count resell tickets with original price 0.");
    }
} 