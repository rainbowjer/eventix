<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentlyViewedEvent extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
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
