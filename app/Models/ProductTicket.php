<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTicket extends Model
{
    protected $table = 'product_tickets';

    protected $fillable = [
        'product_id',
        'option_id',
        'name',
        'short_description',
    ];

    public function prices()
    {
        return $this->hasMany(ProductTicketPrice::class, 'ticket_id');
    }
}
