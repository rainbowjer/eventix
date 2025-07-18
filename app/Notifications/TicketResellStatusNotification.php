<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketResellStatusNotification extends Notification
{
    public $ticket;
    public $status;

    public function __construct($ticket, $status)
    {
        $this->ticket = $ticket;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $eventName = $this->ticket->event->event_name;
        $price = number_format($this->ticket->resell_price, 2);
        $statusText = ucfirst($this->status);

        return (new MailMessage)
            ->greeting("Hello {$notifiable->name},")
            ->line("Your ticket resell request for **{$eventName}** has been **{$statusText}**.")
            ->line("Resell Price: RM{$price}")
            ->action('View My Tickets', url(route('resell.my')))
            ->line('Thank you for using EventiX!');
    }
    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'event_name' => $this->ticket->event->event_name,
            'status' => $this->status, // approved or rejected
            'message' => "Your ticket resell request for '{$this->ticket->event->event_name}' has been {$this->status}.",
            'url' => url(route('resell.show', $this->ticket->id)),
        ];
    }
}
