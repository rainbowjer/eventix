@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h4 class="mb-4">{{ $event->event_name }} – Choose Your Seats</h4>

    {{-- ⏳ Countdown --}}
    <div id="countdown" class="mb-3 text-danger fw-semibold"></div>

    {{-- Resell Tickets Section --}}
    @php
        $resellTickets = \App\Models\Ticket::where('event_id', $event->id)
            ->where('is_resell', true)
            ->where('resell_status', 'approved')
            ->whereNull('user_id')
            ->with('seat')
            ->get();
    @endphp
    @if($resellTickets->count())
        <div class="alert alert-warning text-start mb-4">
            <h5 class="fw-bold mb-2">Available Resell Tickets</h5>
            <ul class="list-unstyled mb-2">
                @foreach($resellTickets as $resell)
                    <li class="mb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <span>
                            <strong>Seat:</strong> {{ $resell->seat->label ?? '-' }}
                            <span class="badge bg-info text-dark ms-2">Resell</span>
                            <strong class="ms-2">Price:</strong> RM{{ number_format($resell->price, 2) }}
                        </span>
                        <form method="POST" action="{{ route('resell.buy', $resell->id) }}" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">Buy Resell Ticket</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <small class="text-muted">Resell tickets are sold by other users and are first-come, first-served.</small>
        </div>
    @endif

    {{-- ✅ Form STARTS --}}
    <form method="POST" action="{{ route('book.prepare', $event->id) }}">
        @csrf

        {{-- Legend --}}
        <div class="d-flex flex-wrap justify-content-center gap-2 gap-md-3 mb-3">
            <span class="badge bg-warning text-dark mb-2">🎟️ VIP</span>
            <span class="badge bg-primary mb-2">🪑 General</span>
            <span class="badge bg-orange text-white mb-2" style="background-color: darkorange;">💺 Economy</span>
            <span class="badge bg-success mb-2">✅ Selected</span>
            <span class="badge bg-secondary mb-2">❌ Booked</span>
        </div>

        {{-- Seat Map --}}
        <div class="seat-map-wrapper w-100 px-1 px-md-4">
            <div class="stage mb-3">STAGE</div>

            {{-- VIP --}}
            <div class="zone zone-vip mb-4">
                <div class="zone-label mb-2">🎟️ VIP - (RM100)</div>
                <div class="seat-row justify-content-center flex-wrap">
                    @php
                        $vipGrouped = $event->seats->where('type', 'VIP')->groupBy(fn($seat) => substr($seat->label, 0, 1));
                    @endphp
                    @foreach ($vipGrouped as $row => $seatsInRow)
                        <div class="d-flex align-items-center mb-2 flex-wrap">
                            <div class="me-2 fw-bold" style="width: 30px;">{{ $row }}</div>
                            <div class="seat-row flex-wrap">
                                @foreach ($seatsInRow as $seat)
                                    @include('components.seat-button', ['seat' => $seat, 'color' => 'warning'])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- General --}}
            <div class="zone zone-general mb-4">
                <div class="zone-label mb-2">🪑 GENERAL - (RM80)</div>
                <div class="seat-row justify-content-center flex-wrap">
                    @php
                        $generalGrouped = $event->seats->where('type', 'GENERAL')->groupBy(fn($seat) => substr($seat->label, 0, 1));
                    @endphp
                    @foreach ($generalGrouped as $row => $seatsInRow)
                        <div class="d-flex align-items-center mb-2 flex-wrap">
                            <div class="me-2 fw-bold" style="width: 30px;">{{ $row }}</div>
                            <div class="seat-row flex-wrap">
                                @foreach ($seatsInRow as $seat)
                                    @include('components.seat-button', ['seat' => $seat, 'color' => 'primary'])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Economy --}}
            <div class="zone zone-economy">
                <div class="zone-label mb-2">💺 ECONOMY - (RM50)</div>
                <div class="seat-row justify-content-center flex-wrap">
                    @php
                        $economyGrouped = $event->seats->where('type', 'ECONOMY')->groupBy(fn($seat) => substr($seat->label, 0, 1));
                    @endphp
                    @foreach ($economyGrouped as $row => $seatsInRow)
                        <div class="d-flex align-items-center mb-2 flex-wrap">
                            <div class="me-2 fw-bold" style="width: 30px;">{{ $row }}</div>
                            <div class="seat-row flex-wrap">
                                @foreach ($seatsInRow as $seat)
                                    @include('components.seat-button', ['seat' => $seat, 'color' => 'orange'])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div id="selected-summary" class="mt-3 fw-semibold text-success"></div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Submit button INSIDE the form --}}
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success w-100 w-md-auto">
                🎟️ Confirm Booking & Make Payment
            </button>
        </div>

        <div class="alert alert-info mt-3 d-none" id="seat-warning">
    ⚠️ Please select at least one seat before continuing.
</div>
    </form> {{-- ✅ Corrected position --}}

</div>

{{-- ✅ JavaScript --}}
<script>
    let seconds = 300;
    const countdownEl = document.getElementById('countdown');

    const interval = setInterval(() => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        countdownEl.textContent = `⏳ Time left: ${mins}:${secs.toString().padStart(2, '0')}`;
        if (--seconds < 0) {
            clearInterval(interval);
            countdownEl.textContent = "⛔ Session expired. Please reload.";
            document.querySelectorAll('form input, form button').forEach(e => e.disabled = true);
        }
    }, 1000);

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (e) {
    e.preventDefault(); // stop form default

    const selected = document.querySelectorAll('.seat-checkbox:checked');
    const warning = document.getElementById('seat-warning');

    if (selected.length === 0) {
        warning.classList.remove('d-none');
        return;
    } else {
        warning.classList.add('d-none');
    }

    // ✅ Wrap inside requestAnimationFrame to ensure DOM is updated BEFORE submit
    requestAnimationFrame(() => {
        // Remove existing hidden inputs
        form.querySelectorAll('input[name="seat_ids[]"]').forEach(el => el.remove());

        // Add hidden inputs for each selected checkbox
        selected.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seat_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        console.log("Submitting seat_ids:", [...selected].map(cb => cb.value));

        form.submit(); // ✅ Submit after DOM update
    });
});
    // Visual feedback on checkbox change
    document.querySelectorAll('.seat-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const label = this.closest('label');
            label.classList.toggle('selected-seat', cb.checked);
            updateSummary();
        });
    });

    updateSummary();
});

function updateSummary() {
    let selected = [];
    let total = 0;

    document.querySelectorAll('.seat-checkbox:checked').forEach(cb => {
        const label = cb.closest('label');
        const seatLabel = label.querySelector('span')?.innerText.trim();
        const price = parseFloat(cb.dataset.price || 0);
        selected.push(seatLabel);
        total += price;
    });

    const summaryBox = document.getElementById('selected-summary');
    summaryBox.textContent = selected.length
        ? `🪑 Selected: ${selected.join(', ')} | 💰 Total: RM${total.toFixed(2)}`
        : '';
}
</script>

{{-- ✅ CSS remains unchanged --}}
<style>
    .seat-map-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .stage {
        background: #333;
        color: #fff;
        padding: 10px 40px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 2rem;
    }
    .seat-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }
    .zone-label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .btn-seat {
        border-radius: 30px !important;
        padding: 10px 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        gap: 6px;
        transition: all 0.3s ease;
    }
    .btn-outline-orange {
        color: darkorange;
        border-color: darkorange;
    }
    .btn-outline-orange:hover {
        background-color: darkorange;
        color: white;
    }
    .btn-seat.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .btn-seat input[type="checkbox"] {
        display:block !important;
    }
    .btn-seat:hover {
        transform: scale(1.05);
    }
    .btn-seat.selected-seat {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: white !important;
    }
    .seat-price-tooltip {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #fff;
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 10;
    }
    .btn-seat:hover .seat-price-tooltip {
        opacity: 1;
    }
    @media (max-width: 768px) {
        .seat-map-wrapper { padding: 0 0.2rem !important; }
        .zone-label { font-size: 1rem; }
        .seat-row { flex-wrap: wrap; }
        .btn, .badge { width: 100%; margin-bottom: 0.5em; }
    }
</style>
<style>
.visual-seat-map {
    max-width: 800px;
    margin: 0 auto 2rem auto;
    padding: 2rem 1rem 2.5rem 1rem;
    background: #181818;
    border-radius: 1.5rem;
    color: #fff;
    box-shadow: 0 8px 32px 0 #0005;
    text-align: center;
    position: relative;
}
.stage-visual {
    font-size: 1.5rem;
    font-weight: bold;
    background: linear-gradient(90deg, #fff 60%, #ffe066 100%);
    color: #222;
    border-radius: 1rem 1rem 0 0;
    margin: 0 auto 2rem auto;
    padding: 0.7rem 0;
    width: 60%;
    box-shadow: 0 4px 24px #ffe06655;
    letter-spacing: 2px;
}
.seat-row-visual {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.2rem;
    gap: 0.7rem;
}
.row-label {
    font-weight: bold;
    margin-right: 1.2rem;
    font-size: 1.1rem;
    color: #ffe066;
    min-width: 2.5rem;
    text-align: right;
}
.seat-btn-visual {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    margin: 0 0.2rem;
    background: #444;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
    box-shadow: 0 2px 8px #0003;
    outline: none;
    position: relative;
}
.seat-btn-visual.vip { background: #ffe066; color: #222; }
.seat-btn-visual.general { background: #a5d8ff; color: #222; }
.seat-btn-visual.economy { background: #ffd6a5; color: #222; }
.seat-btn-visual.booked {
    background: #888 !important;
    color: #fff !important;
    cursor: not-allowed;
    opacity: 0.6;
    position: relative;
}
.seat-btn-visual.booked::after {
    content: "\1F512";
    position: absolute;
    top: 0.2rem;
    right: 0.2rem;
    font-size: 1rem;
}
.seat-btn-visual:hover:not(.booked) {
    background: #4caf50;
    color: #fff;
    transform: scale(1.1);
    z-index: 2;
}
.seat-btn-visual.selected {
    background: #4caf50 !important;
    color: #fff !important;
    box-shadow: 0 0 0 3px #81c78499;
    border: 2px solid #388e3c;
    z-index: 2;
}
</style>
@endsection