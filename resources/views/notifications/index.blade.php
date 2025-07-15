@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">All Notifications</h2>
    @if($notifications->isEmpty())
        <div class="alert alert-info">You have no notifications.</div>
    @else
        <ul class="list-group mb-4">
            @foreach($notifications as $notification)
                @php
                    $status = $notification->data['status'] ?? null;
                    $event = $notification->data['event_name'] ?? null;
                    $message = $notification->data['message'] ?? null;
                    $created = $notification->created_at ? $notification->created_at->diffForHumans() : '';
                    $icon = $status === 'approved' ? 'bi bi-check-circle-fill text-success' : ($status === 'rejected' ? 'bi bi-x-circle-fill text-danger' : 'bi bi-info-circle-fill text-secondary');
                    $badgeClass = $status === 'approved' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-secondary');
                @endphp
                <li class="list-group-item d-flex align-items-start gap-3">
                    <i class="{{ $icon }} fs-4 mt-1"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $event }}</div>
                        <div>{!! $message !!}</div>
                        <span class="badge {{ $badgeClass }} text-white mt-1">{{ ucfirst($status) }}</span>
                        <small class="text-muted d-block">{{ $created }}</small>
                    </div>
                </li>
            @endforeach
        </ul>
        {{ $notifications->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection 