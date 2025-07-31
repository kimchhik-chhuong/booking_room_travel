<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use App\Models\Province;
use Illuminate\Http\Request;

class AdventureController extends Controller
{
    public function getAdventuresByProvince($provinceId)
    {
        $province = Province::findOrFail($provinceId);
        $adventures = $province->adventures()->get();

        return response()->json(['data' => $adventures]);
    }

    public function getHotelsByAdventure($adventureId)
    {
        $adventure = Adventure::with('hotels')->findOrFail($adventureId);
        return response()->json(['data' => $adventure->hotels]);
    }
}
