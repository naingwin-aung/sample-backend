<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'url',
    ];

    protected $casts = [
        'url' => Image::class,
    ];
}
