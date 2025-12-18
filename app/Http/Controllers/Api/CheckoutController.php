<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(public CheckoutService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $request->validate([
            'products'                      => 'required|array',
            'products.*.product_type'       => 'required|string', // boat
            'products.*.product_id'         => 'required_if:products.*.type,boat|integer',
            'products.*.option_id'          => 'required_if:products.*.type,boat|integer',
            'products.*.zone_id'            => 'required_if:products.*.type,boat|integer',
            'products.*.ticket_id'          => 'required_if:products.*.type,boat|integer',
            'products.*.schedule_time_id'   => 'required_if:products.*.type,boat|integer',
            'products.*.date'               => 'required_if:products.*.type,boat|date',
            'products.*.quantities.*'       => 'required_if:products.*.type,boat|array',
            'products.*.quantities.*.id'    => 'required_if:products.*.type,boat|integer',
            'products.*.quantities.*.count' => 'required_if:products.*.type,boat|integer|min:1',
        ]);

        try {
            [$products] = $this->service->processCheckout($request->all());

            return success([
                'products' => $products,
            ], 'Checkout processed successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
