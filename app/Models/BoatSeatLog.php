<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BoatSeatLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'boat_seat_logs';

    protected $fillable = [
        'product_id',
        'option_id',
        'boat_id',
        'zone_id',
        'ticket_id',
        'schedule_time_id',
        'date',
        'allocation_seats',
        'booked_seats',
        'available_seats',
        'logged_at',
    ];
}
