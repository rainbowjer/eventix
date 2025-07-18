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
    @media (max-width: 900px) {
        .modern-card { padding: 1rem 0.5rem; }
        .modern-table { font-size: 0.95rem; }
    }
    .table-responsive { overflow-x: auto; }
    .btn-icon {
        padding: 0.15rem 0.35rem !important;
        font-size: 1rem !important;
        line-height: 1 !important;
        border-radius: 4px !important; /* square, not round */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    @media (max-width: 768px) {
        .modern-card { padding: 0.5rem 0.2rem; }
        .btn-gradient, .btn, .btn-icon { width: 100%; margin-bottom: 0.5em; justify-content: center; }
        .searchbar-row { flex-direction: column !important; gap: 0.5rem; }
        .searchbar-row .input-group { width: 100%; }
        .searchbar-row .search-btn { width: 100%; margin-left: 0; }
    }
    .search-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.9rem;
        font-size: 1.2rem;
        border-radius: 12px;
        margin-left: 0.5rem;
        background: linear-gradient(90deg, #a259f7 0%, #6366f1 100%);
        color: #fff;
        border: none;
        transition: background 0.18s, color 0.18s;
    }
    .search-btn:active, .search-btn:focus {
        background: linear-gradient(90deg, #6366f1 0%, #a259f7 100%);
        color: #fff;
    }
    .input-group .form-control {
        border-radius: 0 12px 12px 0 !important;
    }
    .input-group .input-group-text {
        border-radius: 12px 0 0 12px !important;
    }
</style>
<div class="container py-5">
    <div class="mb-4 text-center">
        <h2><i class="bi bi-journal-text"></i> My Booking History</h2>
    </div>
    <form method="GET" action="{{ route('book.history') }}" class="mb-4 d-flex searchbar-row align-items-center justify-content-center" style="gap:0.5rem;">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your bookings..." class="form-control border-start-0" style="border-radius: 0 12px 12px 0;">
        </div>
        <button type="submit" class="search-btn" title="Search"><i class="bi bi-search"></i></button>
    </form>
    <div class="modern-card">
        <h4 class="mb-3"><i class="bi bi-ticket-perforated"></i> Active Tickets</h4>
        <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Seat</th>
                        <th>Price</th>
                        <th>Date Booked</th>
                        <th>Status</th>
                        <th>QR Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeTransactions as $i => $txn)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ optional(optional($txn->seat)->event)->event_name }}</td>
                        <td>{{ optional($txn->seat)->label }}</td>
                        <td>RM{{ number_format($txn->amount, 2) }}</td>
                        <td>{{ $txn->created_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</td>
                        @php
                            $eventDate = optional(optional($txn->seat)->event)->event_date;
                            $eventTime = optional(optional($txn->seat)->event)->event_time;
                            if ($eventDate && $eventTime) {
                                $eventDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $eventTime, 'Asia/Kuala_Lumpur');
                            } elseif ($eventDate) {
                                $eventDateTime = \Carbon\Carbon::parse($eventDate . ' 23:59:59', 'Asia/Kuala_Lumpur');
                            } else {
                                $eventDateTime = null;
                            }
                            $isExpired = $eventDateTime ? $eventDateTime->isPast() : false;
                            $ticket = $txn->ticket;
                            $isResellApproved = $ticket && $ticket->is_resell && $ticket->resell_status === 'approved';
                        @endphp
                        <td>
                            @if($isExpired)
                                Not Active
                            @else
                                Active
                            @endif
                        </td>
                             <td class="text-center">
                            @php
                                $eventDate = optional(optional($txn->seat)->event)->event_date;
                                $eventTime = optional(optional($txn->seat)->event)->event_time;
                                if ($eventDate && $eventTime) {
                                    $eventDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $eventTime);
                                } elseif ($eventDate) {
                                    $eventDateTime = \Carbon\Carbon::parse($eventDate . ' 23:59:59');
                                } else {
                                    $eventDateTime = null;
                                }
                                $isExpired = $eventDateTime ? $eventDateTime->isPast() : false;
                                $ticket = $txn->ticket;
                                $isResellApproved = $ticket && $ticket->is_resell && $ticket->resell_status === 'approved';
                            @endphp
                            @if($isExpired)
                                <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1" disabled title="Unavailable">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            @else
                                <a href="{{ route('view.ticket', $txn->id) }}" class="btn btn-success btn-sm d-flex align-items-center gap-1" title="View Ticket" target="_blank">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            @endif
                        </td>
                        <td>
                            @if($isExpired || $isResellApproved)
                                <button class="btn btn-secondary btn-sm" disabled>Unavailable</button>
                            @else
                                <a href="{{ route('resell.show', $txn->id) }}" class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-arrow-repeat"></i> Resell
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{-- Resold Tickets Table --}}
@if($resoldTransactions->count())
<div class="modern-card">
    <h4 class="mb-3"><i class="bi bi-arrow-repeat"></i> Resold Tickets</h4>
    <div class="table-responsive">
        <table class="table modern-table align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Seat</th>
                    <th>Price</th>
                    <th>Date Booked</th>
                    <th>Status</th>
                    <th>QR Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resoldTransactions as $i => $txn)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ optional(optional($txn->seat)->event)->event_name }}</td>
                    <td>{{ optional($txn->seat)->label }}</td>
                    <td>RM{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->created_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</td>
                    <td>
                        @php
                            $eventDate = optional(optional($txn->seat)->event)->event_date;
                            $eventTime = optional(optional($txn->seat)->event)->event_time;
                            if ($eventDate && $eventTime) {
                                $eventDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $eventTime, 'Asia/Kuala_Lumpur');
                            } elseif ($eventDate) {
                                $eventDateTime = \Carbon\Carbon::parse($eventDate . ' 23:59:59', 'Asia/Kuala_Lumpur');
                            } else {
                                $eventDateTime = null;
                            }
                            $isExpired = $eventDateTime ? $eventDateTime->isPast() : false;
                        @endphp
                        {{ $isExpired ? 'Not Active' : 'Active' }}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1 w-100 w-md-auto" disabled title="Unavailable">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm w-100 w-md-auto" disabled>Resold</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
{{-- Past/Done Tickets Table --}}
@if($doneTransactions->count())
<div class="modern-card">
    <h4 class="mb-3"><i class="bi bi-clock-history"></i> Past/Done Tickets</h4>
    <div class="table-responsive">
        <table class="table modern-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 3%">#</th>
                    <th style="width: 22%">Event</th>
                    <th style="width: 10%">Seat</th>
                    <th style="width: 13%">Price</th>
                    <th style="width: 18%">Date Booked</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 12%">QR Code</th>
                    <th style="width: 12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doneTransactions as $i => $txn)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ optional(optional($txn->seat)->event)->event_name }}</td>
                    <td>{{ optional($txn->seat)->label }}</td>
                    <td>RM{{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->created_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</td>
                    <td>Done</td>
                    <td class="text-center">
                        <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1 w-100 w-md-auto" disabled title="Unavailable">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm w-100 w-md-auto" disabled>Done</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
</div>
@endsection