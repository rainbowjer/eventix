<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .qr { margin-top: 24px; }
        .details { margin-bottom: 16px; }
    </style>
</head>
<body>
    <h2>EventiX Ticket</h2>
    <div class="details">
        <strong>Event:</strong> {{ $event->event_name }}<br>
        <strong>Date:</strong> {{ $event->event_date }} {{ $event->event_time ?? '' }}<br>
        <strong>Location:</strong> {{ $event->location ?? '-' }}<br>
        <strong>Seat:</strong> {{ $seat->label }}<br>
        <strong>Price:</strong> RM{{ number_format($ticket->price, 2) }}<br>
        <strong>Name:</strong> {{ $user->name }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
    </div>
    <div class="qr">
        <strong>Scan this QR code at the event entrance:</strong><br>
        <img src="{{ $qrDataUri }}" alt="Ticket QR Code" style="max-width:180px;">
    </div>
</body>
</html> 