<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CheckoutService;
use App\Http\Resources\Api\Checkout\CheckoutResource;

class CheckoutController extends Controller
{
    public function __construct(public CheckoutService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $request->validate([
            'products'                         => 'required|array',
            'products.*.product_type'          => 'required|string', // boat
            'products.*.product_id'            => 'required_if:products.*.type,boat|integer',
            'products.*.option_id'             => 'required_if:products.*.type,boat|integer',
            'products.*.zone_id'               => 'required_if:products.*.type,boat|integer',
            'products.*.ticket_id'             => 'required_if:products.*.type,boat|integer',
            'products.*.schedule_time_id'      => 'required_if:products.*.type,boat|integer',
            'products.*.date'                  => 'required_if:products.*.type,boat|date',
            'products.*.quantities.*'          => 'required_if:products.*.type,boat|array',
            'products.*.quantities.*.id'       => 'required_if:products.*.type,boat|integer',
            'products.*.quantities.*.quantity' => 'required_if:products.*.type,boat|integer|min:1',
        ]);

        try {
            $products        = $this->service->processCheckout($request->all());
            $data_collection = CheckoutResource::collection($products)->toArray($request);

            $total_price = collect($data_collection)->reduce(function ($carry, $item) {
                return $carry + $item['total_price'];
            }, 0);

            return success([
                'data'        => $data_collection,
                'total_price' => $total_price,
            ], 'Checkout processed successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
