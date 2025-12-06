<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\ProductService;
use App\Http\Requests\Admin\Product\CreateRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;

class ProductController extends Controller
{
    public function __construct(public ProductService $service)
    {
        //
    }

    public function index(Request $request)
    {
        $request->validate([
            'page'   => 'required|numeric|min:1',
            'limit'  => 'required|numeric|min:1|max:100',
            'search' => 'nullable|string|max:255',
        ]);

        try {
            $products = $this->service->listing($request);

            return success([
                'page'     => $products->currentPage(),
                'limit'    => $products->perPage(),
                'total'    => $products->total(),
                'has_more' => $products->hasMorePages(),
                'data'     => $products->items(),
            ], 'Products retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = $this->service->create($request);

            DB::commit();
            return success([
                'product' => $product,
            ], 'Product created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $product = $this->service->getById($id);

            return success([
                'product' => $product,
            ], 'Product retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $id);

            DB::commit();
            return success([], 'Product updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }
}
