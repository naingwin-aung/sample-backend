<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'boat_id',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'closing_type',
        'closing_dates',
        'closing_days',
    ];

    protected $casts = [
        'closing_dates' => 'array',
        'closing_days' => 'array',
    ];

    public function tickets()
    {
        return $this->hasMany(ProductTicket::class, 'option_id');
    }
}
