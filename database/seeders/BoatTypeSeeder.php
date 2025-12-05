<?php

namespace Database\Seeders;

use App\Models\BoatType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BoatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boatTypes = [
            ['name' => 'Luxury Speedboat'],
            ['name' => 'Electric Pontoon'],
            ['name' => 'Yacht'],
        ];

        foreach ($boatTypes as $type) {
            BoatType::updateOrCreate([
                'name' => $type['name'],
            ]);
        }
    }
}
