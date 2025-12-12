<?php
namespace App\Services\Admin;

use App\Models\BoatType;
use Illuminate\Http\Request;

class BoatTypeService
{
    public function listing(Request $request)
    {
        $query = BoatType::query();

        if (isset($request->search)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $types = $query
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $types;
    }

    public function create(Request $request)
    {
        $type = BoatType::create([
            'name' => $request->name,
        ]);

        return $type;
    }

    public function getById(int $id)
    {
        return BoatType::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $type = $this->getById($id);
        $type->update([
            'name' => $request->name,
        ]);

        return $type;
    }

    public function delete(int $id)
    {
        $type = $this->getById($id);
        $type->delete();
    }
}