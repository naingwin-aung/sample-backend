<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shopping_carts';

    protected $fillable = [
        'guid',
        'cart_data',
        'status',
    ];

    protected $casts = [
        'cart_data' => 'json',
    ];
}
