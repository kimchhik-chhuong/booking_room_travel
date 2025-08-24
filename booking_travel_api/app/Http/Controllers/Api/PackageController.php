<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageCollection;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['category', 'destination'])
            ->published()
            ->active();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }
        }

        // Filter by destination
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

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Filter by tags
        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', trim($tag));
                }
            });
        }

        // Featured packages
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Popular packages
        if ($request->boolean('popular')) {
            $query->popular();
        }

        // Sort
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

        $packages = $query->paginate($request->get('per_page', 12));

        return new PackageCollection($packages);
    }

    public function show(Package $package)
    {
        if (!$package->is_available) {
            return response()->json(['message' => 'Package not available'], 404);
        }

        $package->load([
            'category',
            'destination',
            'amenities',
            'approvedReviews.user',
            'approvedReviews' => function ($query) {
                $query->latest()->take(10);
            }
        ]);

        return new PackageResource($package);
    }

    public function featured(Request $request)
    {
        $packages = Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->featured()
            ->orderBy('total_bookings', 'desc')
            ->take($request->get('limit', 6))
            ->get();

        return PackageResource::collection($packages);
    }

    public function popular(Request $request)
    {
        $packages = Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->popular()
            ->orderBy('total_bookings', 'desc')
            ->take($request->get('limit', 6))
            ->get();

        return PackageResource::collection($packages);
    }

    public function similar(Package $package, Request $request)
    {
        $similar = Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->where('id', '!=', $package->id)
            ->where(function ($query) use ($package) {
                $query->where('category_id', $package->category_id)
                      ->orWhere('destination_id', $package->destination_id);
            })
            ->orderBy('rating', 'desc')
            ->take($request->get('limit', 4))
            ->get();

        return PackageResource::collection($similar);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $packages = Package::with(['category', 'destination'])
            ->published()
            ->active()
            ->search($request->q)
            ->orderBy('rating', 'desc')
            ->paginate($request->get('per_page', 12));

        return new PackageCollection($packages);
    }

    public function filters()
    {
        $categories = Category::active()
            ->withCount(['packages' => function ($query) {
                $query->published()->active();
            }])
            ->having('packages_count', '>', 0)
            ->ordered()
            ->get(['id', 'name', 'slug']);

        $destinations = Destination::active()
            ->withCount(['packages' => function ($query) {
                $query->published()->active();
            }])
            ->having('packages_count', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'country', 'country_code']);

        $priceRange = Package::published()->active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $durationRange = Package::published()->active()->selectRaw('MIN(duration_days) as min_days, MAX(duration_days) as max_days')->first();

        return response()->json([
            'categories' => $categories,
            'destinations' => $destinations,
            'price_range' => $priceRange,
            'duration_range' => $durationRange,
            'difficulty_levels' => ['easy', 'moderate', 'challenging'],
            'meal_plans' => ['breakfast', 'half-board', 'full-board', 'all-inclusive'],
        ]);
    }
}
