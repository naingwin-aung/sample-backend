<?php
namespace App\Services\Admin;

use App\Models\Pier;
use Illuminate\Http\Request;

class PierService
{
    public function listing(Request $request)
    {
        $query = Pier::query();

        if (isset($request->search)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $piers = $query
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $piers;
    }

    public function create(Request $request)
    {
        $pier = Pier::create([
            'name' => $request->name,
        ]);

        return $pier;
    }

    public function getById(int $id)
    {
        return Pier::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pier = $this->getById($id);
        $pier->update([
            'name' => $request->name,
        ]);

        return $pier;
    }

    public function delete(int $id)
    {
        $pier = $this->getById($id);
        $pier->delete();
    }
}