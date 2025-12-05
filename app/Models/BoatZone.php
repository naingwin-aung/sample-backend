<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoatZone extends Model
{
    protected $table = 'boat_zones';

    protected $fillable = [
        'boat_id',
        'name',
        'capacity',
    ];
}
