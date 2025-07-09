<div class="quick-view-content">
    <div class="row">
        <div class="col-md-6">
            @if ($event->banner_image)
                <img src="{{ asset('storage/' . $event->banner_image) }}" class="img-fluid rounded" alt="Event Banner">
            @else
                <img src="{{ asset('images/concert1.jpg') }}" class="img-fluid rounded" alt="Concert Image">
            @endif

            <!-- Seat Availability Section (from show.blade.php) -->
            <div class="mb-3 mt-4">
                <h5>🎟 Available Seats</h5>
                <ul class="list-unstyled">
                    @php
                        $types = ['VIP' => 150, 'GENERAL' => 80, 'ECONOMY' => 50];
                    @endphp
                    @foreach ($types as $type => $price)
                        @php
                            $count = $event->seats->where('type', $type)->where('is_booked', false)->count();
                        @endphp
                        @if ($count > 0)
                            <li class="mb-1">
                                <strong>{{ $type }}</strong> — {{ $count }} seats available @ RM{{ $price }}
                            </li>
                        @endif
                    @endforeach
                </ul>
                <small class="text-muted">Total seats: {{ $event->seats->count() }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <h4 class="mb-3">{{ $event->event_name }}</h4>
            
            <!-- Rating Display -->
            <div class="rating-display mb-3">
                @php $avgRating = $event->getAverageRating(); @endphp
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="rating-text ms-2">{{ number_format($avgRating, 1) }} ({{ $event->getReviewsCount() }} reviews)</span>
                </div>
            </div>

            <!-- Event Details -->
            <div class="event-details mb-3">
                <p><i class="fas fa-calendar-alt text-primary"></i> <strong>Date:</strong> {{ $event->event_date }}</p>
                <p><i class="fas fa-clock text-primary"></i> <strong>Time:</strong> {{ $event->event_time }}</p>
                <p><i class="fas fa-map-marker-alt text-primary"></i> <strong>Location:</strong> {{ $event->location }}</p>
                <p><i class="fas fa-user text-primary"></i> <strong>Organizer:</strong> {{ $event->organizer->name }}</p>
                
                <!-- Capacity Status -->
                <p>
                    <i class="fas fa-ticket-alt text-primary"></i> 
                    <strong>Availability:</strong> 
                    @if($event->isSoldOut())
                        <span class="badge bg-danger">Sold Out</span>
                    @else
                        <span class="badge bg-success">{{ $event->getAvailableSeatsCount() }} seats available</span>
                    @endif
                </p>
            </div>

            @if (!empty($event->description))
                <div class="event-description mb-3">
                    <h6>Description:</h6>
                    <p>{{ $event->description }}</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if(auth()->check())
                    <button class="btn btn-outline-danger btn-sm favorite-toggle-quick" data-event-id="{{ $event->id }}" data-is-favorited="{{ $isFavorited ? 'true' : 'false' }}">
                        <i class="fas fa-heart {{ $isFavorited ? 'text-danger' : 'text-muted' }}"></i> 
                        {{ $isFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}
                    </button>
                    
                    @if(!$userReview)
                        <button class="btn btn-outline-primary btn-sm ms-2" onclick="openReviewModal({{ $event->id }})">
                            <i class="fas fa-star"></i> Write Review
                        </button>
                    @else
                        <button class="btn btn-outline-warning btn-sm ms-2" onclick="editReview({{ $event->id }})">
                            <i class="fas fa-edit"></i> Edit Review
                        </button>
                    @endif
                    
                    @if(!$event->isSoldOut())
                        <a href="{{ route('book.ticket', $event->id) }}" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-ticket-alt"></i> Buy Tickets
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Login to Book
                    </a>
                @endif
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section mt-4">
                <h5>Reviews</h5>
                @if($event->reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($event->reviews->take(5) as $review)
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                            @endfor
                                        </div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($review->review)
                                    <p class="mb-0 mt-2">{{ $review->review }}</p>
                                @endif
                            </div>
                        @endforeach
                        @if($event->reviews->count() > 5)
                            <p class="text-muted">Showing 5 of {{ $event->reviews->count() }} reviews</p>
                        @endif
                    </div>
                @else
                    <p class="text-muted">No reviews yet. Be the first to review this event!</p>
                @endif
            </div>
        </div>
    </div>
</div>

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

<script>
function showToast(message, type = 'primary') {
  const toastEl = document.getElementById('mainToast');
  const toastBody = document.getElementById('mainToastBody');
  toastBody.textContent = message;
  toastEl.className = 'toast align-items-center text-bg-' + type + ' border-0';
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}
// Favorite toggle for quick view
$('.favorite-toggle-quick').click(function() {
    const btn = $(this);
    const eventId = btn.data('event-id');
    const isFavorited = btn.data('is-favorited') === 'true';

    $.post(`/events/${eventId}/favorites/toggle`)
        .done(function(response) {
            if (response.success) {
                btn.data('is-favorited', response.isFavorited);
                const icon = btn.find('i');
                const text = btn.text().trim();
                
                if (response.isFavorited) {
                    icon.removeClass('text-muted').addClass('text-danger');
                    btn.html('<i class="fas fa-heart text-danger"></i> Remove from Favorites');
                } else {
                    icon.removeClass('text-danger').addClass('text-muted');
                    btn.html('<i class="fas fa-heart text-muted"></i> Add to Favorites');
                }
            }
        })
        .fail(function() {
            showToast('Error updating favorite status', 'danger');
        });
});

function openReviewModal(eventId) {
    $('#reviewModal').data('event-id', eventId);
    $('#reviewForm').data('edit', false); // Not edit mode
    // Optionally clear previous rating/review
    $('input[name="rating"]').prop('checked', false);
    $('#reviewText').val('');
    $('#reviewModal').modal('show');
}

function editReview(eventId) {
    // Load existing review data and open modal
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
</script> 