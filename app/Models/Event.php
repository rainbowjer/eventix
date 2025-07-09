<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Models\EventReview;
use App\Models\UserFavorite;
use App\Models\RecentlyViewedEvent;

class Event extends Model
{
    use Searchable;
    protected $fillable = [
        'event_name',
        'event_date',
        'event_time',
        'location',
        'description',
        'organizer_id',
        'banner_image',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function seats()
{
    return $this->hasMany(Seat::class);
}
public function organizer()
{
    return $this->belongsTo(User::class, 'organizer_id');
}

    public function reviews()
    {
        return $this->hasMany(EventReview::class);
    }

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewedEvent::class);
    }

    // Helper methods for capacity and availability
    public function getAvailableSeatsCount()
    {
        return $this->seats()->where('is_booked', false)->count();
    }

    public function getTotalSeatsCount()
    {
        return $this->seats()->count();
    }

    public function isSoldOut()
    {
        return $this->getAvailableSeatsCount() === 0;
    }

    public function getBookedSeatsCount()
    {
        return $this->seats()->whereHas('transactions', function($query) {
            $query->whereNotNull('seat_id');
        })->count();
    }

    public function getAverageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCount()
    {
        return $this->reviews()->count();
    }

    /**
     * Check if this event is considered "hot" based on various criteria
     */
    public function isHotEvent()
    {
        // Declare hot event if at least 10 seats are booked
        return $this->getBookedSeatsCount() >= 10;
    }

    /**
     * Get hot events count for display
     */
    public function getHotEventsCount()
    {
        return Event::where('event_date', '>=', now())
            ->get()
            ->filter(function($event) {
                return $event->isHotEvent();
            })->count();
    }

    /**
     * Get hot events for display
     */
    public static function getHotEvents($limit = 6)
    {
        return Event::where('event_date', '>=', now())
            ->get()
            ->filter(function($event) {
                return $event->isHotEvent();
            })
            ->take($limit);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'event_name' => $this->event_name,
            'location' => $this->location,
            'description' => $this->description,
        ];
    }
}
