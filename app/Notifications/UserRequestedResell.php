<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRequestedResell extends Notification
{
    use Queueable;

    /**
     * The event related to the resell request.
     */
    public $event;

    /**
     * The ticket being resold.
     */
    public $ticket;

    /**
     * The user requesting the resell.
     */
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($event, $ticket, $user)
    {
        $this->event = $event;
        $this->ticket = $ticket;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Resell Request for Event: ' . $this->event->event_name)
            ->greeting('Hello Organizer!')
            ->line('A user has requested to resell a ticket for your event: ' . $this->event->event_name)
            ->line('User: ' . $this->user->name . ' (' . $this->user->email . ')')
            ->line('Ticket ID: ' . $this->ticket->id)
            ->line('Resell Price: RM' . number_format($this->ticket->resell_price, 2))
            ->action('View Resell Requests', url('/organizer/resell'))
            ->line('Thank you for using EventiX!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->event_name,
            'ticket_id' => $this->ticket->id,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'resell_price' => $this->ticket->resell_price,
            'url' => url('/organizer/resell'),
        ];
    }
}
