<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Ticket;

class ResellTicketApproved extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your ticket for "' . $this->ticket->event->event_name . '" has been approved for resell.',
            'ticket_id' => $this->ticket->id,
            'url' => url(route('resell.show', $this->ticket->id)),
        ];
    }
}
