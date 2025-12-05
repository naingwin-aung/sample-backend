<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\BoatService;
use App\Http\Controllers\Controller;

class BoatController extends Controller
{
    public function __construct(public BoatService $service)
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
            $boats = $this->service->listing($request);

            return success([
                'page'     => $boats->currentPage(),
                'limit'    => $boats->perPage(),
                'total'    => $boats->total(),
                'has_more' => $boats->hasMorePages(),
                'data'     => $boats->items(),
            ], 'Boats retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'boat_type'        => 'required',
            'boat_type.id'     => 'required|integer',
            'capacity'         => 'required|integer',
            'seat_type'        => 'required|string',
            'images'           => 'required|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'zones'            => 'required_if:seat_type,==,zone',
            'zones.*.name'     => 'required_with:zones|string|max:255',
            'zones.*.capacity' => 'required_with:zones|integer|min:1',
            'zones.*.images'   => 'required_with:zones|array',
            'zones.*.images.*' => 'required_with:zones|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $boat = $this->service->create($request);

            DB::commit();
            return success([
                'boat' => $boat,
            ], 'Boat created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'boat_type'        => 'required',
            'boat_type.id'     => 'required|integer',
            'capacity'         => 'required|integer',
            'seat_type'        => 'required|string',
            'images'           => 'nullable|array',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'old_images'       => 'nullable|array',
            'zones'            => 'required_if:seat_type,==,zone',
            'zones.*.name'     => 'required_with:zones|string|max:255',
            'zones.*.capacity' => 'required_with:zones|integer|min:1',
            'zones.*.images'   => 'nullable|array',
            'zones.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $this->service->update($request, $id);

            DB::commit();
            return success([], 'Boat updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $boat = $this->service->getById($id);

            return success([
                'boat' => $boat,
            ], 'Boat retrieved successfully.');
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
            return success([], 'Boat deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return error($e->getMessage());
        }
    }
}
