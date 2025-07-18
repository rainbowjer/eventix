@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2>Enter Verification Code</h2>
    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="code" maxlength="6" class="form-control" required autofocus placeholder="6-digit code">
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
        @error('code')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </form>
</div>
@endsection 