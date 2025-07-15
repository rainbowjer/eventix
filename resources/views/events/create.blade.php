@extends('layouts.app')

@section('content')

@auth
    @if(auth()->user()->role === 'organizer')
        <!-- Organizer-specific content could go here if needed -->
    @endif

<div class="container py-4 px-2 px-md-5">
    <h2 class="mb-4 fw-bold">Create New Event 🎉</h2>
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <!-- other inputs -->
    <div class="mb-3">
        <label for="banner_image" class="form-label">Event Banner</label>
        <input type="file" name="banner_image" id="banner_image" class="form-control">
    </div>
    <div class="mb-3">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" name="event_name" id="event_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="event_date" class="form-label">Event Date</label>
        <input type="date" name="event_date" id="event_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="event_time" class="form-label">Event Time</label>
        <input type="time" name="event_time" id="event_time" class="form-control" value="{{ old('event_time', $event->event_time ?? '') }}">
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" name="location" id="location" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" rows="4"></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100 w-md-auto">Create Event</button>
    </form>
</div>
<style>
@media (max-width: 600px) {
    .container { padding: 0.5rem !important; }
    .btn { width: 100% !important; margin-bottom: 0.5em; }
    .form-label, .form-control, textarea { font-size: 1rem !important; }
}
</style>

@endauth
@endsection