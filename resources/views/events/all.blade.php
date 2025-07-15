@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">All Events</h2>
    
    <!-- Filter Bar (inline, one line on desktop) -->
    <form method="GET" class="filter-bar-modern-inline mb-4 d-flex flex-wrap gap-2">
        <div class="filter-inline-field search-grow position-relative">
            <label for="search" class="form-label mb-1 visually-hidden">Search</label>
            <div class="search-bar-wrapper">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text" name="search" id="search" class="form-control search-bar-input" placeholder="Search concerts, venues..." value="{{ request('search') }}" autofocus>
                @if(request('search'))
                    <a href="?{{ http_build_query(array_merge(request()->except('search'), ['search' => ''])) }}" class="clear-btn" title="Clear search">&times;</a>
                @endif
            </div>
        </div>
        <div class="filter-inline-field">
            <label for="date" class="form-label mb-1">Date</label>
            <select name="date" id="date" class="form-select filter-select">
                <option value="">All Dates</option>
                <option value="today" {{ request('date')=='today' ? 'selected' : '' }}>Today</option>
                <option value="this-week" {{ request('date')=='this-week' ? 'selected' : '' }}>This Week</option>
                <option value="this-month" {{ request('date')=='this-month' ? 'selected' : '' }}>This Month</option>
            </select>
        </div>
        <div class="filter-inline-field">
            <label for="location" class="form-label mb-1">Location</label>
            <select name="location" id="location" class="form-select filter-select">
                <option value="">All Locations</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc }}" {{ request('location')==$loc ? 'selected' : '' }}>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-inline-field">
            <label for="capacity" class="form-label mb-1">Availability</label>
            <select name="capacity" id="capacity" class="form-select filter-select">
                <option value="">All Events</option>
                <option value="available" {{ request('capacity')=='available' ? 'selected' : '' }}>Available Seats</option>
                <option value="sold-out" {{ request('capacity')=='sold-out' ? 'selected' : '' }}>Sold Out</option>
                <option value="hot" {{ request('capacity')=='hot' ? 'selected' : '' }}>🔥 Hot Events</option>
            </select>
        </div>
        <div class="filter-inline-field">
            <label for="sort" class="form-label mb-1">Sort</label>
            <select name="sort" id="sort" class="form-select filter-select">
                <option value="soonest" {{ request('sort')=='soonest' ? 'selected' : '' }}>Soonest</option>
                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Latest</option>
            </select>
        </div>
        <div class="filter-inline-field">
            <button type="submit" class="btn filter-btn fw-bold"><i class="fas fa-filter"></i></button>
        </div>
    </form>

    <!-- Recently Viewed Events Section -->
    @if(auth()->check() && $recentlyViewed->count() > 0)
    <div class="recently-viewed-section mb-4">
        <h5 class="text-muted mb-3"><i class="fas fa-history"></i> Recently Viewed</h5>
        <div class="row">
            @foreach($recentlyViewed->take(3) as $recentEvent)
            <div class="col-md-3 mb-3">
                <div class="recent-event-card">
                    @if ($recentEvent->banner_image)
                        <img src="{{ asset('storage/' . $recentEvent->banner_image) }}" class="recent-event-img" alt="Event Banner">
                    @else
                        <img src="{{ asset('images/concert1.jpg') }}" class="recent-event-img" alt="Concert Image">
                    @endif
                    <div class="recent-event-body">
                        <h6 class="recent-event-title">{{ $recentEvent->event_name }}</h6>
                        <small class="text-muted">{{ $recentEvent->event_date }}</small>
                        <button class="btn btn-sm btn-outline-primary mt-2 quick-view-btn" data-event-id="{{ $recentEvent->id }}">
                            <i class="fas fa-eye"></i> Quick View
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row">
        @forelse ($events as $event)
            <div class="col-md-4 col-12 mb-4">
                <div class="concert-card shadow-sm h-100 d-flex flex-column">
                    <!-- Event image -->
                    @if ($event->banner_image)
                        <img src="{{ asset('storage/' . $event->banner_image) }}" class="concert-card-img img-fluid w-100" alt="Event Banner">
                    @else
                        <img src="{{ asset('images/concert1.jpg') }}" class="concert-card-img img-fluid w-100" alt="Concert Image">
                    @endif
                    
                    <!-- Badges: Stack vertically -->
                    <!-- Seats Left Badge: Top-left -->
                    <div class="position-absolute top-0 start-0 p-2" style="z-index:2;">
                        <div class="capacity-badge">
                            @if($event->isSoldOut())
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Sold Out</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $event->getAvailableSeatsCount() }} Seats Left</span>
                            @endif
                        </div>
                    </div>
                    <!-- Hot Event Badge: Top-right -->
                    @if($event->isHotEvent())
                    <div class="position-absolute top-0 end-0 p-2 d-flex flex-column align-items-end" style="z-index:2;">
                        <div class="hot-event-badge mb-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-fire"></i> HOT EVENT
                            </span>
                        </div>
                        @if(auth()->check())
                        <div class="favorite-btn">
                            <button class="btn btn-sm favorite-toggle" data-event-id="{{ $event->id }}" data-is-favorited="{{ auth()->user()->hasFavoritedEvent($event->id) ? 'true' : 'false' }}">
                                <i class="fas fa-heart {{ auth()->user()->hasFavoritedEvent($event->id) ? 'text-danger' : 'text-muted' }}"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    @else
                        @if(auth()->check())
                        <div class="position-absolute top-0 end-0 p-2 favorite-btn" style="z-index:2;">
                            <button class="btn btn-sm favorite-toggle" data-event-id="{{ $event->id }}" data-is-favorited="{{ auth()->user()->hasFavoritedEvent($event->id) ? 'true' : 'false' }}">
                                <i class="fas fa-heart {{ auth()->user()->hasFavoritedEvent($event->id) ? 'text-danger' : 'text-muted' }}"></i>
                            </button>
                        </div>
                        @endif
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
                        
                        <div class="concert-actions mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary btn-sm quick-view-btn me-2 w-100 w-md-auto" data-event-id="{{ $event->id }}">
                                <i class="fas fa-eye"></i> Quick View
                            </button>
                            @if(auth()->check())
                                <a href="{{ route('book.ticket', $event->id) }}" class="concert-book-btn w-100 w-md-auto">
                                    <i class="fas fa-ticket-alt"></i> Buy Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="concert-book-btn w-100 w-md-auto" title="Login to book this event">
                                    <i class="fas fa-ticket-alt"></i> Login to Book
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No events found.</div>
            </div>
        @endforelse
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

<!-- Toast Container -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
  <div id="mainToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="mainToastBody">
        <!-- Toast message here -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Concert card styles (reuse from welcome page) -->
<style>
.filter-bar-modern {
    background: linear-gradient(90deg, #f8f8ff 0%, #f3eaff 100%);
    border-radius: 2.2rem;
    box-shadow: 0 8px 32px 0 #a259f722, 0 1.5px 0 #ff6a8822;
    padding: 2rem 2.2rem 1.2rem 2.2rem;
    margin-bottom: 2.2rem;
    border: 1.5px solid #f3eaff;
    transition: box-shadow 0.2s, border 0.2s;
    max-width: 440px;
    margin: 0 auto;
}
.filter-bar-modern:focus-within {
    box-shadow: 0 12px 40px 0 #a259f744, 0 2px 0 #ff6a8844;
    border: 1.5px solid #a259f7;
}
.search-bar-wrapper {
    position: relative;
    width: 100%;
}
.search-bar-input {
    padding-left: 2.2rem;
    border-radius: 2rem;
    border: 1.5px solid #a259f733;
    box-shadow: 0 2px 8px 0 #a259f711;
    transition: border 0.2s, box-shadow 0.2s;
    height: 2.6rem;
    font-size: 1.08rem;
    background: #fff;
}
.search-bar-input:focus {
    border: 1.5px solid #a259f7;
    box-shadow: 0 2px 12px 0 #a259f733;
    outline: none;
    background: #f8f8ff;
}
.search-icon {
    position: absolute;
    left: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a259f7;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 2;
}
.clear-btn {
    position: absolute;
    right: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
    font-size: 1.2rem;
    text-decoration: none;
    background: #f3f3f3;
    border-radius: 50%;
    width: 1.7rem;
    height: 1.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s, color 0.18s;
    opacity: 0.85;
}
.clear-btn:hover {
    background: #a259f7;
    color: #fff;
    opacity: 1;
}
.filter-select {
    border-radius: 1.5rem;
    border: 1.5px solid #a259f733;
    background: #fff;
    font-size: 1.05rem;
    height: 2.6rem;
    box-shadow: 0 2px 8px 0 #a259f711;
    transition: border 0.2s, box-shadow 0.2s;
}
.filter-select:focus {
    border: 1.5px solid #a259f7;
    box-shadow: 0 2px 12px 0 #a259f733;
    outline: none;
    background: #f8f8ff;
}
.filter-btn {
    background: linear-gradient(90deg, #4f2cc6 0%, #a259f7 100%);
    color: #fff;
    border: none;
    border-radius: 2rem;
    font-weight: 700;
    font-size: 1.08rem;
    padding: 0.7rem 1rem;
    box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
    letter-spacing: 1px;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-top: auto;
}
.filter-btn:hover, .filter-btn:focus {
    background: linear-gradient(90deg, #a259f7 0%, #4f2cc6 100%);
    color: #fff;
    box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
    transform: scale(1.04);
    outline: none;
}

/* Recently Viewed Section */
.recently-viewed-section {
    background: linear-gradient(135deg, #f8f8ff 0%, #f3eaff 100%);
    border-radius: 1.5rem;
    padding: 1.5rem;
    border: 1.5px solid #e0e7ff;
}
.recent-event-card {
    background: #fff;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 16px 0 #a259f722;
    transition: transform 0.2s;
}
.recent-event-card:hover {
    transform: translateY(-4px);
}
.recent-event-img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}
.recent-event-body {
    padding: 0.8rem;
    text-align: center;
}
.recent-event-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: #222;
}

/* Concert Card Enhancements */
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
.concert-card-body {
    padding: 1.2rem 1.2rem 1.5rem 1.2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
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

/* Rating Input Styles */
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

.filter-bar-modern-inline {
    background: linear-gradient(90deg, #f8f8ff 0%, #f3eaff 100%);
    border-radius: 2.2rem;
    box-shadow: 0 8px 32px 0 #a259f722, 0 1.5px 0 #ff6a8822;
    padding: 1.2rem 1.5rem;
    margin-bottom: 2.2rem;
    border: 1.5px solid #f3eaff;
    transition: box-shadow 0.2s, border 0.2s;
    max-width: 100%;
    overflow-x: auto;
    flex-wrap: wrap;
    gap: 1rem;
    display: flex;
    align-items: flex-end;
}
.filter-bar-modern-inline:focus-within {
    box-shadow: 0 12px 40px 0 #a259f744, 0 2px 0 #ff6a8844;
    border: 1.5px solid #a259f7;
}
.filter-inline-field {
    min-width: 110px;
    max-width: 150px;
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    flex: 1 1 120px;
    flex-shrink: 1;
}
.filter-inline-field label {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: #444;
    margin-left: 0.2rem;
}
.filter-inline-field.search-grow {
    flex-grow: 1;
    min-width: 220px;
    max-width: 100%;
    flex-direction: row;
    align-items: flex-end;
}
.filter-inline-field.search-grow label {
    display: none;
}
.filter-inline-field.search-grow .form-control {
    min-width: 220px;
    max-width: 100%;
    font-size: 1.12rem;
    height: 48px;
}
.filter-inline-field .form-select,
.filter-inline-field .form-control {
    min-width: 100px;
    max-width: 150px;
    width: 100%;
    border-radius: 2rem;
    height: 40px;
    padding-top: 0.375rem;
    padding-bottom: 0.375rem;
    font-size: 1.02rem;
}
.filter-inline-field .btn.filter-btn {
    min-width: 40px;
    max-width: 40px;
    width: 40px;
    height: 40px;
    border-radius: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 1.1rem;
    margin: 0;
    box-shadow: none;
    vertical-align: middle;
}
@media (max-width: 991.98px) {
    .filter-bar-modern-inline {
        flex-direction: column;
        align-items: stretch;
        padding: 1rem 0.5rem;
    }
    .filter-inline-field,
    .filter-inline-field.search-grow {
        max-width: 100%;
        width: 100%;
        margin-bottom: 0.5rem;
        flex: 1 1 100%;
        flex-direction: column;
        align-items: stretch;
    }
    .filter-inline-field label {
        margin-left: 0;
    }
    .filter-inline-field .form-select,
    .filter-inline-field .form-control {
        max-width: 100%;
        min-width: 0;
        height: 44px;
    }
    .filter-inline-field .btn.filter-btn {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        height: 44px;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Test if jQuery is loaded
    console.log('jQuery version:', $.fn.jquery);
    console.log('Document ready!');
    
    // Quick View functionality
    $('.quick-view-btn').click(function() {
        const eventId = $(this).data('event-id');
        loadQuickView(eventId);
    });

    function loadQuickView(eventId) {
        $.get(`/events/${eventId}/quick-view`)
            .done(function(response) {
                $('#quickViewModalBody').html(response.html);
                $('#quickViewModal').modal('show');
            })
            .fail(function() {
                showToast('Error loading event details', 'danger');
            });
    }

    // Favorite toggle functionality
    $('.favorite-toggle').click(function(e) {
        e.preventDefault();
        const btn = $(this);
        const eventId = btn.data('event-id');
        const isFavorited = btn.data('is-favorited') === 'true';

        console.log('Toggling favorite for event:', eventId, 'Current state:', isFavorited);

        $.post(`/events/${eventId}/favorites/toggle`)
            .done(function(response) {
                console.log('Favorite toggle response:', response);
                if (response.success) {
                    btn.data('is-favorited', response.isFavorited);
                    const icon = btn.find('i');
                    if (response.isFavorited) {
                        icon.removeClass('text-muted').addClass('text-danger');
                    } else {
                        icon.removeClass('text-danger').addClass('text-muted');
                    }
                } else {
                    showToast(response.message || 'Error updating favorite status', 'danger');
                }
            })
            .fail(function(xhr, status, error) {
                console.error('Favorite toggle error:', xhr, status, error);
                showToast('Error updating favorite status. Please try again.', 'danger');
            });
    });

    // Review functionality
    // Set edit mode when editing a review
    function openReviewModal(eventId) {
        $('#reviewModal').data('event-id', eventId);
        $('#reviewForm').data('edit', false); // Not edit mode
        $('input[name="rating"]').prop('checked', false);
        $('#reviewText').val('');
        $('#reviewModal').modal('show');
    }

    function editReview(eventId) {
        $.get(`/events/${eventId}/reviews`)
            .done(function(response) {
                if (response.review) {
                    $(`input[name="rating"][value="${response.review.rating}"]`).prop('checked', true);
                    $('#reviewText').val(response.review.review);
                    $('#reviewModal').data('event-id', eventId);
                    $('#reviewForm').data('edit', true); // Set edit mode
                    $('#reviewModal').modal('show');
                }
            });
    }

    // Reset edit mode when modal is closed
    $('#reviewModal').on('hidden.bs.modal', function () {
        $('#reviewForm').data('edit', false);
    });

    $('#submitReview').on('click', function() {
        const rating = $('input[name="rating"]:checked').val();
        const review = $('#reviewText').val();
        const eventId = $('#reviewModal').data('event-id');
        if (!rating) {
            showToast('Please select a rating', 'warning');
            return;
        }
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
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    showToast(response.message || 'Error submitting review', 'danger');
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error submitting review';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMsg = xhr.responseText;
                }
                showToast(errorMsg, 'danger');
            }
        };
        if (isEdit) {
            ajaxOptions.type = 'PUT';
        } else {
            ajaxOptions.type = 'POST';
        }
        $.ajax(ajaxOptions);
    });
});

function showToast(message, type = 'primary') {
  const toastEl = document.getElementById('mainToast');
  const toastBody = document.getElementById('mainToastBody');
  toastBody.textContent = message;
  toastEl.className = 'toast align-items-center text-bg-' + type + ' border-0';
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}
</script>
@endpush
@endsection 