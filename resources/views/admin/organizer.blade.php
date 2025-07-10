@extends('layouts.admin')

@section('content')
<style>
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
        font-size:0.8rem;
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
        .modern-table { font-size: 0.95rem; }
    }
    .table-responsive { overflow-x: auto; }
</style>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🧑‍🔧 Manage Organizers</h2>
        <form method="GET" action="" class="d-flex" style="max-width:350px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Search organizers by name or email...">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Events Created</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizers as $organizer)
                        <tr>
                            <td>{{ $organizer->name }}</td>
                            <td>{{ $organizer->email }}</td>
                            <td>{{ $organizer->events_count ?? $organizer->events->count() }}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>{{ $organizer->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.organizer.events', $organizer->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No organizers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $organizers->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection