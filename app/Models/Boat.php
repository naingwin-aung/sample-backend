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
}
