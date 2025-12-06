<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAdditionalOption extends Model
{
    protected $table = 'product_additional_options';

    protected $fillable = [
        'product_id',
        'option_id',
        'additional_option_id',
        'selling_price',
        'net_price',
    ];

    protected $casts = [
        'selling_price' => 'float',
        'net_price' => 'float',
    ];
}
