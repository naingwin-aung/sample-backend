<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductScheduleTime extends Model
{
    protected $table = 'product_schedule_times';

    protected $fillable = [
        'product_id',
        'option_id',
        'start_time',
        'end_time',
    ];
}
