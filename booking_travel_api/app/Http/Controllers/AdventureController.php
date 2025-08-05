<?php

namespace App\Http\Controllers;

use App\Models\Adventure;
use App\Models\Province;
use Illuminate\Http\Request;

class AdventureController extends Controller
{

    public function hotels($id)
    {
        $adventure = Adventure::findOrFail($id);
        return response()->json($adventure->hotels);
    }
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

 public function index(Request $request)
    {
        $provinceId = $request->query('province_id');

        if ($provinceId) {
            $adventures = Adventure::where('province_id', $provinceId)->get();
        } else {
            $adventures = Adventure::all();
        }

        return response()->json($adventures);
    }
}
