<?php
namespace App\Services\Api;

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

        if(isset($request->except_pier_id)) {
            $query = $query->where('id', '!=', $request->except_pier_id);
        }

        $piers = $query->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $piers;
    }
}