<?php
namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function listing(Request $request)
    {
        $query = Product::query();

        if (isset($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $products;
    }
}