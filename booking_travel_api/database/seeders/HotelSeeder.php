<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Province;

class HotelSeeder extends Seeder
{
    public function run()
    {
        $provinces = Province::all();

        foreach ($provinces as $province) {
            // Create 2 sample hotels per province
            for ($i = 1; $i <= 2; $i++) {
                Hotel::create([
                    'name' => $province->name . " Hotel " . $i,
                    'image' => 'https://via.placeholder.com/300x200.png?text=' . urlencode($province->name . " Hotel " . $i),
                    'price' => rand(50, 200),
                    'day' => rand(1, 7),
                    'description' => "A nice hotel located in " . $province->name,
                    'province_id' => $province->id,
                ]);
            }
        }
    }
}
