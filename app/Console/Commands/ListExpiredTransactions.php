<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class ListExpiredTransactions extends Command
{
    protected $signature = 'list:expired-transactions';
    protected $description = 'List all transactions where the event_date is null or in the past.';

    public function handle()
    {
        $count = 0;
        foreach (Transaction::all() as $txn) {
            $event = optional(optional($txn->seat)->event);
            $eventDate = $event->event_date;
            $isExpired = is_null($eventDate) || Carbon::parse($eventDate)->isPast();
            if ($isExpired) {
                $this->line('Transaction ID: ' . $txn->id . ', Event: ' . ($event->event_name ?? 'N/A') . ', Event Date: ' . ($eventDate ?? 'NULL'));
                $count++;
            }
        }
        $this->info("Total expired transactions: $count");
    }
} 