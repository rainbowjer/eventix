@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Resell Ticket Details</h3>
    <div class="card mb-4">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Ticket ID</dt>
                <dd class="col-sm-9">{{ $ticket->id }}</dd>
                <dt class="col-sm-3">Event</dt>
                <dd class="col-sm-9">{{ $ticket->event->event_name ?? '-' }}</dd>
                <dt class="col-sm-3">Seat</dt>
                <dd class="col-sm-9">{{ $ticket->seat->label ?? '-' }}</dd>
                <dt class="col-sm-3">User</dt>
                <dd class="col-sm-9">{{ $ticket->user->name ?? '-' }} ({{ $ticket->user->email ?? '-' }})</dd>
                <dt class="col-sm-3">Original Price</dt>
                <dd class="col-sm-9">RM{{ number_format($ticket->price, 2) }}</dd>
                <dt class="col-sm-3">Resell Price</dt>
                <dd class="col-sm-9">RM{{ number_format($ticket->resell_price, 2) }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    <span class="badge bg-{{ $ticket->resell_status === 'approved' ? 'success' : ($ticket->resell_status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ ucfirst($ticket->resell_status ?? 'pending') }}
                    </span>
                </dd>
                <dt class="col-sm-3">Date Submitted</dt>
                <dd class="col-sm-9">{{ $ticket->updated_at->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Admin Internal Note</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.resell.note', $ticket->id) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="resell_admin_note" id="resell_admin_note" class="form-control" rows="3" placeholder="Add internal note...">{{ $ticket->resell_admin_note }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Note</button>
                @if(session('success'))
                    <span class="text-success ms-3">{{ session('success') }}</span>
                @endif
            </form>
        </div>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</div>
@endsection 