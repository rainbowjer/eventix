<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'seat_id',
        'event_id',
        'user_id',
        'price',
        'type',
        'is_resell',
        'resell_price',
        'status',
        'resell_status',
        'resell_admin_note',
    ];

    protected $casts = [
        'is_resell' => 'boolean',
    ];

    public function transaction()
    {
        // Correctly defines that a ticket can have one transaction using ticket_id foreign key
        return $this->hasOne(Transaction::class, 'ticket_id', 'id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getResellStatusAttribute()
    {
        if (!$this->is_resell) return null;

        // Directly use the 'resell_status' DB field
        return $this->attributes['resell_status'] ?? 'pending';
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = ucfirst(strtolower($value));
        // You can use strtoupper($value) if your app prefers uppercase like "ECONOMY"
    }
}