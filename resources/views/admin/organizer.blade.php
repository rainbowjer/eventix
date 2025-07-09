@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📋 Organizer Management</h2>

    <div class="card">
        <div class="card-header">List of Organizers</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Login Count</th><th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizers as $organizer)
                    <tr>
                        <td>{{ $organizer->name }}</td>
                        <td>{{ $organizer->email }}</td>
                        <td>{{ $organizer->login_count }}</td>
                        <td>{{ $organizer->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No organizers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection