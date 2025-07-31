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
                'hotels' => ['Ohana Phnom Penh Palace Hotel', 'The Pavilion'],
            ],
            [
                'name' => 'Koh Dach cycling',
                'description' => 'Cycling adventure on Koh Dach island.',
                'province_name' => 'Kandal',
                'hotels' => ['La Plantation Koh Dach', 'Sunrise River Resort'],
            ],
            [
                'name' => 'Han Chey Mountain, Bamboo Bridge',
                'description' => 'Explore Han Chey Mountain and Bamboo Bridge.',
                'province_name' => 'Kampongcham',
                'hotels' => ['LBN Asian Hotel', 'Monorom VIP Hotel'],
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
            ]);

            $hotelIds = Hotel::whereIn('name', $data['hotels'])->pluck('id')->toArray();
            $adventure->hotels()->sync($hotelIds);
        }
    }
}
