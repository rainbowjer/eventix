@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="container py-4"> {{-- ✅ no nested container --}}
    <h2 class="mb-3">🔄 Ticket Resell Management</h2>
    <p class="mb-4">Review and manage user requests to resell their tickets.</p>

    @if ($tickets->isEmpty())
        <div class="alert alert-info">No pending resell requests found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>User</th>
                        <th>Event</th>
                        <th>Seat</th>
                        <th>Original Price</th>
                        <th>Resell Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->transaction->user->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->event->event_name }}</td>
                            <td>{{ $ticket->seat->label ?? '-' }}</td>
                            <td>RM{{ number_format($ticket->price, 2) }}</td>
                            <td>RM{{ number_format($ticket->resell_price, 2) }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ ucfirst($ticket->resell_status) }}</span>
                            </td>
                            <td>
                                <form action="{{ route('organizer.resell.approve', $ticket->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success btn-sm">Approve</button>
                            </form>

                            <form action="{{ route('organizer.resell.reject', $ticket->id) }}" method="POST" class="d-inline ms-1">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection