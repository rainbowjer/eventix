@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">📈 Reports</h2>
    <a href="{{ route('admin.report.export.pdf') }}" class="btn btn-danger">Export to PDF</a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.report') }}" class="row g-3">
            <div class="col-md-3">
                <label for="user_search" class="form-label">Search by User</label>
                <input type="text" class="form-control" id="user_search" name="user_search" 
                       value="{{ request('user_search') }}" placeholder="Enter user name...">
            </div>
            <div class="col-md-3">
                <label for="event_search" class="form-label">Search by Event</label>
                <input type="text" class="form-control" id="event_search" name="event_search" 
                       value="{{ request('event_search') }}" placeholder="Enter event name...">
            </div>
            <div class="col-md-2">
                <label for="seat_search" class="form-label">Search by Seat</label>
                <input type="text" class="form-control" id="seat_search" name="seat_search" 
                       value="{{ request('seat_search') }}" placeholder="Enter seat...">
            </div>
            <div class="col-md-2">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('admin.report') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
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
    <div class="card-header">
        <h5 class="mb-0">Transaction Results ({{ $transactions->count() }} found)</h5>
    </div>
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