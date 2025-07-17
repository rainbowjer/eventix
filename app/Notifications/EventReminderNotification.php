<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $ticket;
    protected $reminderType;

    /**
     * Create a new notification instance.
     */
    public function __construct($event, $ticket, $reminderType)
    {
        $this->event = $event;
        $this->ticket = $ticket;
        $this->reminderType = $reminderType;
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
        $eventName = $this->event->event_name ?? 'your event';
        $eventDate = $this->event->event_date ?? '';
        $eventTime = $this->event->event_time ?? '';
        $url = url('/events/' . ($this->event->id ?? ''));
        $message = '';
        if ($this->reminderType === '7days') {
            $message = "Reminder: '$eventName' is happening in less than 7 days!";
        } elseif ($this->reminderType === '1hour') {
            $message = "Reminder: '$eventName' is starting in 1 hour!";
        } elseif ($this->reminderType === 'started') {
            $message = "'$eventName' has just started! Enjoy the event.";
        }
        return (new MailMessage)
            ->subject('Event Reminder: ' . $eventName)
            ->greeting('Hello!')
            ->line($message)
            ->line("Date: $eventDate")
            ->line("Time: $eventTime")
            ->action('View Event', $url)
            ->line('Thank you for using EventiX!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $eventName = $this->event->event_name ?? 'your event';
        $eventDate = $this->event->event_date ?? '';
        $eventTime = $this->event->event_time ?? '';
        $url = url('/events/' . ($this->event->id ?? ''));
        $message = '';
        if ($this->reminderType === '7days') {
            $message = "Reminder: '$eventName' is happening in less than 7 days!";
        } elseif ($this->reminderType === '1hour') {
            $message = "Reminder: '$eventName' is starting in 1 hour!";
        } elseif ($this->reminderType === 'started') {
            $message = "'$eventName' has just started! Enjoy the event.";
        }
        return [
            'event_id' => $this->event->id ?? null,
            'ticket_id' => $this->ticket->id ?? null,
            'reminder_type' => $this->reminderType,
            'message' => $message,
            'event_name' => $eventName,
            'event_date' => $eventDate,
            'event_time' => $eventTime,
            'url' => $url,
        ];
    }
}
