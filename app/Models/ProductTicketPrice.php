<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTicketPrice extends Model
{
    protected $table = 'product_ticket_prices';

    protected $fillable = [
        'product_ticket_id',
        'option_id',
        'ticket_id',
        'name',
        'selling_price',
        'net_price',
    ];
}
