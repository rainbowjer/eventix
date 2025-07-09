<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Transactions Report</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Event</th>
                <th>Seat</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->seat->event->event_name ?? '-' }}</td>
                    <td>{{ $transaction->seat->label ?? '-' }}</td>
                    <td>RM{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>