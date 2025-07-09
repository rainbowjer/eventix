<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventReview extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'review'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
