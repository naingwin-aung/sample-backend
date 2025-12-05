<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Pier;
use Illuminate\Http\Request;
use App\Services\PierService;

class PierController extends Controller
{
    public function __construct(public PierService $service)
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric|min:1',
            'limit'  => 'required|numeric|min:1|max:100',
            'except_pier_id' => 'nullable|numeric',
        ]);

        try {
            $piers = $this->service->listing($request);

            return success([
                'page'     => $piers->currentPage(),
                'limit'    => $piers->perPage(),
                'total'    => $piers->total(),
                'has_more' => $piers->hasMorePages(),
                'data'     => $piers->items(),
            ], 'Piers retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }
}
