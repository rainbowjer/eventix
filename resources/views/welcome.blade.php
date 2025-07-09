@extends('layouts.app')
@section('content')
<!-- Prevent unwanted scrollbars -->
<style>
body {
    overflow-x: hidden !important;
    overflow-y: auto;
}
.top-slider-container {
    width: 100%; /* changed from 100vw to 100% */
    max-width: 100vw;
    overflow-x: hidden;
    position: relative;
    z-index: 10;
}
.top-slider .carousel-item img {
    width: 100%; /* changed from 100vw to 100% */
    height: 60vh;
    object-fit: cover;
    transition: transform 2.5s cubic-bezier(.4,0,.2,1);
    will-change: transform;
}
.top-slider .carousel-item.active img {
    animation: sliderZoom 7s ease-in-out;
}
@keyframes sliderZoom {
    0% { transform: scale(1); }
    100% { transform: scale(1.08); }
}
.top-slider .carousel-caption {
    background: rgba(34, 34, 54, 0.35);
    backdrop-filter: blur(12px) saturate(1.2);
    border-radius: 1.5rem;
    padding: 2rem 2.5rem 1.5rem 2.5rem;
    box-shadow: 0 8px 32px 0 #a259f733;
    color: #fff;
    left: 50%;
    transform: translateX(-50%);
    bottom: 12%;
    max-width: 480px;
}
.top-slider .carousel-caption h5 {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-shadow: 0 2px 24px #a259f7cc;
}
.top-slider .carousel-caption p {
    font-size: 1.15rem;
    margin-bottom: 1.2rem;
    color: #f3f0ff;
}
.top-slider .carousel-caption .slider-btn {
    background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    font-size: 1.08rem;
    padding: 0.7rem 2.2rem;
    box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
    letter-spacing: 1px;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
}
.top-slider .carousel-caption .slider-btn:hover {
    background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
    color: #fff;
    box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
    transform: scale(1.04);
}
.top-slider .carousel-control-prev, .top-slider .carousel-control-next {
    width: 5%;
}
.top-slider .carousel-control-prev-icon, .top-slider .carousel-control-next-icon {
    filter: drop-shadow(0 0 8px #a259f7cc);
}
@media (max-width: 768px) {
    .top-slider .carousel-caption {
        padding: 1.2rem 1rem 1rem 1rem;
        max-width: 95vw;
    }
    .top-slider .carousel-caption h5 { font-size: 1.2rem; }
    .top-slider .carousel-caption p { font-size: 0.98rem; }
    .top-slider .carousel-caption .slider-btn { font-size: 0.98rem; padding: 0.6rem 1.2rem; }
    .top-slider .carousel-item img { height: 32vh; }
    .hero-bg { min-height: 70vh; padding: 1.5rem 0; }
    .hero-content { padding: 2.2rem 0.2rem 1.5rem 0.2rem; }
    .hero-title { font-size: 1.3rem; }
    .hero-desc { font-size: 1.01rem; }
    .hero-logo img { height: 70px !important; }
    .hero-cta-btn { font-size: 1rem; padding: 0.8rem 1.5rem; }
    .hero-floating-notes { display: none; } /* Hide floating notes on mobile for clarity */
}
@media (max-width: 480px) {
    .hero-title { font-size: 1.05rem; }
    .hero-logo img { height: 54px !important; }
    .hero-content { padding: 1.2rem 0.1rem 1rem 0.1rem; }
}
.top-slider .carousel-control-prev, .top-slider .carousel-control-next {
    width: 12%; /* Larger tap area for mobile */
}
.top-slider .carousel-indicators [data-bs-target] {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin: 0 4px;
}
</style>
<div class="top-slider-container">
    <div id="homepageCarousel" class="carousel slide carousel-fade top-slider" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homepageCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homepageCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homepageCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/concert1.jpg') }}" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/concert3.jpg') }}" class="d-block w-100" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/concert2.jpg') }}" class="d-block w-100" alt="Slide 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homepageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homepageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- 🎉 MODERN HERO SECTION (now just below slider) -->
<style>
.hero-bg {
    width: 100%;
    min-height: 30h;
    background: url('https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=1600&q=80') center center/cover no-repeat;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.hero-bg::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(120deg, rgba(162,89,247,0.65) 0%, rgba(243,123,241,0.55) 100%);
    z-index: 1;
}
.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
    padding: 4.5rem 1.2rem 3.5rem 1.2rem;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.hero-logo {
    margin-bottom: 1.5rem;
    filter: drop-shadow(0 0 32px #fff6) drop-shadow(0 0 8px #a259f7cc);
    animation: heroLogoGlow 2.5s infinite alternate;
    display: flex;
    justify-content: center;
    align-items: center;
}
.glow-logo {
    filter: drop-shadow(0 0 32px #fff6) drop-shadow(0 0 8px #a259f7cc);
    animation: heroLogoGlow 2.5s infinite alternate;
    border-radius: 16px;
}
@keyframes heroLogoGlow {
    0% { filter: drop-shadow(0 0 12px #fff6) drop-shadow(0 0 8px #a259f7cc); }
    100% { filter: drop-shadow(0 0 32px #fff9) drop-shadow(0 0 16px #a259f7); }
}
.hero-title {
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 2.7rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    margin-bottom: 1.1rem;
    text-shadow: 0 2px 24px #a259f7cc;
}
.hero-desc {
    font-size: 1.25rem;
    font-weight: 400;
    margin-bottom: 2.2rem;
    color: #f3f0ff;
    text-shadow: 0 1px 8px #a259f7cc;
}
.hero-cta-btn {
    background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    font-size: 1.18rem;
    padding: 1rem 2.7rem;
    box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
    letter-spacing: 1px;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
    animation: pulseBtn 1.8s infinite;
}
@keyframes pulseBtn {
    0% { box-shadow: 0 0 0 0 #a259f755; }
    70% { box-shadow: 0 0 0 16px #a259f701; }
    100% { box-shadow: 0 0 0 0 #a259f701; }
}
.hero-cta-btn:hover, .hero-cta-btn:focus {
    background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
    color: #fff;
    box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
    transform: scale(1.04);
}
.hero-floating-notes {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    pointer-events: none;
    z-index: 2;
}
.hero-note {
    position: absolute;
    opacity: 0.7;
    animation: floatNote 7s linear infinite;
}
@keyframes floatNote {
    0% { transform: translateY(100vh) scale(1) rotate(0deg); opacity: 0.7; }
    80% { opacity: 1; }
    100% { transform: translateY(-10vh) scale(1.2) rotate(30deg); opacity: 0; }
}
@media (max-width: 600px) {
    .hero-title { font-size: 1.5rem; }
    .hero-desc { font-size: 1.01rem; }
    .hero-content { padding: 2.2rem 0.2rem 1.5rem 0.2rem; }
}
</style>
<div class="hero-bg">
    <div class="hero-floating-notes">
        <svg class="hero-note" style="left:15%;animation-delay:0.5s;" width="32" height="32" viewBox="0 0 40 40"><text x="0" y="30" font-size="32">🎵</text></svg>
        <svg class="hero-note" style="left:30%;animation-delay:1.2s;" width="32" height="32" viewBox="0 0 32 32"><text x="0" y="24" font-size="32">🎶</text></svg>
        <svg class="hero-note" style="left:60%;animation-delay:2.1s;" width="32" height="32" viewBox="0 0 32 32"><text x="0" y="24" font-size="32">🎤</text></svg>
        <svg class="hero-note" style="left:80%;animation-delay:0.7s;" width="32" height="32" viewBox="0 0 32 32"><text x="0" y="24" font-size="32">🎸</text></svg>
        <svg class="hero-note" style="left:50%;animation-delay:1.7s;" width="32" height="32" viewBox="0 0 32 32"><text x="0" y="24" font-size="32">🎷</text></svg>
    </div>
    <div class="hero-content">
        <div class="hero-logo">
            
        </div>
        <div class="hero-title">Welcome to EventiX</div>
        <div class="hero-desc">Your all-in-one e-ticketing solution for concerts, shows, and events in Malaysia.<br>Book, resell, and experience the best events with ease!</div>
        <!-- <a href="{{ route('events.all') }}" class="hero-cta-btn">Browse Events</a> -->
    </div>
</div>

<!-- ✅ ABOUT SECTION (Side by Side) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-0">
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="min-height:220px;">
                <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo" class="glow-logo" style="height:180px;">
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold">About EventiX</h2>
                <p class="lead ">
                    EventiX is a powerful and user-friendly e-ticketing platform that lets you discover, book, and resell tickets for events across Malaysia. Whether you're attending concerts, workshops, sports, or community events — we make the process simple, fast, and secure.
                </p>
                <p>
                    Event organizers can easily create, manage, and sell tickets, while users can browse upcoming events, purchase tickets, and even resell them if needed. All in one place.
                </p>
                <!-- <a href="{{ route('events.index') }}" class="btn btn-outline-primary mt-3">Explore Events</a> -->
            </div>
        </div>
    </div>
</section>
<style>
.about-row-tight {
    gap: 0.5rem !important;
}
@media (min-width: 768px) {
    .about-row-tight {
        gap: 1.5rem !important;
    }
}
</style>

<!-- ✅ HOW IT WORKS SECTION -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">How It Works</h2>
        <div class="row text-center">
            <!-- Step 1 -->
            <div class="col-md-4 mb-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-search display-4 text-primary mb-3"></i>
                    <h5 class="fw-bold">1. Discover Events</h5>
                    <p>Browse a variety of events including concerts, workshops, sports, and more happening near you.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-md-4 mb-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-ticket-perforated display-4 text-success mb-3"></i>
                    <h5 class="fw-bold">2. Book Your Ticket</h5>
                    <p>Choose your seat and pay securely through our platform. Your e-ticket will be sent instantly.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-md-4 mb-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-arrow-repeat display-4 text-warning mb-3"></i>
                    <h5 class="fw-bold">3. Resell If Needed</h5>
                    <p>Can't attend? Easily list your ticket for resell and let another fan take your place.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ TESTIMONIALS SECTION -->
<section class="py-5 bg-light border-top">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">What Users Say</h2>
        <div class="row text-center">
            <!-- Testimonial 1 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <p class="card-text">"Booking tickets through EventiX was so easy! I found my event and had my ticket within minutes."</p>
                        <h6 class="mt-3 mb-0 fw-bold">Amira N.</h6>
                        <small class="text-muted">Concert Goer</small>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <p class="card-text">"I used the resell feature and it was incredibly smooth. Got my refund and helped another user too!"</p>
                        <h6 class="mt-3 mb-0 fw-bold">Zaki H.</h6>
                        <small class="text-muted">Student</small>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <p class="card-text">"As an organizer, EventiX made it easy to manage my events, ticket sales, and seat layout."</p>
                        <h6 class="mt-3 mb-0 fw-bold">Emily T.</h6>
                        <small class="text-muted">Event Organizer</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ✅ FAQ SECTION -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Frequently Asked Questions</h2>

        <div class="accordion" id="faqAccordion">
            <!-- FAQ 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne">
                        How do I book an event ticket?
                    </button>
                </h2>
                <div id="faqCollapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Browse events, select your desired one, choose a seat, and proceed with payment. The ticket will be sent to your email.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo">
                        Can I resell my ticket?
                    </button>
                </h2>
                <div id="faqCollapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes. Go to "My Bookings", select the ticket, and click "Resell". Another user can then purchase it.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree">
                        How do I become an event organizer?
                    </button>
                </h2>
                <div id="faqCollapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Register an account and contact admin to verify you as an organizer. After approval, you'll have access to the organizer dashboard.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ UPCOMING EVENTS -->
<style>
.concert-card {
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px 0 #a259f733;
    color: #222;
    transition: transform 0.18s, box-shadow 0.18s;
    border: none;
    overflow: hidden;
    position: relative;
    min-height: 320px;
}
.concert-card:hover {
    transform: translateY(-8px) scale(1.025);
    box-shadow: 0 12px 40px 0 #a259f799;
}
.concert-card-img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
    background: #a259f7;
}
.concert-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
    color: #fff;
    font-size: 0.95rem;
    font-weight: 700;
    padding: 0.3rem 1rem;
    border-radius: 1rem;
    box-shadow: 0 2px 8px #a259f755;
    z-index: 2;
}

/* Capacity Badge */
.capacity-badge {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    z-index: 2;
}
.capacity-badge .badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.6rem;
    border-radius: 1rem;
}

/* Hot Event Badge */
.hot-event-badge {
    position: absolute;
    top: 0.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}
.hot-event-badge .badge {
    font-size: 0.8rem;
    padding: 0.5rem 0.8rem;
    border-radius: 1rem;
    font-weight: 700;
    animation: hotEventPulse 2s infinite;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4);
}
@keyframes hotEventPulse {
    0% { transform: translateX(-50%) scale(1); }
    50% { transform: translateX(-50%) scale(1.05); }
    100% { transform: translateX(-50%) scale(1); }
}

/* Favorite Button */
.favorite-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    z-index: 2;
}
.favorite-toggle {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.favorite-toggle:hover {
    background: #fff;
    transform: scale(1.1);
}

/* Rating Display */
.rating-display {
    display: flex;
    align-items: center;
}
.stars {
    display: flex;
    align-items: center;
}
.stars .fa-star {
    font-size: 0.9rem;
    margin-right: 0.1rem;
}
.rating-text {
    font-size: 0.8rem;
    color: #666;
}

/* Concert Actions */
.concert-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}
.concert-actions .btn {
    flex: 1;
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
}

.concert-card-body {
    padding: 1.2rem 1.2rem 1.5rem 1.2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.concert-title {
    font-size: 1.35rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #222;
}
.concert-desc {
    font-size: 1.01rem;
    color: #444;
    margin-bottom: 0.7rem;
}
.concert-info {
    font-size: 1.01rem;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.concert-info i {
    color: #a259f7;
    margin-right: 0.3rem;
}
.concert-book-btn {
    background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    font-size: 1.08rem;
    padding: 0.7rem 2.2rem;
    box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
    letter-spacing: 1px;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-top: auto;
    text-decoration: none;
}
.concert-book-btn:hover {
    background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
    color: #fff;
    box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
    transform: scale(1.04);
}
@media (max-width: 768px) {
    .concert-card-img { height: 80px; }
    .concert-title { font-size: 1.1rem; }
    .concert-card-body { padding: 0.8rem 0.7rem 1rem 0.7rem; }
    .concert-actions { flex-direction: column; }
}
</style>
<div class="container py-5">
    @if(request('search'))
        <h5>Showing {{ $events->count() }} result{{ $events->count() == 1 ? '' : 's' }} for "<strong>{{ request('search') }}</strong>"</h5>
    @else
        <h5>Total Upcoming Events: {{ $events->count() }}</h5>
    @endif

    <div class="row mt-3">
        @forelse ($events as $event)
            <div class="col-md-4 mb-4">
                <div class="concert-card shadow-sm">
                    <!-- Event image -->
                    @if ($event->banner_image)
                        <img src="{{ asset('storage/' . $event->banner_image) }}" class="concert-card-img" alt="Event Banner">
                    @else
                        <img src="{{ asset('images/concert1.jpg') }}" class="concert-card-img" alt="Concert Image">
                    @endif
                    
                    <!-- Capacity Status Badge -->
                    <div class="capacity-badge">
                        @if($event->isSoldOut())
                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Sold Out</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $event->getAvailableSeatsCount() }} Seats Left</span>
                        @endif
                    </div>

                    <!-- Hot Event Badge -->
                    @if($event->isHotEvent())
                        <div class="hot-event-badge">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-fire"></i> HOT EVENT
                            </span>
                        </div>
                    @endif

                    <!-- Favorite Button -->
                    @if(auth()->check())
                    <div class="favorite-btn">
                        <button class="btn btn-sm favorite-toggle" data-event-id="{{ $event->id }}" data-is-favorited="{{ auth()->user()->hasFavoritedEvent($event->id) ? 'true' : 'false' }}">
                            <i class="fas fa-heart {{ auth()->user()->hasFavoritedEvent($event->id) ? 'text-danger' : 'text-muted' }}"></i>
                        </button>
                    </div>
                    @endif

                    <div class="concert-card-body">
                        <div class="concert-title d-flex align-items-center gap-2">
                            {{ $event->event_name }}
                            @if(\Carbon\Carbon::parse($event->event_date)->isToday())
                                <span class="badge bg-danger ms-1"><i class="fas fa-bolt"></i> Tonight</span>
                            @elseif(\Carbon\Carbon::parse($event->event_date)->isTomorrow())
                                <span class="badge bg-warning text-dark ms-1"><i class="fas fa-sun"></i> Tomorrow</span>
                            @endif
                        </div>
                        
                        <!-- Rating Display -->
                        <div class="rating-display mb-2">
                            @php $avgRating = $event->getAverageRating(); @endphp
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="rating-text ms-1">({{ $event->getReviewsCount() }} reviews)</span>
                            </div>
                        </div>

                        @if (!empty($event->description))
                            <div class="concert-desc">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</div>
                        @endif
                        <div class="concert-info"><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> {{ $event->event_date }}</div>
                        <div class="concert-info"><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $event->location }}</div>
                        
                        <div class="concert-actions mt-3">
                            <button class="btn btn-outline-primary btn-sm quick-view-btn me-2" data-event-id="{{ $event->id }}">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                            @if(auth()->check())
                                <a href="{{ route('book.ticket', $event->id) }}" class="concert-book-btn">
                                    <i class="fas fa-ticket-alt"></i> Buy Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="concert-book-btn" title="Login to book this event">
                                    <i class="fas fa-ticket-alt"></i> Login to Book
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No upcoming events at the moment.</div>
            </div>
        @endforelse
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('events.all') }}" class="btn btn-primary btn-lg">See More Events</a>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="quickViewModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal Styles (copied from events/partials/quick-view.blade.php) -->
<style>
.quick-view-content {
    padding: 1rem;
}
.event-details p {
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}
.event-description {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
}
.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.reviews-section {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
}
.review-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}
.stars .fa-star {
    font-size: 0.9rem;
    margin-right: 0.1rem;
}
.rating-text {
    font-size: 0.9rem;
    color: #666;
}
</style>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="rating-radio">
                                <label for="star{{ $i }}" class="rating-star"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reviewText" class="form-label">Review (Optional)</label>
                        <textarea class="form-control" id="reviewText" name="review" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitReview">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<!-- Rating Input Styles -->
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.2rem;
}
.rating-radio {
    display: none;
}
.rating-star {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    transition: color 0.2s;
}
.rating-star:hover,
.rating-star:hover ~ .rating-star,
.rating-radio:checked ~ .rating-star {
    color: #ffc107;
}
</style>

<!-- JavaScript for Interactive Features -->
@push('scripts')
<script>
$(document).ready(function() {
    // Favorite Toggle Functionality
    $('.favorite-toggle').on('click', function() {
        const button = $(this);
        const eventId = button.data('event-id');
        const isFavorited = button.data('is-favorited') === 'true';
        
        console.log('Favorite button clicked for event:', eventId, 'Currently favorited:', isFavorited);
        
        $.ajax({
            url: '{{ route("events.favorites.toggle", ["event" => "__EVENT_ID__"]) }}'.replace('__EVENT_ID__', eventId),
            method: 'POST',
            data: {
                event_id: eventId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Favorite toggle response:', response);
                
                if (response.success) {
                    const icon = button.find('i');
                    if (response.is_favorited) {
                        icon.removeClass('text-muted').addClass('text-danger');
                        button.data('is-favorited', 'true');
                        // Show success message
                        showAlert('Event added to favorites!', 'success');
                    } else {
                        icon.removeClass('text-danger').addClass('text-muted');
                        button.data('is-favorited', 'false');
                        // Show success message
                        showAlert('Event removed from favorites!', 'info');
                    }
                } else {
                    showAlert('Error updating favorite status', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Favorite toggle error:', error);
                showAlert('Error updating favorite status', 'error');
            }
        });
    });

    // Quick View Functionality
    $('.quick-view-btn').on('click', function() {
        const eventId = $(this).data('event-id');
        
        $.ajax({
            url: `/events/${eventId}/quick-view`,
            method: 'GET',
            success: function(response) {
                $('#quickViewModalBody').html(response.html);
                $('#quickViewModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Quick view error:', error);
                showAlert('Error loading event details', 'error');
            }
        });
    });

    // Review Submission
    $('#submitReview').on('click', function() {
        const rating = $('input[name="rating"]:checked').val();
        const review = $('#reviewText').val();
        const eventId = $('#reviewModal').data('event-id');
        
        if (!rating) {
            showAlert('Please select a rating', 'warning');
            return;
        }
        
        // Determine if editing or creating
        const isEdit = $('#reviewForm').data('edit') === true;
        const ajaxOptions = {
            url: `/events/${eventId}/reviews`,
            data: {
                rating: rating,
                review: review,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#reviewModal').modal('hide');
                    showAlert('Review submitted successfully!', 'success');
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    showAlert(response.message || 'Error submitting review', 'error');
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error submitting review';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMsg = xhr.responseText;
                }
                console.error('Review submission error:', errorMsg, xhr);
                showAlert(errorMsg, 'error');
            }
        };
        if (isEdit) {
            ajaxOptions.type = 'PUT';
        } else {
            ajaxOptions.type = 'POST';
        }
        $.ajax(ajaxOptions);
    });

    // Helper function to show alerts
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 3000);
    }
});
</script>
@endpush

<!-- ✅ CONTACT US SECTION -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Need Help?</h2>
        <p class="mb-4">If you have questions or need support, reach out to us anytime.</p>
        <a href="mailto:eventix.helpdesk@gmail.com" class="btn btn-outline-light btn-lg">
            <i class="bi bi-envelope"></i> Contact Us
        </a>
    </div>
</section>

@endsection

        