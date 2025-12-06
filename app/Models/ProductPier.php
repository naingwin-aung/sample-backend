<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPier extends Model
{
    protected $table = 'product_piers';

    protected $fillable = [
        'product_id',
        'pier_id',
    ];
}
