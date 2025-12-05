<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    protected $table = 'boats';

    protected $fillable = [
        'name',
        'boat_type_id',
        'capacity',
        'seat_type',
    ];

    public function images()
    {
        return $this->hasMany(BoatImage::class, 'boat_id', 'id');
    }

    public function zones()
    {
        return $this->hasMany(BoatZone::class, 'boat_id', 'id');
    }

    public function boatType()
    {
        return $this->belongsTo(BoatType::class, 'boat_type_id', 'id');
    }
}
