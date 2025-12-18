<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\Api\ProductOptionService;
use App\Http\Resources\Api\ProductOption\OptionResource;

class ProductOptionController extends Controller
{
    public function __construct(public ProductOptionService $service)
    {
    }

    public function index($slug, $optionId)
    {
        try {
            $option = $this->service->index($slug, $optionId);

            return success([
                // 'data' => $option,
                'data' => OptionResource::make($option),
            ], 'Product option retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
