<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function seat()
{
    return $this->belongsTo(\App\Models\Seat::class);
}

public function ticket()
{
    return $this->belongsTo(\App\Models\Ticket::class);
}
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}


protected $fillable = ['user_id',
'seat_id',
'ticket_id',
'amount',
'payment_status', // enum: 'paid', 'pending', 'cancelled'
'payment_method', // enum: 'credit_card', 'fpx', 'tng', 'grab', 'shopee', 'boost'
'purchase_date',
'resold_at'];


}
