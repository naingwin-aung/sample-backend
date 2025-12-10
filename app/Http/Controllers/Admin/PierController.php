<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\PierService;
use App\Http\Controllers\Controller;

class PierController extends Controller
{
    public function __construct(public PierService $service)
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

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $pier = $this->service->create($request);

            DB::commit();
            return success([
                'pier' => $pier,
            ], 'Pier created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $this->service->update($request, $id);

            DB::commit();
            return success([], 'Pier updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $pier = $this->service->getById($id);

            return success([
                'pier' => $pier,
            ], 'Pier retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->service->delete($id);

            DB::commit();
            return success([], 'Pier deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }
}
