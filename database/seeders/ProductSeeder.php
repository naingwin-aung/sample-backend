<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'slug' => '1-product-1',
                'description' => 'Description for Product 1',
            ],
            [
                'name' => 'Product 2',
                'slug' => '2-product-2',
                'description' => 'Description for Product 2',
            ],
            [
                'name' => 'Product 3',
                'slug' => '3-product-3',
                'description' => 'Description for Product 3',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
