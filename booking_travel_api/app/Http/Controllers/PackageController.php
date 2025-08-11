<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $totalPackages    = Package::count();
        $newThisMonth     = Package::whereMonth('created_at', now()->month)->count();
        $activePackages   = Package::where('status', 'active')->count();
        $inactivePackages = Package::where('status', 'inactive')->count();

        // If rating column doesn't exist, set it to 0
        $averageRating = Package::avg('rating') ?? 0;

        $packages = Package::all();

        return view('packages.index', compact(
            'totalPackages',
            'newThisMonth',
            'activePackages',
            'inactivePackages',
            'averageRating',
            'packages'
        ));
    }
}
