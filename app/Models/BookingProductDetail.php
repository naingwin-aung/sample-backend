<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingProductDetail extends Model
{
    protected $table = 'booking_product_details';

    protected $fillable = [
        'booking_product_id',
        'product',
        'option',
        'zone',
        'ticket',
        'schedule_time',
        'variations',
    ];

    protected $casts = [
        'product'       => 'json',
        'option'        => 'json',
        'zone'          => 'json',
        'ticket'        => 'json',
        'schedule_time' => 'json',
        'variations'    => 'json',
    ];
}