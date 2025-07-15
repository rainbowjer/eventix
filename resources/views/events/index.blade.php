@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
    }
    .modern-event-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 1.5rem 1.2rem;
        margin-bottom: 2rem;
        transition: box-shadow 0.18s, transform 0.18s, border 0.25s;
        border: 1.5px solid #e0e7ff;
        position: relative;
        min-height: 260px;
    }
    .modern-event-card:hover {
        box-shadow: 0 12px 40px 0 #a259f744, 0 2px 0 #ff6a8844;
        transform: translateY(-2px) scale(1.012);
        border: 1.5px solid #a259f7;
    }
    .modern-event-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.45em;
    }
    .modern-event-title .bi {
        font-size: 1.2em;
        color: #a259f7;
        margin-right: 0.18em;
    }
    .modern-event-details strong {
        color: #a259f7;
    }
    .btn-gradient {
        background: linear-gradient(90deg, #a259f7 0%, #6366f1 100%);
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: 0.48rem 1.7rem;
        font-weight: 700;
        font-size: 1.08rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
        box-shadow: 0 2px 12px 0 #a259f733;
        transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.13s;
        outline: none;
        position: relative;
        overflow: hidden;
        margin-right: 0.7em;
        margin-bottom: 0.5em;
    }
    .btn-gradient:last-child { margin-right: 0; }
    .btn-gradient:active, .btn-gradient:focus {
        background: linear-gradient(90deg, #6366f1 0%, #a259f7 100%);
        color: #fff;
        box-shadow: 0 4px 18px 0 #a259f799, 0 2px 0 #ff6a8888;
        outline: 2px solid #a259f7;
    }
    .btn-gradient .bi {
        font-size: 1.1em;
        margin-right: 0.2em;
    }
    .modern-header {
        font-size: 2.2rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 2.2rem;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 12px #a259f722;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .modern-header .bi {
        color: #a259f7;
        font-size: 1.3em;
    }
    @media (max-width: 900px) {
        .modern-header { font-size: 1.5rem; }
        .modern-event-card { padding: 1rem 0.5rem; }
    }
    @media (max-width: 768px) {
        .modern-header { font-size: 1.2rem; }
        .modern-event-card { padding: 1rem 0.5rem; }
        .row { flex-direction: column; }
        .col-md-6 { max-width: 100%; flex: 0 0 100%; }
    }
</style>
<div class="container py-4">
    @if(isset($isAdmin) && $isAdmin)
        <form method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Event name, location, description">
            </div>
            <div class="col-md-3">
                <label for="organizer_id" class="form-label">Organizer</label>
                <select class="form-select" id="organizer_id" name="organizer_id">
                    <option value="">All Organizers</option>
                    @foreach($organizers as $org)
                        <option value="{{ $org->id }}" {{ request('organizer_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                <a href="?export=csv{{ request()->getQueryString() ? '&' . http_build_query(request()->except('export')) : '' }}" class="btn btn-success w-100"><i class="bi bi-download"></i> Export CSV</a>
            </div>
        </form>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="modern-header"><i class="bi bi-calendar3"></i> My Events</h2>
        @if(auth()->user()->role === 'organizer' || (isset($isAdmin) && $isAdmin))
            <a href="{{ route('events.create') }}" class="btn-gradient" style="background:linear-gradient(90deg,#22c55e 0%,#16a34a 100%)"><i class="bi bi-plus-circle"></i> Create Event</a>
        @endif
    </div>

    @if ($events->isEmpty())
        <div class="alert alert-info">No events created yet.</div>
    @else
        <div class="row">
            @foreach ($events as $event)
                <div class="col-md-6 mb-4">
                    <div class="modern-event-card h-100">
                        @if ($event->banner_image)
                            <img src="{{ asset('storage/' . $event->banner_image) }}" class="card-img-top" alt="Event Banner" style="height: 200px; object-fit: cover; border-radius: 16px; margin-bottom: 1rem;">
                        @endif
                        <div class="card-body p-0">
                            <div class="modern-event-title"><i class="bi bi-music-note-beamed"></i> {{ $event->event_name }}</div>
                            <div class="modern-event-details mb-3">
                                <strong>Date:</strong> {{ $event->event_date }}<br>
                                <strong>Event Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}<br>
                                <strong>Location:</strong> {{ $event->location }}<br>
                                <strong>Seats Left:</strong> {{ $event->seats->where('is_booked', false)->count() }}<br>
                                @if(isset($isAdmin) && $isAdmin)
                                    <strong>Organizer:</strong> {{ $event->organizer ? $event->organizer->name : '-' }}<br>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="{{ route('events.edit', $event->id) }}" class="btn-gradient" style="background:linear-gradient(90deg,#a259f7 0%,#6366f1 100%)"><i class="bi bi-pencil-square"></i> Edit</a>
                            <!-- Delete Button triggers modal -->
                            <button class="btn-gradient" style="background:linear-gradient(90deg,#ff6a88 0%,#ef4444 100%)" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $event->id }}"><i class="bi bi-trash"></i> Delete</button>
                            @if(auth()->user()->role === 'admin')
                                @if(!$event->approved)
                                    {{-- Approve button: admin can approve event --}}
                                    {{--
                                    <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="d-inline-block ms-2">
                                        @csrf
                                        <button type="submit" class="btn-gradient" style="background:linear-gradient(90deg,#fbbf24 0%,#f59e42 100%)"><i class="bi bi-check-circle"></i> Approve</button>
                                    </form>
                                    --}}
                                @else
                                    <span class="badge bg-success ms-2">Approved</span>
                                @endif
                                @if($event->published)
                                    {{-- Unpublish button: admin can unpublish event --}}
                                    {{--
                                    <form action="{{ route('admin.events.unpublish', $event->id) }}" method="POST" class="d-inline-block ms-2">
                                        @csrf
                                        <button type="submit" class="btn-gradient" style="background:linear-gradient(90deg,#6366f1 0%,#a259f7 100%)"><i class="bi bi-eye-slash"></i> Unpublish</button>
                                    </form>
                                    --}}
                                    <span class="badge bg-primary ms-2">Published</span>
                                @else
                                    {{-- Publish button: admin can publish event --}}
                                    {{--
                                    <form action="{{ route('admin.events.publish', $event->id) }}" method="POST" class="d-inline-block ms-2">
                                        @csrf
                                        <button type="submit" class="btn-gradient" style="background:linear-gradient(90deg,#22c55e 0%,#16a34a 100%)"><i class="bi bi-eye"></i> Publish</button>
                                    </form>
                                    --}}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if (!$events->isEmpty())
    @foreach ($events as $event)
        <!-- Delete Confirmation Modal (moved outside the card loop) -->
        <div class="modal" id="deleteModal{{ $event->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel{{ $event->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong>{{ $event->event_name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endif
@endsection