<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call the seeders for provinces, hotel metadata, and adventures
        $this->call([
            ProvinceSeeder::class,
            HotelMetadataSeeder::class,
            AdventureSeeder::class,
        ]);
    }
}
