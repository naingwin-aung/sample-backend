<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdditionalOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdditionalOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $additionalOptions = [
            [
                'name' => 'Beer buffet',
                'description' => 'A buffet offering a variety of beers.',
            ],
            [
                'name' => 'BBQ buffet',
                'description' => 'Barbecue facilities available on the boat.',
            ],
            [
                'name' => 'Seafood platter',
                'description' => 'A selection of fresh seafood served on board.',
            ]
        ];

        foreach ($additionalOptions as $additionalOption) {
            AdditionalOption::updateOrCreate([
                'name' => $additionalOption['name'],
                'description' => $additionalOption['description'],
            ]);
        }
    }
}
