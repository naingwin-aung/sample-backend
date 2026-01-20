<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTicketPrice extends Model
{
    const ADULT = 'Adult';
    const CHILD = 'Child';
    
    protected $table = 'product_ticket_prices';

    protected $fillable = [
        'product_id',
        'option_id',
        'ticket_id',
        'name',
        'selling_price',
        'net_price',
    ];

    protected $casts = [
        'selling_price' => 'float',
        'net_price' => 'float',
    ];
}
