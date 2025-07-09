@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- 🎉 Event Banner --}}
    @if ($event->banner_image)
        <div class="mb-4 text-center">
            <img src="{{ asset('storage/' . $event->banner_image) }}" class="img-fluid rounded shadow" style="max-height: 400px;" alt="Event Banner">
        </div>
    @endif

    {{-- 🎤 Event Info --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title">{{ $event->event_name }}</h2>

            <p class="text-muted mb-3">
                <i class="bi bi-calendar-event"></i>
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                <br>
                <i class="bi bi-clock-fill"></i>
                <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                <br>
                <i class="bi bi-geo-alt-fill"></i>
                <strong>Location:</strong> {{ $event->location }}
            </p>

            {{-- 📜 Description --}}
            <p class="lead">
                {{ $event->description ?? 'No description provided for this event.' }}
            </p>

            <hr>

            {{-- 🪑 Seat Info --}}
            <div class="mb-3">
                <h5>🎟 Available Seats</h5>
                <ul class="list-unstyled">
                    @php
                        $types = ['VIP' => 150, 'GENERAL' => 80, 'ECONOMY' => 50];
                    @endphp
                    @foreach ($types as $type => $price)
                        @php
                            $count = $event->seats->where('type', $type)->where('is_booked', false)->count();
                        @endphp
                        @if ($count > 0)
                            <li class="mb-1">
                                <strong>{{ $type }}</strong> — {{ $count }} seats available @ RM{{ $price }}
                            </li>
                        @endif
                    @endforeach
                </ul>
                <small class="text-muted">Total seats: {{ $event->seats->count() }}</small>
            </div>

            {{-- ⏳ Countdown --}}
            <div class="alert alert-info" id="countdown-box">
                <strong>⏳ Event starts in: <span id="countdown"></span></strong>
            </div>

            {{-- 🎟 Book Now --}}
            <a href="{{ route('book.ticket', $event->id) }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-ticket-perforated"></i> Book Your Seat Now
            </a>
        </div>
    </div>
</div>

{{-- Countdown Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const eventDate = new Date("{{ $event->event_date }}T00:00:00").getTime();
        const countdownEl = document.getElementById('countdown');

        const x = setInterval(function () {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                clearInterval(x);
                countdownEl.innerHTML = "The event has started!";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }, 1000);
    });
</script>
@endsection