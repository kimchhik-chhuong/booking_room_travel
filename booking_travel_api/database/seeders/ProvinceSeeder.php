<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        $provinces = [
            'Banteaymeanchey',
            'Battambang',
            'Kampongcham',
            'Kampongchhnang',
            'Kampongspeu',
            'Kampongthom',
            'Kandal',
            'Kep',
            'Kohkong',
            'Kompot',
            'Mondulkiri',
            'Oddarmeanchey',
            'Pailin',
            'Phnompenh',
            'Preahvihear',
            'Preyveng',
            'Pursat',
            'Ratanakiri',
            'Siemreap',
            'Sihanoukville',
            'Stugteang',
            'Svayrieng',
            'Takeo',
            'Tboungkhmum',
        ];

        foreach ($provinces as $province) {
            Province::create(['name' => $province]);
        }
    }
}
