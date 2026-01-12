<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'booking_number',
        'payment_reference',
        'user_id',
        'sub_total',
        'grand_total',
        'payment_status',
        'remark',
        'request_payload',
    ];

    protected $casts = [
        'request_payload' => 'json',
        'sub_total'       => 'float',
        'grand_total'     => 'float',
    ];

    public function items()
    {
        return $this->hasMany(BookingItem::class, 'booking_id');
    }
}
