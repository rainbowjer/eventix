<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $event;
    public $seat;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
        $this->event = $ticket->event;
        $this->seat = $ticket->seat;
        $this->user = $ticket->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket Purchased',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tickets.purchased',
        );
    }

    public function build()
    {
        // Use the stored qr_payload for QR code
        $qrPayload = $this->ticket->qr_payload;
        $qr = new QrCode($qrPayload);
        $writer = new PngWriter();
        $qrResult = $writer->write($qr);
        $qrDataUri = $qrResult->getDataUri();

        // Generate PDF ticket
        $pdf = Pdf::loadView('emails.tickets.ticket_pdf', [
            'ticket' => $this->ticket,
            'event' => $this->event,
            'seat' => $this->seat,
            'user' => $this->user,
            'qrDataUri' => $qrDataUri,
        ]);
        $pdfContent = $pdf->output();

        return $this->markdown('emails.tickets.purchased')
            ->with([
                'ticket' => $this->ticket,
                'event' => $this->event,
                'seat' => $this->seat,
                'user' => $this->user,
                'qrDataUri' => $qrDataUri,
            ])
            ->attachData($pdfContent, 'ticket-'.$this->ticket->id.'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
