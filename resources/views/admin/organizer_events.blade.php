@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Events by {{ $organizer->name }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->event_name }}</td>
                <td>{{ $event->event_date }}</td>
                <td>{{ $event->location }}</td>
                <td>{{ $event->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection 