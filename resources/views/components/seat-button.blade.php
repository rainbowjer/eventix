@php
    $isBooked = $seat->is_booked;
@endphp

<label class="btn btn-outline-{{ $color }} btn-seat {{ $isBooked ? 'disabled' : '' }}">
    <input 
    type="checkbox" 
    class="seat-checkbox d-none" 
    name="seat_ids[]" 
    value="{{ $seat->id }}" 
    data-price="{{ $seat->price }}" 
    autocomplete="off"
/>

    <span>{{ $seat->label }}</span>

    {{-- Optional: Price popup --}}
    <div class="seat-price-tooltip">{{ 'RM' . number_format($seat->price, 2) }}</div>
</label>