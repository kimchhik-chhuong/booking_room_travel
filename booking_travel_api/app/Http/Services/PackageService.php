<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageService
{
    public function getPackages(Request $request)
    {
        $query = Package::with(['category', 'destination'])
            ->published()
            ->active();

        // Apply filters
        $this->applyFilters($query, $request);
        
        // Apply sorting
        $this->applySorting($query, $request);

        return $query->paginate($request->get('per_page', 12));
    }

    public function getFeaturedPackages($limit = 6)
    {
        return Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->featured()
            ->orderBy('total_bookings', 'desc')
            ->take($limit)
            ->get();
    }

    public function getPopularPackages($limit = 6)
    {
        return Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->popular()
            ->orderBy('total_bookings', 'desc')
            ->take($limit)
            ->get();
    }

    public function getSimilarPackages(Package $package, $limit = 4)
    {
        return Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->where('id', '!=', $package->id)
            ->where(function ($query) use ($package) {
                $query->where('category_id', $package->category_id)
                      ->orWhere('destination_id', $package->destination_id);
            })
            ->orderBy('rating', 'desc')
            ->take($limit)
            ->get();
    }

    public function searchPackages($query, Request $request)
    {
        return Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->search($query)
            ->orderBy('rating', 'desc')
            ->paginate($request->get('per_page', 12));
    }

    public function createPackage(array $data)
    {
        // Generate unique slug
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        
        // Handle file uploads
        $data = $this->handleFileUploads($data);

        $package = Package::create($data);

        // Sync amenities if provided
        if (isset($data['amenities'])) {
            $package->amenities()->sync($data['amenities']);
        }

        return $package;
    }

    public function updatePackage(Package $package, array $data)
    {
        // Update slug if title changed
        if ($data['title'] !== $package->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $package->id);
        }
        
        // Handle file uploads
        $data = $this->handleFileUploads($data, $package);

        $package->update($data);

        // Sync amenities if provided
        if (isset($data['amenities'])) {
            $package->amenities()->sync($data['amenities']);
        }

        return $package;
    }

    public function deletePackage(Package $package)
    {
        // Delete associated files
        $this->deletePackageFiles($package);
        
        return $package->delete();
    }

    public function getPackageStats()
    {
        return [
            'total' => Package::count(),
            'published' => Package::published()->count(),
            'featured' => Package::featured()->count(),
            'popular' => Package::popular()->count(),
            'average_price' => Package::published()->avg('price'),
            'total_bookings' => Package::sum('total_bookings'),
            'average_rating' => Package::where('rating', '>', 0)->avg('rating'),
        ];
    }

    public function getFilters()
    {
        return [
            'categories' => Category::active()
                ->withCount(['packages' => function ($query) {
                    $query->published()->active();
                }])
                ->having('packages_count', '>', 0)
                ->ordered()
                ->get(['id', 'name', 'slug']),
            
            'destinations' => Destination::active()
                ->withCount(['packages' => function ($query) {
                    $query->published()->active();
                }])
                ->having('packages_count', '>', 0)
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'country', 'country_code']),
            
            'price_range' => Package::published()->active()
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                ->first(),
            
            'duration_range' => Package::published()->active()
                ->selectRaw('MIN(duration_days) as min_days, MAX(duration_days) as max_days')
                ->first(),
            
            'difficulty_levels' => ['easy', 'moderate', 'challenging'],
            'meal_plans' => ['breakfast', 'half-board', 'full-board', 'all-inclusive'],
        ];
    }

    private function applyFilters($query, Request $request)
    {
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }
        }

        // Destination filter
        if ($request->filled('destination')) {
            if (is_numeric($request->destination)) {
                $query->where('destination_id', $request->destination);
            } else {
                $query->whereHas('destination', function ($q) use ($request) {
                    $q->where('slug', $request->destination);
                });
            }
        }

        // Price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Duration range
        if ($request->filled('min_days') || $request->filled('max_days')) {
            $query->durationRange($request->min_days, $request->max_days);
        }

        // Difficulty filter
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Tags filter
        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', trim($tag));
                }
            });
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Popular filter
        if ($request->boolean('popular')) {
            $query->popular();
        }
    }

    private function applySorting($query, Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_bookings', 'desc');
                break;
            case 'duration':
                $query->orderBy('duration_days', 'asc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }
    }

    private function handleFileUploads(array $data, Package $package = null)
    {
        // Handle featured image
        if (isset($data['featured_image']) && is_file($data['featured_image'])) {
            // Delete old image if updating
            if ($package && $package->featured_image) {
                Storage::disk('public')->delete($package->featured_image);
            }
            
            $data['featured_image'] = $data['featured_image']->store('packages/featured', 'public');
        }

        // Handle gallery images
        if (isset($data['gallery']) && is_array($data['gallery'])) {
            // Delete old gallery if updating
            if ($package && $package->gallery) {
                foreach ($package->gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $gallery = [];
            foreach ($data['gallery'] as $file) {
                if (is_file($file)) {
                    $gallery[] = $file->store('packages/gallery', 'public');
                }
            }
            $data['gallery'] = $gallery;
        }

        return $data;
    }

    private function deletePackageFiles(Package $package)
    {
        // Delete featured image
        if ($package->featured_image) {
            Storage::disk('public')->delete($package->featured_image);
        }
        
        // Delete gallery images
        if ($package->gallery) {
            foreach ($package->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }
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
