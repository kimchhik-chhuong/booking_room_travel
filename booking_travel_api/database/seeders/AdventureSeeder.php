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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Hotel::truncate();
        Adventure::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $provinces = Province::factory(5)->create();

        foreach ($provinces as $province) {
            $adventures = Adventure::factory(2)->create([
                'province_id' => $province->id,
            ]);

            foreach ($adventures as $adventure) {
                Hotel::factory(3)->create([
                    'province_id' => $province->id,
                    'adventure_id' => $adventure->id,
                ]);
            }
        }
    }
}
