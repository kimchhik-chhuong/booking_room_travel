<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->when($this->needsFullDetails($request), $this->description),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'destination' => new DestinationResource($this->whenLoaded('destination')),
            'price' => [
                'amount' => $this->price,
                'formatted' => $this->formatted_price,
                'original_amount' => $this->original_price,
                'original_formatted' => $this->formatted_original_price,
                'discount_percentage' => $this->discount_percentage,
                'has_discount' => $this->discount_percentage > 0,
            ],
            'duration' => [
                'days' => $this->duration_days,
                'nights' => $this->duration_nights,
                'text' => $this->duration_text,
            ],
            'participants' => [
                'min' => $this->min_participants,
                'max' => $this->max_participants,
            ],
            'difficulty_level' => $this->difficulty_level,
            'rating' => [
                'average' => round($this->rating, 1),
                'total_reviews' => $this->total_reviews,
                'stars' => $this->getStarsArray(),
            ],
            'images' => [
                'featured' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
                'gallery' => $this->gallery ? collect($this->gallery)->map(fn($img) => asset('storage/' . $img)) : [],
            ],
            'features' => [
                'is_featured' => $this->is_featured,
                'is_popular' => $this->is_popular,
                'is_available' => $this->is_available,
            ],
            'details' => $this->when($this->needsFullDetails($request), [
                'inclusions' => $this->inclusions,
                'exclusions' => $this->exclusions,
                'highlights' => $this->highlights,
                'requirements' => $this->requirements,
                'itinerary' => $this->itinerary,
                'accommodation_type' => $this->accommodation_type,
                'meal_plan' => $this->meal_plan,
                'transportation' => $this->transportation,
                'cancellation_policy' => $this->cancellation_policy,
                'advance_booking_days' => $this->advance_booking_days,
            ]),
            'amenities' => AmenityResource::collection($this->whenLoaded('amenities')),
            'reviews' => PackageReviewResource::collection($this->whenLoaded('approvedReviews')),
            'availability' => [
                'from' => $this->available_from?->format('Y-m-d'),
                'until' => $this->available_until?->format('Y-m-d'),
                'specific_dates' => $this->available_dates,
                'can_book_today' => $this->canBeBooked(),
            ],
            'tags' => $this->tags,
            'statistics' => [
                'total_bookings' => $this->total_bookings,
                'views' => 0, // Implement view tracking if needed
            ],
            'meta' => [
                'status' => $this->status,
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString(),
            ],
        ];
    }

    private function needsFullDetails($request)
    {
        // Return full details for single package view or when explicitly requested
        return $request->route()->getName() === 'packages.show' || 
               $request->has('include_details') ||
               !$request->route(); // For admin views
    }

    private function getStarsArray()
    {
        $rating = $this->rating;
        $stars = [];
        
        for ($i = 1; $i <= 5; $i++) {
            if ($rating >= $i) {
                $stars[] = 'full';
            } elseif ($rating >= $i - 0.5) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }
        
        return $stars;
    }
}
