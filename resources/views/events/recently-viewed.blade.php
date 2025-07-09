@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-history text-primary"></i> Recently Viewed Events</h2>
        <a href="{{ route('events.all') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to All Events
        </a>
    </div>

    @if($recentlyViewed->count() > 0)
        <div class="row">
            @foreach($recentlyViewed as $event)
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

                        <!-- Favorite Button -->
                        <div class="favorite-btn">
                            <button class="btn btn-sm favorite-toggle" data-event-id="{{ $event->id }}" data-is-favorited="{{ auth()->user()->hasFavoritedEvent($event->id) ? 'true' : 'false' }}">
                                <i class="fas fa-heart {{ auth()->user()->hasFavoritedEvent($event->id) ? 'text-danger' : 'text-muted' }}"></i>
                            </button>
                        </div>

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
                                @if(!$event->isSoldOut())
                                    <a href="{{ route('events.show', $event->id) }}" class="concert-book-btn">
                                        <i class="fas fa-ticket-alt"></i> Buy Now
                                    </a>
                                @else
                                    <button class="concert-book-btn" disabled>
                                        <i class="fas fa-times"></i> Sold Out
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-history text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">No recently viewed events</h4>
            <p class="text-muted">Start exploring events to see them here!</p>
            <a href="{{ route('events.all') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Browse Events
            </a>
        </div>
    @endif
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

<style>
/* Reuse styles from events.all.blade.php */
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
.concert-book-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 768px) {
    .concert-card-img { height: 80px; }
    .concert-title { font-size: 1.1rem; }
    .concert-card-body { padding: 0.8rem 0.7rem 1rem 0.7rem; }
    .concert-actions { flex-direction: column; }
}
</style>

@push('scripts')
<script>
function showToast(message, type = 'primary') {
  const toastEl = document.getElementById('mainToast');
  const toastBody = document.getElementById('mainToastBody');
  toastBody.textContent = message;
  toastEl.className = 'toast align-items-center text-bg-' + type + ' border-0';
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}
$(document).ready(function() {
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
    $('.favorite-toggle').click(function() {
        const btn = $(this);
        const eventId = btn.data('event-id');
        const isFavorited = btn.data('is-favorited') === 'true';

        $.post(`/events/${eventId}/favorites/toggle`)
            .done(function(response) {
                if (response.success) {
                    btn.data('is-favorited', response.isFavorited);
                    const icon = btn.find('i');
                    if (response.isFavorited) {
                        icon.removeClass('text-muted').addClass('text-danger');
                    } else {
                        icon.removeClass('text-danger').addClass('text-muted');
                    }
                }
            })
            .fail(function() {
                showToast('Error updating favorite status', 'danger');
            });
    });
});
</script>
@endpush
@endsection 