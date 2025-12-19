<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ShoppingCartService;

class ShoppingCartController extends Controller
{
    public function __construct(public ShoppingCartService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'guid' => 'required|string',
        ]);

        try {
            $carts = $this->service->listing($validated);

            return success([
                'guid' => $carts->guid,
                'data' => $carts->cart_data,
            ], 'Shopping carts retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'cart_data' => 'required',
        ]);

        try {
            $cart = $this->service->create($validated);

            return success([
                'guid' => $cart->guid,
            ], 'Shopping carts created successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
