<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Schema;

class PackageController extends Controller
{
    public function index()
    {
        // Get counts
        $totalPackages = Package::count();
        $newThisMonth  = Package::whereMonth('created_at', now()->month)->count();

        // Check if "status" column exists
        $activePackages = 0;
        $inactivePackages = 0;
        if (Schema::hasColumn('packages', 'status')) {
            $activePackages   = Package::where('status', 'active')->count();
            $inactivePackages = Package::where('status', 'inactive')->count();
        }

        // Check if "rating" column exists
        $averageRating = 0;
        if (Schema::hasColumn('packages', 'rating')) {
            $averageRating = Package::avg('rating') ?? 0;
        }

        // Force to collection to avoid "count() on array" issues
        $packages = collect(Package::all());

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
