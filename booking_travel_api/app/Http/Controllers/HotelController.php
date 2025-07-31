<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of hotels by province ID.
     */
    public function getHotelsByProvince($provinceId)
    {
        $hotels = Hotel::where('province_id', $provinceId)->get();

        return response()->json([
            'data' => $hotels,
        ]);
    }
}
