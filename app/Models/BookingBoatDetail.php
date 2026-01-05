<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingBoatDetail extends Model
{
    protected $table = 'booking_boat_details';

    protected $fillable = [
        'booking_boat_id',
        'product',
        'option',
        'boat',
        'zone',
        'ticket',
        'schedule_time',
        'variations',
        'additional_options',
    ];

    protected $casts = [
        'product'            => 'json',
        'boat'               => 'json',
        'option'             => 'json',
        'zone'               => 'json',
        'ticket'             => 'json',
        'schedule_time'      => 'json',
        'variations'         => 'json',
        'additional_options' => 'json',
    ];
}