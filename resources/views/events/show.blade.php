@extends('layouts.app')

@section('content')
<div class="container py-4 px-2 px-md-5">
    {{-- 🎉 Event Banner --}}
    @if ($event->banner_image)
        <div class="mb-4 text-center">
            <img src="{{ asset('storage/' . $event->banner_image) }}" class="img-fluid rounded shadow w-100" style="max-height: 400px; object-fit: cover;" alt="Event Banner">
        </div>
    @endif
    {{-- 🎤 Event Info --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title fw-bold mb-3">{{ $event->event_name }}</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <p class="text-muted mb-3">
                        <i class="bi bi-calendar-event"></i>
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}<br>
                        <i class="bi bi-clock-fill"></i>
                        <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}<br>
                        <i class="bi bi-geo-alt-fill"></i>
                        <strong>Location:</strong> {{ $event->location }}
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    {{-- 📜 Description --}}
                    <p class="lead mb-2">
                        {{ $event->description ?? 'No description provided for this event.' }}
                    </p>
                </div>
            </div>
            <hr>
            {{-- 🪑 Seat Info --}}
            <div class="mb-3">
                <h5 class="fw-semibold">🎟 Available Seats</h5>
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
            <a href="{{ route('book.ticket', $event->id) }}" class="btn btn-primary btn-lg mt-3 w-100 w-md-auto">
                <i class="bi bi-ticket-perforated"></i> Book Your Seat Now
            </a>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .card-title { font-size: 1.4rem !important; }
    .lead { font-size: 1rem !important; }
    .btn-lg { font-size: 1rem !important; padding: 0.75rem 1rem !important; }
    #countdown-box { font-size: 1rem !important; }
}
</style>

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