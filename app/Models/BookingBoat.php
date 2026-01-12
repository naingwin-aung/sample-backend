<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingBoat extends Model
{
    protected $table = 'booking_boats';
    
    protected $fillable = [
        'booking_id',
        'booking_item_id',
        'product_id',
        'option_id',
        'boat_id',
        'zone_id',
        'ticket_id',
        'schedule_time_id',
        'name',
        'ticket_name',
        'date',
        'total_quantity',
        'sub_total',
        'grand_total',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'sub_total'      => 'float',
        'grand_total'    => 'float',
    ];

    public function boatItemDetail()
    {
        return $this->hasOne(BookingBoatDetail::class, 'booking_boat_id');
    }
}
