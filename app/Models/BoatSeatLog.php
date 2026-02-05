<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BoatSeatLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'boat_seat_logs';

    protected $fillable = [
        'booking_number',
        'product_id',
        'option_id',
        'boat_id',
        'zone_id',
        'ticket_id',
        'schedule_time_id',
        'date',
        'allocation_seats',
        'current_seats',
        'booked_seats',
        'available_seats',
        'product',
        'option',
        'boat',
        'zone',
        'ticket',
        'schedule_time',
        'variations',
        'additional_options',
        'logged_at',
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
