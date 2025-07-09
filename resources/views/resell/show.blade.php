@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

        <div class="mb-4 text-center">
                <h2 class="fw-bold"><i class="bi bi-file-earmark-text"></i> Resell Ticket</h2>
                <p class="text-muted">You can set a resell price for your ticket.</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="mb-3">
                        <strong>Event:</strong> {{ $transaction->ticket->event->event_name ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Seat:</strong> {{ $transaction->ticket->seat->label ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Original Price:</strong> RM{{ number_format($transaction->ticket->price, 2) }}
                    </div>

                    <form method="POST" action="{{ route('resell.post', $transaction->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="resell_price" class="form-label fw-semibold">Resell Price (RM)</label>
                            <input type="number" step="0.01" name="resell_price" id="resell_price"
                                class="form-control @error('resell_price') is-invalid @enderror" required>

                            @error('resell_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-cash-coin me-1"></i> Submit Resell Request
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection