 <?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PackageCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PackageResource::collection($this->collection),
            'meta' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }

    public function with($request)
    {
        return [
            'filters' => $this->getAvailableFilters(),
            'sort_options' => $this->getSortOptions(),
        ];
    }

    private function getAvailableFilters()
    {
        return [
            'categories' => \App\Models\Category::active()->ordered()->get(['id', 'name', 'slug']),
            'destinations' => \App\Models\Destination::active()->orderBy('name')->get(['id', 'name', 'slug', 'country']),
            'difficulty_levels' => ['easy', 'moderate', 'challenging'],
            'meal_plans' => ['breakfast', 'half-board', 'full-board', 'all-inclusive'],
        ];
    }

    private function getSortOptions()
    {
        return [
            'latest' => 'Latest',
            'price_low' => 'Price: Low to High',
            'price_high' => 'Price: High to Low',
            'rating' => 'Highest Rated',
            'popular' => 'Most Popular',
            'duration' => 'Duration',
        ];
    }
}
