@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit User</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="user" @if(old('role', $user->role) == 'user') selected @endif>User</option>
                <option value="organizer" @if(old('role', $user->role) == 'organizer') selected @endif>Organizer</option>
                <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password <span class="text-muted small">(leave blank to keep current)</span></label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 