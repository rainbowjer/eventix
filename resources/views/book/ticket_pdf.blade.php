<!DOCTYPE html>
<html>
<head>
    <title>Event Ticket</title>
    <style>
        body { font-family: sans-serif; }
        .ticket-container { max-width: 400px; margin: 0 auto; border: 1px solid #ddd; border-radius: 12px; padding: 24px; }
        .qr { text-align: center; margin-top: 20px; }
        .event-title { font-size: 1.5rem; font-weight: bold; margin-bottom: 10px; text-align: center; }
        .event-detail { margin-bottom: 6px; }
        .footer { text-align: center; margin-top: 24px; font-size: 0.95rem; color: #555; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="event-title">{{ $transaction->seat->event->event_name ?? 'Event' }}</div>
        <div class="event-detail">Date: {{ $transaction->seat->event->event_date ?? '-' }}</div>
        <div class="event-detail">Time: {{ $transaction->seat->event->event_time ?? '-' }}</div>
        <div class="event-detail">Seat: {{ $transaction->seat->label ?? '-' }}</div>
        <div class="event-detail">Price: RM{{ number_format($transaction->amount, 2) }}</div>
        <div class="event-detail">Venue: {{ $transaction->seat->event->location ?? '-' }}</div>
        <div class="qr">
            <img src="{{ $qrDataUri }}" width="180" height="180" alt="QR Code">
        </div>
        <div class="footer">
            Scan this QR at the event entrance<br>
            Ticket ID: {{ $transaction->ticket_id }}
        </div>
    </div>
</body>
</html> 