<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function piers()
    {
        return $this->belongsToMany(Pier::class, 'product_piers', 'product_id', 'pier_id');
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
