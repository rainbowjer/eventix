@extends('layouts.app')

@section('content')
<div class="modern-card">
    <div class="modern-title">
        <i class="bi bi-credit-card"></i> Payment Method
    </div>
    <form id="payment-form" method="POST" action="{{ route('payments.process') }}">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="total_price" value="{{ $total }}">
        @foreach($seats as $seat)
            <input type="hidden" name="seat_ids[]" value="{{ $seat->id }}">
        @endforeach

        <div class="two-column-layout">
            <!-- Left: Event Details -->
            <div class="left-column">
                <h4 class="fw-bold mb-2">{{ $event->event_name }}</h4>
                <div class="modern-info-list mb-3">
                    <span><i class="bi bi-calendar-event"></i> <strong>Date:</strong> {{ $event->event_date }}</span>
                    <span><i class="bi bi-clock"></i> <strong>Time:</strong> {{ $event->event_time ?? '—' }}</span>
                    <span><i class="bi bi-geo-alt"></i> <strong>Location:</strong> {{ $event->location ?? '—' }}</span>
                </div>
                <hr>
                <div class="modern-section-title mb-2">
                    <i class="bi bi-chair"></i> Your Seats
                </div>
                <div class="modern-seats mb-3">
                    @foreach($seats as $seat)
                        <div class="modern-seat-badge">
                            <strong>Seat:</strong> {{ $seat->label }} ({{ $seat->type }})<br>
                            <strong>Price:</strong> RM{{ number_format($seat->price, 2) }}
                        </div>
                    @endforeach
                </div>
                <div class="modern-total d-inline-block">
                    <i class="bi bi-coin"></i> Total: RM{{ number_format($total, 2) }}
                </div>
            </div>

            <!-- Right: Payment Methods -->
            <div class="right-column">
                <div class="modern-section-title mb-3" style="color:#be123c;">
                    <i class="bi bi-credit-card"></i> Choose Payment Method
                </div>
                <div class="payment-methods-grid">
                    <div class="payment-method" onclick="selectPayment('credit_card')">
                        <input type="radio" name="payment_method" value="credit_card" id="credit_card" required>
                        <label for="credit_card" class="mb-0">
                            <img src="{{ asset('images/pay/visa.png') }}" alt="Credit Card" class="payment-icon">
                            Credit/Debit Card
                            <span class="badge-recommended">Recommended</span>
                            <div class="method-desc">Pay with Visa, MasterCard, or Amex. Instant confirmation.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                    <div class="payment-method" onclick="selectPayment('fpx')">
                        <input type="radio" name="payment_method" value="fpx" id="fpx">
                        <label for="fpx" class="mb-0">
                            <img src="{{ asset('images/pay/fpx.png') }}" alt="FPX" class="payment-icon">
                            FPX Online Banking
                            <div class="method-desc">Pay directly from your bank account. Secure and instant.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                    <div class="payment-method" onclick="selectPayment('tng')">
                        <input type="radio" name="payment_method" value="tng" id="tng">
                        <label for="tng" class="mb-0">
                            <img src="{{ asset('images/pay/tng.png') }}" alt="Touch 'n Go" class="payment-icon">
                            Touch 'n Go eWallet
                            <div class="method-desc">Pay easily with your Touch 'n Go eWallet app.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                    <div class="payment-method" onclick="selectPayment('grab')">
                        <input type="radio" name="payment_method" value="grab" id="grab">
                        <label for="grab" class="mb-0">
                            <img src="{{ asset('images/pay/grab.png') }}" alt="GrabPay" class="payment-icon">
                            GrabPay
                            <div class="method-desc">Use your GrabPay wallet for fast checkout.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                    <div class="payment-method" onclick="selectPayment('shopee')">
                        <input type="radio" name="payment_method" value="shopee" id="shopee">
                        <label for="shopee" class="mb-0">
                            <img src="{{ asset('images/pay/shopee.png') }}" alt="ShopeePay" class="payment-icon">
                            ShopeePay
                            <div class="method-desc">Pay with your ShopeePay wallet. Instant and easy.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                    <div class="payment-method" onclick="selectPayment('boost')">
                        <input type="radio" name="payment_method" value="boost" id="boost">
                        <label for="boost" class="mb-0">
                            <img src="{{ asset('images/pay/boost.png') }}" alt="Boost" class="payment-icon">
                            Boost
                            <div class="method-desc">Pay with Boost eWallet. Fast and secure.</div>
                        </label>
                        <span class="checkmark">&#10003;</span>
                    </div>
                </div>
                @error('payment_method')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror
                <div class="text-center mt-4">
                    <button type="submit" class="modern-btn pay-btn">
                        <i class="bi bi-cash-coin"></i> Pay RM{{ number_format($total, 2) }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" style="display:none;">
    <div class="spinner"></div>
    <div class="loading-text">Processing your payment...</div>
</div>

<style>
body {
    background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
}
#loading-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255,255,255,0.85);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.spinner {
    border: 6px solid #e0e7ff;
    border-top: 6px solid #6366f1;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
    margin-bottom: 1.5rem;
}
@keyframes spin {
    0% { transform: rotate(0deg);}
    100% { transform: rotate(360deg);}
}
.loading-text {
    font-size: 1.2rem;
    color: #22223b;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.modern-card {
    background: #f9fafb;
    border-radius: 28px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
    padding: 2.5rem 2rem;
    max-width: 1050px;
    margin: 3rem auto;
    border: 1px solid #e5e7eb;
}
.modern-title {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 2.5rem;
    color: #22223b;
    letter-spacing: -1px;
    background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-align: center;
}
.two-column-layout {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 3rem;
    align-items: start;
}
@media (max-width: 900px) {
    .two-column-layout { grid-template-columns: 1fr; gap: 2rem; }
}
.left-column {
    border-right: 1px solid #e5e7eb;
    padding-right: 2rem;
}
@media (max-width: 900px) {
    .left-column { border-right: none; padding-right: 0; }
}
.modern-info-list span {
    display: block;
    font-size: 1.1rem;
    color: #475569;
    margin-bottom: 0.2rem;
}
.modern-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #be123c;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modern-seats {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.modern-seat-badge {
    background: #f1f5f9;
    border-radius: 16px;
    padding: 0.75rem 1rem;
    box-shadow: 0 2px 8px 0 rgba(31, 38, 135, 0.06);
    font-size: 1.08rem;
    text-align: left;
}
.modern-total {
    background: #e0fce0;
    color: #15803d;
    border-radius: 12px;
    font-size: 1.35rem;
    font-weight: bold;
    padding: 0.85rem 1.2rem;
    margin: 1.5rem 0 1rem 0;
    display: inline-block;
    box-shadow: 0 2px 8px 0 rgba(16, 185, 129, 0.08);
}
.payment-methods-grid {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}
.payment-method {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.1rem 1.2rem;
    background: #fff;
    cursor: pointer;
    transition: border 0.2s, box-shadow 0.2s, background 0.2s, transform 0.15s;
    position: relative;
}
.payment-method:hover {
    border-color: #06b6d4;
    background: #f0f9ff;
    transform: scale(1.025);
}
.payment-method.selected, .payment-method input[type=radio]:checked + label {
    border-color: #6366f1;
    background: #e0e7ff;
    box-shadow: 0 2px 8px 0 rgba(99, 102, 241, 0.10);
}
.payment-method input[type=radio] {
    margin-right: 0.75rem;
    accent-color: #6366f1;
}
.payment-method input[type=radio]:checked ~ .checkmark {
    opacity: 1;
}
.payment-icon {
    width: 44px;
    height: 28px;
    object-fit: contain;
    margin-right: 0.7rem;
}
.checkmark {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.3rem;
    color: #06b6d4;
    opacity: 0;
    transition: opacity 0.2s;
}
.badge-recommended {
    background: #06b6d4;
    color: #fff;
    font-size: 0.8rem;
    border-radius: 8px;
    padding: 0.2rem 0.7rem;
    margin-left: 0.7rem;
    font-weight: 600;
}
.method-desc {
    font-size: 0.95rem;
    color: #64748b;
    margin-top: 0.2rem;
}
.pay-btn {
    background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 1rem 2.5rem;
    font-size: 1.2rem;
    font-weight: 700;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 12px 0 rgba(31, 38, 135, 0.13);
    margin-top: 2rem;
}
.pay-btn:hover {
    background: linear-gradient(90deg, #06b6d4 0%, #6366f1 100%);
    box-shadow: 0 4px 18px 0 rgba(99, 102, 241, 0.13);
}
</style>
<script>
function selectPayment(method) {
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById(method).checked = true;
}
document.getElementById('payment-form').addEventListener('submit', function(e) {
    document.getElementById('loading-overlay').style.display = 'flex';
    e.preventDefault();
    setTimeout(() => this.submit(), 2000);
});
</script>
@endsection