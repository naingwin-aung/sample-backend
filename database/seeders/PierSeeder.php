<?php

namespace Database\Seeders;

use App\Models\Pier;
use Illuminate\Database\Seeder;

class PierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $piers = [
            ['name' => 'SIAM CHAROEN NAKHON'],
            ['name' => 'WAT WORACHANYAWAT'],
            ['name' => 'SATHORN'],
            ['name' => 'CAT TOWER'],
            ['name' => 'ICON SIAM'],
            ['name' => 'SI PHRAYA'],
            ['name' => 'MARINE DEPARTMENT'],
            ['name' => 'RATCHAWONG'],
            ['name' => 'MEMORIAL BRIDGE'],
            ['name' => 'RAJINEE'],
            ['name' => 'THA TIAN'],
            ['name' => 'WAT ARUN'],
            ['name' => 'THA CHANG'],
            ['name' => 'PRANNOK'],
            ['name' => 'PHRA PINKLAO'],
            ['name' => 'THEWET'],
            ['name' => 'PAYAP'],
            ['name' => 'KIAK KAI'],
            ['name' => 'BANGPHO'],
            ['name' => 'NONTHABURI'],
            ['name' => 'RAMA VII'],
            ['name' => 'PHRA NANG KLAO'],
        ];

        foreach ($piers as $pier) {
            Pier::updateOrCreate([
                'name' => $pier['name'],
            ]);
        }
    }
}
