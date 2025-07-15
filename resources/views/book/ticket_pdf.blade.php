o<!DOCTYPE html>
<html>
<head>
    <title>Event Ticket</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f8fa; }
        .ticket-card {
            max-width: 500px;
            width: 100%;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(60,72,88,0.10);
            padding: 32px 28px 24px 28px;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 18px;
        }
        .logo {
            max-height: 48px;
            margin-bottom: 8px;
            max-width: 100%;
            height: auto;
        }
        .event-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #22223b;
            margin-bottom: 6px;
        }
        .event-detail {
            font-size: 1.05rem;
            color: #444;
            margin-bottom: 4px;
        }
        .ticket-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 18px;
        }
        .details {
            flex: 1 1 180px;
            min-width: 180px;
        }
        .qr {
            flex: 0 0 140px;
            text-align: center;
            width: 100%;
            max-width: 140px;
            margin: 0 auto;
        }
        .qr img {
            border: 4px solid #e0e7ff;
            border-radius: 12px;
            background: #f8fafc;
            width: 140px;
            height: 140px;
            max-width: 100%;
        }
        .footer {
            text-align: center;
            margin-top: 28px;
            font-size: 0.98rem;
            color: #888;
        }
        @media (max-width: 600px) {
            .ticket-card { padding: 1rem 0.5rem; }
            .event-title { font-size: 1.1rem; }
            .ticket-info { flex-direction: column; align-items: stretch; }
            .qr { margin-top: 1rem; }
        }
    </style>
</head>
<body>
    <div class="ticket-card">
        <div class="header">
            <img src="{{ public_path('eventix-logo.png') }}" class="logo" alt="EventiX Logo">
            <div class="event-title">{{ $transaction->seat->event->event_name ?? 'Event' }}</div>
        </div>
        <div class="ticket-info">
            <div class="details">
                <div class="event-detail"><strong>Date:</strong> {{ $transaction->seat->event->event_date ?? '-' }}</div>
                <div class="event-detail"><strong>Time:</strong> {{ $transaction->seat->event->event_time ?? '-' }}</div>
                <div class="event-detail"><strong>Venue:</strong> {{ $transaction->seat->event->location ?? '-' }}</div>
                <div class="event-detail"><strong>Seat:</strong> {{ $transaction->seat->label ?? '-' }}</div>
                <div class="event-detail"><strong>Price:</strong> RM{{ number_format($transaction->amount, 2) }}</div>
                <div class="event-detail"><strong>Ticket ID:</strong> {{ $transaction->ticket_id }}</div>
            </div>
            <div class="qr">
                <img src="{{ $qrDataUri }}" width="140" height="140" alt="QR Code">
            </div>
        </div>
        <div class="footer">
            Please present this ticket at the event entrance.<br>
            <strong>Thank you for choosing EventiX!</strong>
        </div>
    </div>
</body>
</html> 