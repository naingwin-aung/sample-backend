<?php
namespace App\Services\Api;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function listing(Request $request)
    {
        $query = Product::with(['images', 'piers'])
            ->withMin('ticketPrices', 'net_price');

        if (isset($request->search)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if (isset($request->except_ids) && is_array($request->except_ids)) {
            $query = $query->whereNotIn('id', $request->except_ids);
        }

        $products = $query->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $products;
    }

    public function getByProduct($slug)
    {
        $product = Product::with([
            'images',
            'piers',
            'options' => function ($query) {
                $query->withMin('ticketPrices', 'net_price')
                    ->where('end_date', '>=', Carbon::now()->startOfDay());
            },
            'options.boat.images',
        ])
            ->withMin('ticketPrices', 'net_price')
            ->where('slug', $slug)
            ->firstOrFail();

        return $product;
    }

    public function relatedProducts($id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = Product::with(['images', 'piers'])
            ->withMin('ticketPrices', 'net_price')
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return $relatedProducts;
    }
}