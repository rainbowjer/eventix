@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Event 🛠️</h2>

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
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
                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Event Banner" style="max-height: 150px;">
                </div>
            @endif
            <input type="file" name="banner_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>
@endsection