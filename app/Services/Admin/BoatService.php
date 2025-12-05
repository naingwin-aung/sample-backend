<?php
namespace App\Services\Admin;

use Exception;
use App\Models\Boat;
use App\Models\BoatZone;
use App\Models\BoatImage;
use App\Models\ZoneImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function getById(int $id)
    {
        return Boat::with(['boatType', 'images', 'zones.images'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $boat = $this->getById($id);
        $boat->update([
            'name'         => $request->name,
            'boat_type_id' => isset($request->boat_type['id']) ? $request->boat_type['id'] : null,
            'capacity'     => $request->capacity,
            'seat_type'    => $request->seat_type,
        ]);

        $currentZoneIds = collect($request->zones)->pluck('id')->filter();

        if ($currentZoneIds->isNotEmpty()) {
            $zonesToDelete = $boat->zones()->with('images')->whereNotIn('id', $currentZoneIds)->get();
            foreach ($zonesToDelete as $zoneToDelete) {
                foreach ($zoneToDelete->images as $image) {
                    Storage::delete($image->getRawOriginal('url'));
                }
            }
            $boat->zones()->whereNotIn('id', $currentZoneIds)->delete();
        } else {
            $zonesToDelete = $boat->zones()->with('images')->get();
            foreach ($zonesToDelete as $zoneToDelete) {
                foreach ($zoneToDelete->images as $image) {
                    Storage::delete($image->getRawOriginal('url'));
                }
            }
            $boat->zones()->delete();
        }

        $this->_updateZone($request, $boat);

        if (count($this->imageZoneArray) > 0) {
            ZoneImage::insert($this->imageZoneArray);
        }

        // update boat images
        if (!empty($request->old_images)) {
            if ($boat->images->count() < 1 && empty($request->images)) {
                throw new Exception('At least one gallery image is required.');
            }

            $files = $boat->images()
                ->whereIn('id', $request->old_images ?? [])
                ->get();

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $oldImage = $file->getRawOriginal('url') ?? '';
                    Storage::delete($oldImage);
                }

                $boat->images()->whereIn('id', $request->old_images)->delete();
            }
        }

        if (!empty($request->images) && count($request->images) > 0) {
            if ($request->file('images')) {
                $this->_createImages($boat, $request->file('images'));
            }
        }

        if (count($this->imageArray) > 0) {
            BoatImage::insert($this->imageArray);
        }

        return $boat;
    }

    public function delete(int $id)
    {
        $boat = $this->getById($id);
        $boat->delete();
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

    private function _updateZone(Request $request, Boat $boat)
    {
        foreach ($request->zones as $index => $zone) {
            $currentZone = null;

            if (isset($zone['id']) && is_numeric($zone['id'])) {
                $currentZone = $boat->zones()->where('id', $zone['id'])->first();
                if ($currentZone) {
                    $currentZone->update([
                        'name'     => $zone['name'],
                        'capacity' => $zone['capacity'],
                    ]);

                    if (!empty($zone['old_images'])) {
                        $imagesToDelete = $currentZone->images()->whereIn('id', $zone['old_images'])->get();

                        if (count($imagesToDelete) > 0) {
                            foreach ($imagesToDelete as $image) {
                                Storage::delete($image->getRawOriginal('url'));
                            }
                            $currentZone->images()->whereIn('id', $zone['old_images'])->delete();
                        }
                    }

                    if (!empty($request->file('zones')[$index]['images'])) {
                        $this->_createZoneImages($currentZone, $request->file('zones')[$index]['images']);
                    }
                }
            } else {
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
        }
    }
}