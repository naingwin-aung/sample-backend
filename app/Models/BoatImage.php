<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoatImage extends Model
{
    protected $table = 'boat_images';

    protected $fillable = [
        'boat_id',
        'url'
    ];
}
