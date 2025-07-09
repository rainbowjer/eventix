@extends('layouts.app')

@section('content')

@auth
    @if(auth()->user()->role === 'organizer')
        <a href="{{ route('events.create') }}" class="container py-4"></a>
    @endif


<div class="container py-4">
    <h2>Create New Event 🎉</h2>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
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
            <label for="event_time">Event Time</label>
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

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>

@endauth
@endsection