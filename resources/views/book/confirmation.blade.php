@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
    }
    .modern-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 2.5rem 2rem;
        max-width: 450px;
        width: 100%;
        margin: 3rem auto;
    }
    .modern-seats {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .modern-seat-badge {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.06);
        font-size: 1rem;
        text-align: left;
    }
    .modern-total {
        background: #e0fce0;
        color: #15803d;
        border-radius: 10px;
        font-size: 1.25rem;
        font-weight: bold;
        padding: 0.75rem 1rem;
        margin: 1.5rem 0 1rem 0;
        display: inline-block;
    }
    .modern-btn {
        background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        transition: background 0.2s;
        box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.10);
        text-decoration: none;
        display: inline-block;
        width: auto;
    }
    .modern-btn:hover {
        background: linear-gradient(90deg, #06b6d4 0%, #6366f1 100%);
        color: #fff;
    }
    .modern-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1e293b;
    }
    .modern-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #be123c;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .modern-info-list {
        margin-bottom: 1.5rem;
    }
    .modern-info-list span {
        display: block;
        font-size: 1.05rem;
        color: #475569;
    }
    .success-icon {
        font-size: 4rem;
        color: #10b981;
        margin-bottom: 1rem;
    }
    @media (max-width: 600px) {
        .modern-card { padding: 1rem 0.5rem; }
        .modern-title { font-size: 1.2rem; }
        .modern-btn { width: 100%; padding: 0.75rem 0.5rem; font-size: 1rem; }
        .modern-seat-badge { font-size: 0.95rem; padding: 0.5rem 0.7rem; }
        .modern-total { font-size: 1rem; padding: 0.5rem 0.7rem; }
    }
</style>

<div class="modern-card">
    <div class="text-center">
        <div class="success-icon">✅</div>
        <div class="modern-title mb-3">
            🎉 Booking Confirmed!
        </div>
    </div>
    
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">{{ $event->event_name }}</h4>
        <div class="modern-info-list">
            <span><i class="bi bi-calendar-event"></i> <strong>Date:</strong> {{ $event->event_date }}</span>
            <span><i class="bi bi-clock"></i> <strong>Time:</strong> {{ $event->event_time ?? '—' }}</span>
            <span><i class="bi bi-geo-alt"></i> <strong>Location:</strong> {{ $event->location ?? '—' }}</span>
        </div>
    </div>
    
    <hr>
    
    <div class="modern-section-title mb-2">
        <i class="bi bi-chair"></i> Your Seats
    </div>
    <div class="modern-seats">
        @foreach($seats as $seat)
            <div class="modern-seat-badge">
                <strong>Seat:</strong> {{ $seat->label }} ({{ $seat->type }})<br>
                <strong>Price:</strong> RM{{ number_format($seat->price, 2) }}
            </div>
        @endforeach
    </div>
    
    <div class="text-center mt-3">
        <div class="modern-total d-inline-block">
            🪙 Total Paid: RM{{ number_format($total, 2) }}
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('book.history') }}" class="modern-btn">View Booking History</a>
    </div>
</div>
@endsection