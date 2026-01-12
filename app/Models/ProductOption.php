<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'boat_id',
        'start_date',
        'end_date',
        'closing_type',
        'closing_dates',
        'closing_days',
    ];

    protected $casts = [
        'closing_dates' => 'array',
        'closing_days'  => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scheduleTimes()
    {
        return $this->hasMany(ProductScheduleTime::class, 'option_id');
    }

    public function tickets()
    {
        return $this->hasMany(ProductTicket::class, 'option_id');
    }

    public function ticketPrices()
    {
        return $this->hasMany(ProductTicketPrice::class, 'option_id');
    }

    public function boat()
    {
        return $this->belongsTo(Boat::class, 'boat_id');
    }

    public function productAdditionalOptions()
    {
        return $this->hasMany(ProductAdditionalOption::class, 'option_id')->whereNotNull('additional_option_id');
    }
}
