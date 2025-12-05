<?php
namespace App\Services\Admin;

use App\Models\Boat;
use App\Models\BoatZone;
use App\Models\BoatImage;
use App\Models\ZoneImage;
use Illuminate\Http\Request;

class BoatService
{
    public $imageArray = [];
    public $imageZoneArray = [];

    public function listing(Request $request)
    {
        $query = Boat::with(['boatType', 'images']);

        if (isset($request->search)) {
            $query = $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $boats = $query
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $boats;
    }

    public function create(Request $request)
    {
        $boat = Boat::create([
            'name'         => $request->name,
            'boat_type_id' => isset($request->boat_type['id']) ? $request->boat_type['id'] : null,
            'capacity'     => $request->capacity,
            'seat_type'    => $request->seat_type,
        ]);

        // Handle images
        if (!empty($request->images) && count($request->images) > 0) {
            if ($request->file('images')) {
                $this->_createImages($boat, $request->file('images'));
            }
        }

        if (count($this->imageArray) > 0) {
            BoatImage::insert($this->imageArray);
        }

        foreach ($request->zones as $index => $zone) {
            $create_zone = BoatZone::create([
                'boat_id'  => $boat->id,
                'name'     => $zone['name'],
                'capacity' => $zone['capacity'],
            ]);

            if (!empty($zone['images']) && count($zone['images']) > 0) {
                if ($request->file('zones')[$index]['images']) {
                    $this->_createZoneImages($create_zone, $request->file('zones')[$index]['images']);
                }
            }
        }

        if (count($this->imageZoneArray) > 0) {
            ZoneImage::insert($this->imageZoneArray);
        }

        return $boat;
    }

    private function _createImages(Boat $boat, $files)
    {
        foreach ($files as $image) {
            $this->imageArray[] = [
                'boat_id'    => $boat->id,
                'url'        => $image->store('boats'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    private function _createZoneImages(BoatZone $zone, $files)
    {
        foreach ($files as $image) {
            $this->imageZoneArray[] = [
                'zone_id'    => $zone->id,
                'url'        => $image->store('zones'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }
}