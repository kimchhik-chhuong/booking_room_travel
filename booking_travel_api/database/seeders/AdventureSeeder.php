<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Adventure;
use App\Models\Province;
use App\Models\Hotel;

class AdventureSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing adventures and pivot data
        DB::table('adventure_hotel')->truncate();
        Adventure::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Sample adventures data with province and hotels
        $adventuresData = [
            [
                'name' => 'Areyksat Zipline, Mekong biking',
                'description' => 'Exciting zipline and biking adventures in Phnompenh.',
                'province_name' => 'Phnompenh',
                'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=60&q=80',
            'hotels' => ['Phnompenh Hotel 1', 'Phnompenh Hotel 2'],
        ],
        [
            'name' => 'Koh Dach cycling',
            'description' => 'Cycling adventure on Koh Dach island.',
            'province_name' => 'Kandal',
            'image_url' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=60&q=80',
            'hotels' => ['Kandal Hotel 1', 'Kandal Hotel 2'],
        ],
        [
            'name' => 'Han Chey Mountain, Bamboo Bridge',
            'description' => 'Explore Han Chey Mountain and Bamboo Bridge.',
            'province_name' => 'Kampongcham',
            'image_url' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=60&q=80',
            'hotels' => ['Kampongcham Hotel 1', 'Kampongcham Hotel 2'],
            ],
            // Add more adventures as needed...
        ];

        foreach ($adventuresData as $data) {
            $province = Province::where('name', $data['province_name'])->first();
            if (!$province) {
                continue;
            }

            $adventure = Adventure::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'province_id' => $province->id,
                'image_url' => $data['image_url'] ?? null,
            ]);

            $hotelIds = Hotel::whereIn('name', $data['hotels'])->pluck('id')->toArray();
            $adventure->hotels()->sync($hotelIds);
        }
    }
}
