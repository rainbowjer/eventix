@extends('layouts.admin')

@section('content')
<style>
    .modern-table th, .modern-table td { vertical-align: middle !important; }
    .modern-table thead th {
        background: #22223b; color: #fff; font-weight: 700; position: sticky; top: 0; z-index: 2; border: none; font-size:0.8rem;
    }
    .modern-table tbody tr { transition: background 0.15s; }
    .modern-table tbody tr:nth-child(even) { background: #f1f5f9; }
    .modern-table tbody tr:hover { background: #e0e7ff; }
    @media (max-width: 900px) { .modern-table { font-size: 0.95rem; } }
    .table-responsive { overflow-x: auto; }
</style>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">🔁 Manage Resell Tickets</h2>
    <form method="GET" action="" class="d-flex" style="max-width:400px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Search by event, user, seat, or status...">
        <button type="submit" class="btn btn-sm btn-primary">Search</button>
    </form>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered modern-table mb-0">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Event</th>
                        <th>Seat</th>
                        <th>User</th>
                        <th>Original Price</th>
                        <th>Resell Price</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resellTickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->event->event_name ?? '-' }}</td>
                        <td>{{ $ticket->seat->label ?? '-' }}</td>
                        <td>{{ $ticket->user->name ?? '-' }}</td>
                        <td>RM{{ number_format($ticket->price, 2) }}</td>
                        <td>RM{{ number_format($ticket->resell_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $ticket->resell_status === 'approved' ? 'success' : ($ticket->resell_status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($ticket->resell_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $ticket->updated_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.resell.view', $ticket->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No resell tickets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $resellTickets->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection 