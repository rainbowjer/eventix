<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EventiX - E-Ticketing System</title>
    <link rel="icon" type="image/png" href="{{ asset('eventix-logo.png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .card, .navbar, footer {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
        }

        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark-mode .card {
            background-color: #1e1e1e;
            border-color: #333;
        }

        body.dark-mode .navbar,
        body.dark-mode .card-header {
            background: linear-gradient(90deg, rgba(30,34,54,0.92) 0%, rgba(88,36,204,0.92) 100%) !important;
            color: #fff !important;
            backdrop-filter: blur(8px);
        }

        body.dark-mode .nav-link {
            color: #bbb !important;
        }

        body.dark-mode footer {
            background-color: #1a1a1a;
            color: #aaa;
        }

         .form-check:hover {
        background-color: #f9f9f9;
        cursor: pointer;
    }
    /* Modern Glassmorphism Navbar */
    .navbar {
        background: linear-gradient(90deg, rgba(214, 141, 225, 0.85) 0%, rgba(136,58,255,0.85) 100%) !important;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 24px rgba(44,62,80,0.10), 0 1.5px 0 rgba(136,58,255,0.08);
        border-radius: 0;
        position: sticky;
        top: 0;
        z-index: 1030;
    }
    .navbar .navbar-nav .nav-link {
        border-radius: 2rem;
        padding: 0.5rem 1.2rem;
        margin: 0 0.25rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }
    .navbar .navbar-nav .nav-link.active, .navbar .navbar-nav .nav-link:hover {
        background: rgba(251, 251, 251, 0.12);
        color: #fff !important;
    }
    .navbar .navbar-brand img {
        height: 56px;
        border-radius: 12px;
        margin-right: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        max-width: 100%; /* Responsive logo */
        height: auto;
    }
    .navbar .navbar-toggler {
        border: none;
        outline: none;
    }
    .navbar .navbar-toggler:focus {
        box-shadow: none;
    }
    .theme-toggle-btn {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 1.5rem;
        margin-left: 0.5rem;
        transition: color 0.2s;
    }
    .theme-toggle-btn:hover {
        color: #ffc107;
    }
    @media (max-width: 991.98px) {
        .navbar .navbar-nav .nav-link {
            margin: 0.25rem 0;
        }
        .navbar {
            border-radius: 0;
        }
    }
    .glow-logo {
        filter: drop-shadow(0 0 32px #fff6) drop-shadow(0 0 8px #a259f7cc);
        animation: heroLogoGlow 2.5s infinite alternate;
        border-radius: 16px;
        max-width: 100%;
        height: auto;
    }
    @keyframes heroLogoGlow {
        0% { filter: drop-shadow(0 0 12px #fff6) drop-shadow(0 0 8px #a259f7cc); }
        100% { filter: drop-shadow(0 0 32px #fff9) drop-shadow(0 0 16px #a259f7); }
    }
    /* Responsive main content padding */
    main {
        padding: 1.5rem 0.5rem;
    }
    @media (min-width: 768px) {
        main {
            padding: 2rem 1.5rem;
        }
    }
    /* Remove main padding on homepage to eliminate gap above slider */
    @if (Request::is('/'))
    main {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    @endif
    /* Responsive footer */
    footer {
        padding: 1.5rem 0.5rem;
        text-align: center;
        font-size: 1rem;
    }
    @media (min-width: 768px) {
        footer {
            padding: 2rem 1.5rem;
            font-size: 1.1rem;
        }
    }
    </style>
    <style>
    /* Fancy Dropdown Menu Content Only (not parent button) */
    .navbar .dropdown-menu.fancy-dropdown-menu {
        border-radius: 1.2rem;
        box-shadow: 0 8px 32px 0 rgba(162,89,247,0.13);
        border: none;
        margin-top: 0.5rem;
        background: linear-gradient(120deg, #fff 80%, #f3f0ff 100%);
        min-width: 210px;
        padding: 0.5rem 0.2rem;
    }
    .navbar .dropdown-menu.fancy-dropdown-menu .dropdown-item {
        border-radius: 0.8rem;
        margin: 0.1rem 0.3rem;
        font-weight: 500;
        color: #7c3aed;
        transition: background 0.15s, color 0.15s;
    }
    .navbar .dropdown-menu.fancy-dropdown-menu .dropdown-item:hover, .navbar .dropdown-menu.fancy-dropdown-menu .dropdown-item:focus {
        background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
        color: #fff;
    }
    </style>
</head>

<body>
{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-2 px-md-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo" class="glow-logo" style="height:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse py-1" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center" style="gap: 0.5rem;">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('events.all') }}">Events</a></li>
                @else
                    @if(Auth::user()->role === 'organizer')
                        <li class="nav-item"><a class="nav-link" href="{{ route('events.index') }}">My Events</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('events.all') }}">Events</a></li>
                    @endif
                @endguest
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    @if (Auth::user()->role === 'organizer')
                        <li class="nav-item"><a class="nav-link" href="{{ route('organizer.dashboard') }}">Organizer Dashboard</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="myTicketsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                My Tickets
                            </a>
                            <ul class="dropdown-menu fancy-dropdown-menu" aria-labelledby="myTicketsDropdown">
                                <li><a class="dropdown-item" href="{{ route('book.history') }}">My Ticket History</a></li>
                                <li><a class="dropdown-item" href="{{ route('resell.my') }}">My Resell Tickets</a></li>
                            </ul>
                        </li>
                    @elseif (Auth::user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                    @endif
                    @if (auth()->user()->role === 'user')
                        <li class="nav-item"><a class="nav-link" href="{{ route('user.profile') }}">My Profile</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="myTicketsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                My Tickets
                            </a>
                            <ul class="dropdown-menu fancy-dropdown-menu" aria-labelledby="myTicketsDropdown">
                                <li><a class="dropdown-item" href="{{ route('book.history') }}">My Ticket History</a></li>
                                <li><a class="dropdown-item" href="{{ route('resell.my') }}">My Resell Tickets</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item"><span class="nav-link">Welcome, {{ Auth::user()->name }}</span></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About Us
                        </a>
                        <ul class="dropdown-menu fancy-dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="{{ route('about.eventix') }}">About EventiX</a></li>
                            <li><a class="dropdown-item" href="{{ route('about.contact') }}">Contact Us</a></li>
                            <li><a class="dropdown-item" href="{{ route('about.fees') }}">Our Fees (No fee)</a></li>
                            <li><a class="dropdown-item" href="{{ route('about.manual') }}">User Manual</a></li>
                        </ul>
                    </li>
                    <!-- Notification bell dropdown moved here, before logout -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            @if (Auth::user()->notifications->where('read_at', null)->count() > 0)
                                <span class="badge bg-danger">{{ Auth::user()->notifications->where('read_at', null)->count() }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
                            @php
                                $notifications = Auth::user()->notifications->take(4);
                            @endphp
                            @forelse ($notifications as $notification)
                                <li>
                                    <a class="dropdown-item d-flex align-items-start gap-2" href="#">
                                        @php
                                            $status = $notification->data['status'] ?? null;
                                            $event = $notification->data['event_name'] ?? null;
                                            $message = $notification->data['message'] ?? null;
                                            $created = $notification->created_at ? $notification->created_at->diffForHumans() : '';
                                            $icon = $status === 'approved' ? 'bi bi-check-circle-fill text-success' : ($status === 'rejected' ? 'bi bi-x-circle-fill text-danger' : 'bi bi-info-circle-fill text-secondary');
                                            $badgeClass = $status === 'approved' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-secondary');
                                        @endphp
                                        <i class="{{ $icon }} fs-5 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $event }}</div>
                                            <div>{!! $message !!}</div>
                                            <span class="badge {{ $badgeClass }} text-white mt-1">{{ ucfirst($status) }}</span>
                                            <small class="text-muted d-block">{{ $created }}</small>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">No notifications</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-center text-primary" href="{{ route('notifications.index') }}">See all notifications</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('notifications.read') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-end text-primary">Mark all as read</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 me-1"></i> Account
                        </a>
                        <ul class="dropdown-menu fancy-dropdown-menu" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-lines-fill me-2"></i>View/Edit Profile</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endguest
                <li class="nav-item d-flex align-items-center">
                    <button id="themeToggle" class="theme-toggle-btn" title="Toggle dark mode">
                        <i id="themeIcon" class="bi bi-moon-fill"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="container @if(Request::is('/')) py-0 @else py-3 @endif">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

{{-- Content --}}
<main>
    @if (Request::is('/'))
        @yield('content')
    @else
        <div class="container py-4">
            @yield('content')
        </div>
    @endif
</main>

{{-- Footer --}}
<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        &copy; {{ date('Y') }} EventiX. All rights reserved.
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // CSRF token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.body.classList.add('dark-mode');
            icon.classList.remove('bi-moon-fill');
            icon.classList.add('bi-sun-fill');
        }
        toggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            icon.classList.toggle('bi-moon-fill', !isDark);
            icon.classList.toggle('bi-sun-fill', isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
</script>

@stack('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>