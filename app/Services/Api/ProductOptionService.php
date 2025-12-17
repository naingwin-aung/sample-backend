<?php
namespace App\Services\Api;

use App\Models\ProductOption;

class ProductOptionService
{
    public function index($slug, $optionId)
    {
        $option = ProductOption::where('id', $optionId)
            ->whereHas('product', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with(['boat.zones.images', 'tickets.prices', 'scheduleTimes'])
            ->firstOrFail();
            
        return $option;
    }
}