@extends('layouts.app')

@section('content')
<div class="container py-4 px-2 px-md-5">
    <h2 class="mb-4 fw-bold">Edit Event 🛠️</h2>
    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" name="event_name" class="form-control" value="{{ $event->event_name }}" required>
        </div>
        <div class="mb-3">
            <label for="event_date" class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" value="{{ $event->event_date }}" required>
        </div>
        <div class="mb-3">
            <label for="event_time" class="form-label">Event Time</label>
            <input type="time" name="event_time" class="form-control" value="{{ $event->event_time }}" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control">{{ $event->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="banner_image" class="form-label">Event Banner</label>
            @if ($event->banner_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Event Banner" class="img-fluid w-100 rounded shadow-sm" style="max-height: 150px; object-fit: cover;">
                </div>
            @endif
            <input type="file" name="banner_image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100 w-md-auto">Update Event</button>
    </form>
</div>
<style>
@media (max-width: 600px) {
    .container { padding: 0.5rem !important; }
    .btn { width: 100% !important; margin-bottom: 0.5em; }
    .form-label, .form-control, textarea { font-size: 1rem !important; }
}
</style>
@endsection