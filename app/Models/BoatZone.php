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

    public function images()
    {
        return $this->hasMany(ZoneImage::class, 'zone_id', 'id');
    }
}
