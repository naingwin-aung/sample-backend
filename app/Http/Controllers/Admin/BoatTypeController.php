<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\BoatTypeService;

class BoatTypeController extends Controller
{
    public function __construct(public BoatTypeService $service)
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
            $types = $this->service->listing($request);

            return success([
                'page'     => $types->currentPage(),
                'limit'    => $types->perPage(),
                'total'    => $types->total(),
                'has_more' => $types->hasMorePages(),
                'data'     => $types->items(),
            ], 'Boat types retrieved successfully.');
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
            $type = $this->service->create($request);

            DB::commit();
            return success([
                'type' => $type,
            ], 'Boat type created successfully.');
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
            return success([], 'Boat type updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $type = $this->service->getById($id);

            return success([
                'type' => $type,
            ], 'Boat type retrieved successfully.');
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
            return success([], 'Boat type deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }
}
