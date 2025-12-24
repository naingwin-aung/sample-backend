<?php
namespace App\Services\Api;

use App\Models\ProductOption;
use Carbon\Carbon;

class ProductOptionService
{
    public function index($slug, $optionId)
    {
        $option = ProductOption::where('id', $optionId)
            ->whereHas('product', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->where('end_date', '>=', Carbon::now()->startOfDay())
            ->with(['boat.zones.images', 'tickets.prices', 'scheduleTimes'])
            ->firstOrFail();

        return $option;
    }
}