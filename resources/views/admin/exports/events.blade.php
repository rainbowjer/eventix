<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Events Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Events Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->event_name }}</td>
                <td>{{ $event->event_date }}</td>
                <td>{{ $event->venue }}</td>
                <td>{{ $event->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 