<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Http\Controllers\Controller;
use App\Services\Api\ProductOptionService;

class ProductOptionController extends Controller
{
    public function __construct(public ProductOptionService $service)
    {
    }

    public function index($slug, $optionId)
    {
        $option = $this->service->index($slug, $optionId);
        
        return success([
            'data' => $option,
        ], 'Product option retrieved successfully.');
    }
}
