<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FakeDataController extends Controller
{
    public function getAdventuresByProvinceFake($provinceId)
    {
        // Return hardcoded adventures data for testing
        $fakeAdventures = [
            1 => [
                ['id' => 1, 'name' => 'Fake Adventure 1', 'description' => 'Description for Fake Adventure 1'],
                ['id' => 2, 'name' => 'Fake Adventure 2', 'description' => 'Description for Fake Adventure 2'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Fake Adventure 3', 'description' => 'Description for Fake Adventure 3'],
            ],
        ];

        $adventures = $fakeAdventures[$provinceId] ?? [];

        return response()->json(['data' => $adventures]);
    }

    public function getHotelsByAdventureFake($adventureId)
    {
        // Return hardcoded hotels data for testing
        $fakeHotels = [
            1 => [
                ['id' => 1, 'hotel_name' => 'Fake Hotel 1', 'image_url' => '', 'price' => 100, 'days' => 2, 'description' => 'Description for Fake Hotel 1'],
                ['id' => 2, 'hotel_name' => 'Fake Hotel 2', 'image_url' => '', 'price' => 150, 'days' => 3, 'description' => 'Description for Fake Hotel 2'],
            ],
            2 => [
                ['id' => 3, 'hotel_name' => 'Fake Hotel 3', 'image_url' => '', 'price' => 120, 'days' => 1, 'description' => 'Description for Fake Hotel 3'],
            ],
        ];

        $hotels = $fakeHotels[$adventureId] ?? [];

        return response()->json(['data' => $hotels]);
    }
}
