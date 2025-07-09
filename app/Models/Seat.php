<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public function event()
{
    return $this->belongsTo(Event::class);
}

protected $fillable = [
    'event_id',
    'label',
    'type',
    'price',
    'row',
    'column',
    'is_booked',
];

public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function transactions()
{
    return $this->hasMany(Transaction::class);
}

public function isBooked()
{
    return $this->transactions()->exists();
}
}
