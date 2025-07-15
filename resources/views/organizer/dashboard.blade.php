@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
    }
    .modern-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 2rem 1.5rem;
        margin: 2rem auto 1.5rem auto;
        max-width: 1200px;
        transition: box-shadow 0.18s, transform 0.18s, border 0.25s;
        border: 1.5px solid #e0e7ff;
        position: relative;
    }
    .modern-card:hover {
        box-shadow: 0 12px 40px 0 #a259f744, 0 2px 0 #ff6a8844;
        transform: translateY(-2px) scale(1.012);
        border: 1.5px solid #a259f7;
    }
    .dashboard-header {
        font-size: 2.2rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 2.2rem;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 12px #a259f722;
    }
    .dashboard-header .glow {
        color: #a259f7;
        text-shadow: 0 0 8px #a259f755, 0 2px 8px #ff6a8822;
        animation: none;
    }
    .modern-card .card-title {
        font-size: 1.18rem;
        font-weight: 700;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.45em;
    }
    .modern-card .card-title .bi {
        font-size: 1.25em;
        vertical-align: middle;
        color: #a259f7;
        margin-right: 0.18em;
    }
    .badge {
        font-size: 0.92em;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.22em 1.1em;
        margin-left: 0.5em;
        background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
        color: #fff;
        box-shadow: 0 1.5px 6px #a259f722;
        border: none;
        letter-spacing: 0.2px;
        display: inline-block;
    }
    .btn-gradient {
        background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
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
        background: linear-gradient(90deg, #06b6d4 0%, #6366f1 100%);
        color: #fff;
        box-shadow: 0 4px 18px 0 #a259f799, 0 2px 0 #ff6a8888;
        transform: scale(1.035);
        outline: 2px solid #a259f7;
    }
    .btn-gradient .bi {
        font-size: 1.1em;
        margin-right: 0.2em;
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background: rgba(255,255,255,0.3); /* or any color you like */
        pointer-events: none;
        z-index: 2;
    }

    @keyframes ripple {
        to {
            transform: scale(2.5);
            opacity: 0;
        }
    }
    @media (max-width: 900px) {
        .dashboard-header { font-size: 1.5rem; }
        .modern-card { padding: 1rem 0.5rem; }
    }
    @media (max-width: 768px) {
        .dashboard-header { font-size: 1.2rem; }
        .modern-card { padding: 1rem 0.5rem; }
        .row.g-4 { flex-direction: column; }
        .col-md-6 { max-width: 100%; flex: 0 0 100%; }
        .btn-gradient { width: 100%; justify-content: center; margin-bottom: 0.5em; }
    }
    @media (max-width: 480px) {
        .dashboard-header { font-size: 1rem; }
        .modern-card { padding: 0.7rem 0.2rem; }
        .modern-card .card-title { font-size: 1rem; }
        .btn-gradient { font-size: 0.98rem; padding: 0.48rem 1rem; }
    }
</style>
<script>
// Button ripple effect
function addRipple(e) {
    const btn = e.currentTarget;
    const circle = document.createElement('span');
    circle.className = 'ripple';
    const rect = btn.getBoundingClientRect();
    circle.style.width = circle.style.height = Math.max(rect.width, rect.height) + 'px';
    circle.style.left = (e.clientX - rect.left - rect.width/2) + 'px';
    circle.style.top = (e.clientY - rect.top - rect.height/2) + 'px';
    btn.appendChild(circle);
    setTimeout(()=>circle.remove(), 600);
}
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-gradient').forEach(btn => {
        btn.addEventListener('click', addRipple);
    });
});
</script>
<div class="dashboard-bg">
<div class="container py-4">
    <h2 class="dashboard-header">Welcome, <span class="glow">{{ Auth::user()->name }}</span> 🎉</h2>
    <div class="row g-4">
        <!-- My Events -->
        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-event"></i> My Events <span class="badge">Organizer</span></h5>
                    <p class="card-text">Manage your upcoming and past events.</p>
                    <a href="{{ route('events.index') }}" class="btn-gradient" style="background:linear-gradient(90deg,#a259f7 0%,#6366f1 100%)" aria-label="View Events"><i class="bi bi-eye"></i> View Events</a>
                    <a href="{{ route('events.create') }}" class="btn-gradient" style="background:linear-gradient(90deg,#22c55e 0%,#16a34a 100%)" aria-label="Create Event"><i class="bi bi-plus-circle"></i> Create Event</a>
                </div>
            </div>
        </div>
        <!-- Ticket Sales -->
        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-ticket-perforated"></i> Ticket Sales <span class="badge">Report</span></h5>
                    <p class="card-text">Track ticket sales performance.</p>
                    <a href="{{ route('organizer.tickets') }}" class="btn-gradient" style="background:linear-gradient(90deg,#a259f7 0%,#6366f1 100%)" aria-label="View Ticket Reports"><i class="bi bi-bar-chart"></i> View Ticket Reports</a>
                </div>
            </div>
        </div>
        <!-- Resell Management -->
        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-arrow-repeat"></i> Resell Requests <span class="badge">Resell</span></h5>
                    <p class="card-text">Review and approve resell ticket submissions.</p>
                    <a href="{{ route('organizer.resell') }}" class="btn-gradient" style="background:linear-gradient(90deg,#a259f7 0%,#6366f1 100%)" aria-label="Manage Resells"><i class="bi bi-clipboard-check"></i> Manage Resells</a>
                </div>
            </div>
        </div>
        <!-- Account Settings -->
        <div class="col-md-6">
            <div class="modern-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-gear"></i> Account Settings <span class="badge">Profile</span></h5>
                    <p class="card-text">Update your profile or change your password.</p>
                    <a href="{{ route('profile.edit') }}" class="btn-gradient" style="background:linear-gradient(90deg,#22c55e 0%,#16a34a 100%)" aria-label="Edit Profile"><i class="bi bi-person-circle"></i> Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection