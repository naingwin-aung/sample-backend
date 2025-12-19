<?php
namespace App\Services\Api;

use Illuminate\Support\Str;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartService
{
    public function listing(array $data)
    {
        $cart = ShoppingCart::where('guid', $data['guid'])
            ->firstOrFail();

        return $cart;
    }

    public function create(array $data)
    {
        $cart = ShoppingCart::create([
            'guid'      => Str::uuid(),
            'cart_data' => $data['cart_data'],
            'status'    => 'active',
        ]);

        return $cart;
    }
}