<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoneImage extends Model
{
    protected $table = 'zone_images';

    protected $fillable = [
        'zone_id',
        'url'
    ];
}
