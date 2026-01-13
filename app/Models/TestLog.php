<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TestLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'test_logs';

    protected $fillable = [
        'booking_number'
    ];
}
