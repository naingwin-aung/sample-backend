<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;

class ZoneImage extends Model
{
    protected $table = 'zone_images';

    protected $fillable = [
        'zone_id',
        'url'
    ];

    protected $casts = [
        'url' => Image::class,
    ];
}
