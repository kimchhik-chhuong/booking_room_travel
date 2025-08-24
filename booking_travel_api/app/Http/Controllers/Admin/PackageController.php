<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Amenity;
use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['category', 'destination'])
            ->withCount(['reviews', 'bookings']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by destination
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Filter by featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Filter by popular
        if ($request->filled('is_popular')) {
            $query->where('is_popular', $request->boolean('is_popular'));
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $packages = $query->paginate($request->get('per_page', 15));

        if ($request->expectsJson()) {
            return PackageResource::collection($packages);
        }

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $destinations = Destination::active()->orderBy('name')->get();
        $amenities = Amenity::active()->orderBy('name')->get();

        return view('admin.packages.create', compact('categories', 'destinations', 'amenities'));
    }

    public function store(PackageRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        
        // Handle file uploads
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('packages/featured', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('packages/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $package = Package::create($data);

        // Sync amenities
        if ($request->filled('amenities')) {
            $package->amenities()->sync($request->amenities);
        }

        if ($request->expectsJson()) {
            return new PackageResource($package->load(['category', 'destination', 'amenities']));
        }

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Package created successfully.');
    }

    public function show(Package $package)
    {
        $package->load(['category', 'destination', 'amenities', 'reviews.user']);
        
        if (request()->expectsJson()) {
            return new PackageResource($package);
        }

        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $categories = Category::active()->ordered()->get();
        $destinations = Destination::active()->orderBy('name')->get();
        $amenities = Amenity::active()->orderBy('name')->get();
        
        $package->load(['amenities']);

        return view('admin.packages.edit', compact('package', 'categories', 'destinations', 'amenities'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        $data = $request->validated();
        
        // Update slug if title changed
        if ($data['title'] !== $package->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $package->id);
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($package->featured_image) {
                Storage::disk('public')->delete($package->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('packages/featured', 'public');
        }

        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($package->gallery) {
                foreach ($package->gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('packages/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $package->update($data);

        // Sync amenities
        if ($request->filled('amenities')) {
            $package->amenities()->sync($request->amenities);
        }

        if ($request->expectsJson()) {
            return new PackageResource($package->load(['category', 'destination', 'amenities']));
        }

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        // Delete associated files
        if ($package->featured_image) {
            Storage::disk('public')->delete($package->featured_image);
        }
        
        if ($package->gallery) {
            foreach ($package->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $package->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Package deleted successfully.']);
        }

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature,publish,draft',
            'ids' => 'required|array',
            'ids.*' => 'exists:packages,id'
        ]);

        $packages = Package::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'delete':
                $packages->delete();
                $message = 'Selected packages deleted successfully.';
                break;
            case 'activate':
                $packages->update(['is_active' => true]);
                $message = 'Selected packages activated successfully.';
                break;
            case 'deactivate':
                $packages->update(['is_active' => false]);
                $message = 'Selected packages deactivated successfully.';
                break;
            case 'feature':
                $packages->update(['is_featured' => true]);
                $message = 'Selected packages marked as featured.';
                break;
            case 'unfeature':
                $packages->update(['is_featured' => false]);
                $message = 'Selected packages unmarked as featured.';
                break;
            case 'publish':
                $packages->update(['status' => 'published']);
                $message = 'Selected packages published successfully.';
                break;
            case 'draft':
                $packages->update(['status' => 'draft']);
                $message = 'Selected packages moved to draft.';
                break;
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function duplicate(Package $package)
    {
        $newPackage = $package->replicate();
        $newPackage->title = $package->title . ' (Copy)';
        $newPackage->slug = $this->generateUniqueSlug($newPackage->title);
        $newPackage->status = 'draft';
        $newPackage->is_featured = false;
        $newPackage->is_popular = false;
        $newPackage->total_bookings = 0;
        $newPackage->rating = 0;
        $newPackage->total_reviews = 0;
        $newPackage->save();

        // Copy amenities
        $newPackage->amenities()->sync($package->amenities->pluck('id'));

        if (request()->expectsJson()) {
            return new PackageResource($newPackage->load(['category', 'destination', 'amenities']));
        }

        return redirect()->route('admin.packages.edit', $newPackage)
            ->with('success', 'Package duplicated successfully.');
    }

    public function analytics(Package $package)
    {
        $analytics = [
            'total_views' => 0, // You can implement view tracking
            'total_bookings' => $package->bookings()->count(),
            'total_revenue' => $package->bookings()->sum('total_amount'),
            'average_rating' => $package->average_rating,
            'total_reviews' => $package->total_reviews,
            'conversion_rate' => 0, // views to bookings ratio
            'monthly_bookings' => $package->bookings()
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->pluck('count', 'month'),
            'recent_reviews' => $package->reviews()
                ->with('user')
                ->approved()
                ->latest()
                ->take(5)
                ->get(),
        ];

        if (request()->expectsJson()) {
            return response()->json($analytics);
        }

        return view('admin.packages.analytics', compact('package', 'analytics'));
    }

    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Package::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
