@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .modern-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 2rem 1.5rem;
        margin: 2rem auto;
        max-width: 1200px;
    }
    .modern-table th, .modern-table td {
        vertical-align: middle !important;
    }
    .modern-table-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px #a259f722;
        vertical-align: middle;
    }
    .modern-table thead th {
        background: #22223b;
        color: #fff;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 2;
        border: none;
    }
    .modern-table tbody tr {
        transition: background 0.15s;
    }
    .modern-table tbody tr:nth-child(even) {
        background: #f1f5f9;
    }
    .modern-table tbody tr:hover {
        background: #e0e7ff;
    }
    .badge.resell-status-badge {
        border-radius: 12px;
        font-size: 1rem;
        padding: 0.4em 1em;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
    }
    @media (max-width: 900px) {
        .modern-card { padding: 1rem 0.5rem; }
        .modern-table { font-size: 0.95rem; }
        .modern-table-img { width: 32px; height: 32px; }
    }
    .table-responsive { overflow-x: auto; }
</style>
<div class="container py-5">
    <h2 class="mb-3">📤 My Resell Tickets</h2>
    <p class="mb-4">These are the tickets you've submitted for resell.</p>
    @if ($tickets->count() === 0)
        <div class="alert alert-info">You have not submitted any tickets for resell yet.</div>
    @else
    <div class="modern-card">
        <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Seat</th>
                        <th>Resell Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td class="fw-semibold">
                                {{ optional($ticket->event)->event_name }}</td>
                            <td>{{ optional($ticket->seat)->label ?? '-' }}</td>
                            <td>RM{{ number_format($ticket->resell_price, 2) }}</td>
                            <td>
                                <span class="badge resell-status-badge 
                                    @if($ticket->resell_status == 'pending') bg-warning text-dark
                                    @elseif($ticket->resell_status == 'approved') bg-success
                                    @elseif($ticket->resell_status == 'rejected') bg-danger
                                    @else bg-danger
                                    @endif">
                                    @if($ticket->resell_status == 'pending')
                                        <i class="fas fa-hourglass-half"></i>
                                        Pending
                                    @elseif($ticket->resell_status == 'approved')
                                        <i class="fas fa-check-circle"></i>
                                        Approved
                                    @elseif($ticket->resell_status == 'rejected')
                                        <i class="fas fa-times-circle"></i>
                                        Rejected
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                        {{ ucfirst($ticket->resell_status) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection