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
                ['id' => 1, 'name' => 'Fake Adventure 1', 'description' => 'Description for Fake Adventure 1', 'image_url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=60&q=80'],
                ['id' => 2, 'name' => 'Fake Adventure 2', 'description' => 'Description for Fake Adventure 2', 'image_url' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=60&q=80'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Fake Adventure 3', 'description' => 'Description for Fake Adventure 3', 'image_url' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=60&q=80'],
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
