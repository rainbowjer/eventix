<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\EventReview;
use App\Models\UserFavorite;
use App\Models\RecentlyViewedEvent;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
         'role', 
         'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // Only hash if not already hashed (for Laravel 10+ hashed cast compatibility)
            if (\Illuminate\Support\Facades\Hash::needsRehash($value)) {
                $this->attributes['password'] = \Illuminate\Support\Facades\Hash::make($value);
            } else {
                $this->attributes['password'] = $value;
            }
        }
    }

    public function transactions()
{
    return $this->hasMany(\App\Models\Transaction::class);
}

    public function reviews()
    {
        return $this->hasMany(EventReview::class);
    }

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function recentlyViewedEvents()
    {
        return $this->hasMany(RecentlyViewedEvent::class);
    }

    public function favoriteEvents()
    {
        return $this->belongsToMany(Event::class, 'user_favorites');
    }

    public function hasFavoritedEvent($eventId)
    {
        return $this->favorites()->where('event_id', $eventId)->exists();
    }

    public function hasReviewedEvent($eventId)
    {
        return $this->reviews()->where('event_id', $eventId)->exists();
    }

    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'organizer_id');
    }
}
