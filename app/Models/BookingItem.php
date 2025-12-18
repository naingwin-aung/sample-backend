<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $table = 'booking_items';
    
    protected $fillable = [
        'booking_id',
        'product_type',
        'productable_id',
        'productable_type',
        'sub_total',
        'grand_total',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'sub_total'   => 'float',
        'grand_total' => 'float',
    ];
}
