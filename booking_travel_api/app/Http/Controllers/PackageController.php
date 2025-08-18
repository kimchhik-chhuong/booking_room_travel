<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Province;
use Illuminate\Support\Facades\Schema;

class PackageController extends Controller
{
    public function index()
    {
        // Get provinces with counts of related hotels and adventures
        $provinces = Province::withCount(['hotels', 'adventures'])
            ->paginate(12); // 12 items per page

        // Get package statistics (keeping existing functionality)
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
            'packages',
            'provinces' // Add provinces to the view
        ));
    }

    public function showProvince($id)
    {
        $province = Province::withCount(['hotels', 'adventures'])->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($province);
        }
        
        return view('packages.province', compact('province'));
    }

    /**
     * Show the form for creating a new province.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('packages.provinces.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('provinces', 'public');
            $validated['image'] = $path;
        }

        $province = Province::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Province created successfully',
                'province' => $province
            ]);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Province created successfully.');
    }

    public function update(Request $request, $id)
    {
        $province = Province::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($province->image) {
                \Storage::disk('public')->delete($province->image);
            }
            $path = $request->file('image')->store('provinces', 'public');
            $validated['image'] = $path;
        }

        $province->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Province updated successfully',
                'province' => $province
            ]);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Province updated successfully.');
    }

    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        
        // Delete associated image if exists
        if ($province->image) {
            \Storage::disk('public')->delete($province->image);
        }
        
        $province->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Province deleted successfully'
            ]);
        }

        return redirect()->route('packages.index')
            ->with('success', 'Province deleted successfully.');
    }
}
