<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventiX Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('eventix-logo.png') }}">   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            font-size: 0.75rem;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #0d6efd;
        }
        .pagination .page-link {
        font-size: 0.875rem; /* Smaller font for arrows */
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-link svg {
        width: 1em;
        height: 1em;
    }

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (offcanvas for mobile) -->
        <nav id="adminSidebar" class="col-md-2 d-none d-md-block sidebar py-4">
            <a href="{{ route('welcome') }}" style="text-decoration:none;color:inherit;">
                <h4 class="text-center">🎫 EventiX</h4>
            </a>
            <hr>
            <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">👥 Manage Users</a>
            <a href="{{ route('admin.events.index') }}">🎟️ Manage Events</a>
            <a href="{{ route('admin.organizer') }}"  class="{{ Request::is('admin/organizers') ? 'active' : '' }}">🧑‍🔧 Manage Organizer</a>
            <a href="{{ route('admin.resell.tickets') }}" class="{{ Request::is('admin/resell-tickets') ? 'active' : '' }}">
                🔁 Resell Tickets
            </a>
            <a href="{{ route('admin.report') }}" class="{{ Request::is('admin/report') ? 'active' : '' }}">📈 Reports</a>
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100 mt-3">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="visually-hidden">Logout</span>
                </button>
            </form>
        </nav>
        <!-- Offcanvas Sidebar for mobile -->
        <div class="d-md-none">
            <button class="btn btn-primary m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <i class="bi bi-list"></i> Menu
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasSidebarLabel">EventiX Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body sidebar">
                    <a href="{{ route('welcome') }}" style="text-decoration:none;color:inherit;">
                        <h4 class="text-center">🎫 EventiX</h4>
                    </a>
                    <hr>
                    <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">👥 Manage Users</a>
                    <a href="{{ route('admin.events.index') }}">🎟️ Manage Events</a>
                    <a href="{{ route('admin.organizer') }}"  class="{{ Request::is('admin/organizers') ? 'active' : '' }}">🧑‍🔧 Manage Organizer</a>
                    <a href="{{ route('admin.resell.tickets') }}" class="{{ Request::is('admin/resell-tickets') ? 'active' : '' }}">
                        🔁 Resell Tickets
                    </a>
                    <a href="{{ route('admin.report') }}" class="{{ Request::is('admin/report') ? 'active' : '' }}">📈 Reports</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 mt-3">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="visually-hidden">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <main class="col-12 col-md-10 py-4 px-2 px-md-4">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>