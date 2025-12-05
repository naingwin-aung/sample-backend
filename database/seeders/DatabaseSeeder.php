<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'naingwinaung1710@gmail.com',
        ], [
            'name' => 'zen',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            PierSeeder::class,
            BoatTypeSeeder::class,
            AdditionalOptionSeeder::class
        ]);
    }
}
