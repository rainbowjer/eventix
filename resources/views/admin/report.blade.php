@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">📈 Reports</h2>
    <a href="{{ route('admin.report.export.pdf') }}" class="btn btn-danger">Export to PDF</a>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center bg-info text-white mb-3">
            <div class="card-body">
                <div class="fs-2 fw-bold">RM{{ number_format($totalSales, 2) }}</div>
                <div>Total Sales</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center bg-primary text-white mb-3">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $totalTickets }}</div>
                <div>Total Tickets Sold</div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Event</th>
                        <th>Seat</th>
                        <th>Amount (RM)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td>{{ $t->user?->name ?? '-' }}</td>
                        <td>{{ $t->seat?->event?->event_name ?? '-' }}</td>
                        <td>{{ $t->seat?->label ?? '-' }}</td>
                        <td>{{ number_format($t->amount, 2) }}</td>
                        <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection