@extends('layouts.admin')

@section('content')
<style>
    .modern-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 2rem 1.5rem;
        margin: 2rem auto;
        max-width: 1200px;
    }
    .modern-table th, .modern-table td {
        vertical-align: middle !important;
    }
    .modern-table thead th {
        background: #22223b;
        color: #fff;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 2;
        border: none;
    }
    .modern-table thead th:not(:first-child) {
        border-top-left-radius: 0;
    }
    .modern-table thead th:not(:last-child) {
        border-top-right-radius: 0;
    }
    .modern-table tbody tr {
        transition: background 0.15s;
    }
    .modern-table tbody tr:nth-child(even) {
        background: #f1f5f9;
    }
    .modern-table tbody tr:hover {
        background: #e0e7ff;
    }
    @media (max-width: 900px) {
        .modern-card { padding: 1rem 0.5rem; }
        .modern-table { font-size: 0.95rem; }
    }
    .table-responsive { overflow-x: auto; }
    /* Payment Method Usage Distribution Styles */
    .payment-method-stats {
        margin-top: 1rem;
    }
    .payment-method-stats .d-flex {
        box-shadow: 0 2px 8px rgba(31,38,135,0.06);
        border-radius: 12px;
        margin-bottom: 0.5rem;
        background: linear-gradient(90deg, rgba(34,34,59,0.9) 0%, rgba(52, 152, 219, 0.7) 100%);
        color: #fff !important;
        transition: transform 0.1s;
    }
    .payment-method-stats .d-flex:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 16px rgba(31,38,135,0.12);
    }
    .payment-method-stats strong {
        font-size: 1.1em;
    }
    #paymentMethodPieChart {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(31,38,135,0.08);
        padding: 1.5rem;
    }
    .card-header.bg-white.fw-bold {
        background: linear-gradient(90deg, #36A2EB 0%, #FFCE56 100%) !important;
        color: #fff !important;
        border-radius: 18px 18px 0 0;
        font-size: 1.15em;
        letter-spacing: 0.5px;
    }
    /* Responsive tweaks for dashboard */
    @media (max-width: 767.98px) {
        .modern-card { padding: 0.5rem 0.2rem; }
        .row.g-4 > [class^='col-'] { margin-bottom: 1.5rem; }
        .card .fs-1 { font-size: 2rem !important; }
        .card-title { font-size: 1.2rem !important; }
    }
</style>
<div class="container-fluid px-2 px-md-4">
    <h2 class="mb-4">📊 Admin Dashboard</h2>
    
    <!-- Global Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
                <div class="col-md-4 col-12">
                    <label for="global_search" class="form-label">🔍 Global Search</label>
                    <input type="text" class="form-control" id="global_search" name="global_search" 
                           value="{{ request('global_search') }}" placeholder="Search users, events, transactions...">
                </div>
                <div class="col-md-3 col-12">
                    <label for="search_type" class="form-label">Search Type</label>
                    <select class="form-select" id="search_type" name="search_type">
                        <option value="all" {{ request('search_type') == 'all' ? 'selected' : '' }}>All</option>
                        <option value="users" {{ request('search_type') == 'users' ? 'selected' : '' }}>Users</option>
                        <option value="events" {{ request('search_type') == 'events' ? 'selected' : '' }}>Events</option>
                        <option value="transactions" {{ request('search_type') == 'transactions' ? 'selected' : '' }}>Transactions</option>
                        <option value="resells" {{ request('search_type') == 'resells' ? 'selected' : '' }}>Resell Tickets</option>
                    </select>
                </div>
                <div class="col-md-3 col-12">
                    <label for="date_filter" class="form-label">Date Range</label>
                    <select class="form-select" id="date_filter" name="date_filter">
                        <option value="" {{ request('date_filter') == '' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
                <div class="col-md-2 col-12">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    
    <!-- Search Results Summary -->
    @if(request('global_search') || request('search_type') != 'all' || request('date_filter'))
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle"></i>
        <strong>Search Results:</strong>
        @if(request('global_search'))
            Searching for "{{ request('global_search') }}"
        @endif
        @if(request('search_type') != 'all')
            in {{ ucfirst(request('search_type')) }}
        @endif
        @if(request('date_filter'))
            for {{ ucfirst(request('date_filter')) }}
        @endif
    </div>
    @endif
    
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center bg-primary text-white">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-0" id="counter-users">{{ $totalUsers }}</h3>
                    <p class="card-text mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center bg-success text-white">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-0" id="counter-events">{{ $totalEvents }}</h3>
                    <p class="card-text mb-0">Total Events</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center bg-warning text-white">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-arrow-repeat fs-1"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-0" id="counter-resells">{{ $totalResells }}</h3>
                    <p class="card-text mb-0">Resell Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm text-center bg-info text-white">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-cash-stack fs-1"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-0" id="counter-revenue">
                        <span class="fw-bold">RM</span>{{ number_format($totalRevenue, 2) }}
                    </h3>
                    <p class="card-text mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Events Pie Charts -->
    @if(!empty($eventPieData))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-pie-chart"></i> Top 3 Events: Sold Tickets & Revenue
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        @foreach($eventPieData as $i => $event)
                        <div class="col-md-4 col-12 text-center mb-4">
                            <div class="fw-semibold mb-2">{{ $event['event_name'] }}</div>
                            <div id="eventPieChart{{ $i }}" style="height: 220px;"></div>
                            <div class="small text-muted mt-2">
                                Sold Tickets: {{ $event['sold'] }}<br>
                                Total Revenue: RM{{ number_format($event['sold_value'], 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Payment Method Usage Pie Chart -->
    @if(!empty($paymentMethodData))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-credit-card"></i> Payment Method Usage Distribution
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="paymentMethodPieChart" style="height: 400px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-method-stats">
                                <h6 class="fw-bold mb-3">Payment Method Statistics</h6>
                                @foreach($paymentMethodData as $method)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" 
                                     style="background: {{ $loop->index < 6 ? ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'][$loop->index] : '#E0E0E0' }}; color: white;">
                                    <div>
                                        <strong>{{ $method['method'] }}</strong>
                                        <div class="small">{{ $method['percentage'] }}%</div>
                                    </div>
                                    <div class="text-end">
                                        <div>{{ $method['count'] }} transactions</div>
                                        <div class="small">RM{{ number_format($method['total_amount'], 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Top 3 Sold Tickets Modern Card -->
    {{-- Removed as per user request --}}
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">👥 Top 3 Frequent Users</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($frequentUsers as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $user->name }} ({{ $user->email }})
                            <span class="badge bg-primary rounded-pill">{{ $user->login_count }} logins</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">🏢 Top 3 Frequent Organizers</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($frequentOrganizers as $org)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $org->name }} ({{ $org->email }})
                            <span class="badge bg-success rounded-pill">{{ $org->login_count }} logins</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2 text-end">
        <form method="GET" action="" class="d-inline-block">
            <input type="text" name="user_search" value="{{ request('user_search') }}" class="form-control form-control-sm d-inline-block w-auto" placeholder="Search users...">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
        <a href="{{ route('admin.export.users', request()->query()) }}" class="btn btn-outline-primary btn-sm ms-2" title="Export Users CSV">
            <i class="bi bi-file-earmark-spreadsheet"></i>
            <span class="visually-hidden">Export Users CSV</span>
        </a>
        <a href="{{ route('admin.export.users.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm ms-2" title="Export Users PDF">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="visually-hidden">Export Users PDF</span>
        </a>
    </div>
    <div x-data="{ open: true }" class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Users</h5>
            </div>
            <div x-show="open">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped modern-table" id="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2 text-end">
        <form method="GET" action="" class="d-inline-block">
            <input type="text" name="event_search" value="{{ request('event_search') }}" class="form-control form-control-sm d-inline-block w-auto" placeholder="Search events...">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
        <a href="{{ route('admin.export.events', request()->query()) }}" class="btn btn-outline-primary btn-sm ms-2" title="Export Events CSV">
            <i class="bi bi-file-earmark-spreadsheet"></i>
            <span class="visually-hidden">Export Events CSV</span>
        </a>
        <a href="{{ route('admin.export.events.pdf', request()->query()) }}" class="btn btn-outline-danger btn-sm ms-2" title="Export Events PDF">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="visually-hidden">Export Events PDF</span>
        </a>
    </div>
    <div x-data="{ open: true }" class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Events</h5>
            </div>
            <div x-show="open">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped modern-table" id="events-table">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>{{ $event->event_name }}</td>
                                <td>{{ $event->event_date }}</td>
                                <td>{{ $event->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $events->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2 text-end">
        <form method="GET" action="" class="d-inline-block">
            <input type="text" name="resell_search" value="{{ request('resell_search') }}" class="form-control form-control-sm d-inline-block w-auto" placeholder="Search resell tickets (user, event, status)...">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
        <a href="{{ route('admin.export.transactions', request()->query()) }}" class="btn btn-outline-primary btn-sm ms-2" title="Export Transactions CSV">
            <i class="bi bi-file-earmark-spreadsheet"></i>
            <span class="visually-hidden">Export Transactions CSV</span>
        </a>
        <a href="{{ route('admin.export.pdf') }}" class="btn btn-outline-danger btn-sm ms-2" title="Export Transactions PDF">
            <i class="bi bi-file-earmark-pdf"></i>
            <span class="visually-hidden">Export Transactions PDF</span>
        </a>
    </div>
    <div x-data="{ open: true }" class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Transactions</h5>
            </div>
            <div x-show="open">
                <div class="table-responsive" id="transactions-table">
                    <table class="table table-bordered table-striped modern-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Event</th>
                                <th>Seat</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->user->name ?? '-' }}</td>
                                <td>{{ $transaction->seat->event->event_name ?? '-' }}</td>
                                <td>{{ $transaction->seat->label ?? '-' }}</td>
                                <td>RM{{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $transactions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- jQuery, Raphael.js, and Morris.js CDN (in correct order) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($eventPieData as $i => $event)
            new Morris.Donut({
                element: 'eventPieChart{{ $i }}',
                data: [
                    { label: 'Sold Tickets', value: {{ $event['sold'] }} }
                ],
                colors: ['#36a2eb'],
                resize: true,
                formatter: function (y) { return y + ' tickets'; }
            });
            @endforeach
            
            // Payment Method Pie Chart
            @if(!empty($paymentMethodData))
            new Morris.Donut({
                element: 'paymentMethodPieChart',
                data: [
                    @foreach($paymentMethodData as $method)
                    { 
                        label: '{{ $method['method'] }}', 
                        value: {{ $method['count'] }},
                        percentage: {{ $method['percentage'] }}
                    }@if(!$loop->last),@endif
                    @endforeach
                ],
                colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                resize: true,
                formatter: function (y, data) { 
                    return data.percentage + '% (' + y + ' transactions)'; 
                }
            });
            @endif
        });
    </script>
    @endpush
</div>
@endsection

@push('scripts')
<!-- Morris.js & Raphael.js CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    // Animated counters
    function animateCounter(id, end) {
        let el = document.getElementById(id);
        if (!el) return;
        let start = 0;
        let duration = 1000;
        let startTime = null;
        function animate(currentTime) {
            if (!startTime) startTime = currentTime;
            let progress = Math.min((currentTime - startTime) / duration, 1);
            el.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                el.textContent = end.toLocaleString();
            }
        }
        requestAnimationFrame(animate);
    }
    document.addEventListener('DOMContentLoaded', function() {
        animateCounter('counter-users', {{ $totalUsers }});
        animateCounter('counter-events', {{ $totalEvents }});
        animateCounter('counter-resells', {{ $totalResells }});
        animateCounter('counter-revenue', {{ (int) $totalRevenue }});
        // The chart rendering is now handled by x-init in the blade template
    });
    // Toast notification logic
    function showToast(msg) {
        let root = document.querySelector('[x-data]');
        if (root && root.__x) {
            root.__x.$data.show = true;
            root.__x.$data.message = msg;
            setTimeout(() => { root.__x.$data.show = false; }, 2500);
        }
    }
    document.querySelectorAll('a[href*="export"]').forEach(link => {
        link.addEventListener('click', function() {
            showToast('Export started!');
        });
    });
    // No results found toast for users table
    if (document.querySelector('#users-table tbody') && document.querySelector('#users-table tbody').children.length === 0) {
        showToast('No users found.');
    }
    // No results found toast for events table
    if (document.querySelector('#events-table tbody') && document.querySelector('#events-table tbody').children.length === 0) {
        showToast('No events found.');
    }
    // No results found toast for resell tickets table
    if (document.querySelector('#transactions-table tbody') && document.querySelector('#transactions-table tbody').children.length === 0) {
        showToast('No transactions found.');
    }
    // No results found toast for admin logs table
    if (document.querySelector('table.table-bordered:last-of-type tbody') && document.querySelector('table.table-bordered:last-of-type tbody').children.length === 0) {
        showToast('No admin activity found.');
    }
</script>
@endpush