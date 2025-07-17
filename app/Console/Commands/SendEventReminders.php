<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $tickets = Ticket::with(['event', 'user', 'transaction'])
            ->whereHas('event')
            ->whereHas('user')
            ->get();

        foreach ($tickets as $ticket) {
            // Only tickets that are not resold and have a paid transaction
            if ($ticket->is_resell) continue;
            if (!$ticket->transaction || $ticket->transaction->payment_status !== 'paid') continue;
            $event = $ticket->event;
            $user = $ticket->user;
            if (!$event || !$user) continue;
            $eventDate = $event->event_date;
            $eventTime = $event->event_time;
            if (!$eventDate || !$eventTime) continue;
            $eventDateTime = Carbon::parse($eventDate . ' ' . $eventTime, 'Asia/Kuala_Lumpur');

            // 7 days reminder
            if (
                !$ticket->reminder_7days_sent_at &&
                $now->lessThan($eventDateTime) &&
                $now->greaterThanOrEqualTo($eventDateTime->copy()->subDays(7))
            ) {
                $user->notify(new EventReminderNotification($event, $ticket, '7days'));
                $ticket->reminder_7days_sent_at = $now;
                $ticket->save();
                $this->info("7-day reminder sent for ticket #{$ticket->id} (user: {$user->id})");
            }

            // 1 hour reminder
            if (
                !$ticket->reminder_1hour_sent_at &&
                $now->lessThan($eventDateTime) &&
                $now->greaterThanOrEqualTo($eventDateTime->copy()->subHour())
            ) {
                $user->notify(new EventReminderNotification($event, $ticket, '1hour'));
                $ticket->reminder_1hour_sent_at = $now;
                $ticket->save();
                $this->info("1-hour reminder sent for ticket #{$ticket->id} (user: {$user->id})");
            }

            // Event started reminder
            if (
                !$ticket->reminder_started_sent_at &&
                $now->greaterThanOrEqualTo($eventDateTime)
            ) {
                $user->notify(new EventReminderNotification($event, $ticket, 'started'));
                $ticket->reminder_started_sent_at = $now;
                $ticket->save();
                $this->info("Event started reminder sent for ticket #{$ticket->id} (user: {$user->id})");
            }
        }
    }
}
