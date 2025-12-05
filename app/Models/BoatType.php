<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoatType extends Model
{
    protected $table = 'boat_types';

    protected $fillable = [
        'name'
    ];
}
