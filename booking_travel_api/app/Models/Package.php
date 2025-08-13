<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category_id',
        'destination_id',
        'price',
        'original_price',
        'duration_days',
        'duration_nights',
        'min_participants',
        'max_participants',
        'difficulty_level',
        'featured_image',
        'gallery',
        'inclusions',
        'exclusions',
        'itinerary',
        'highlights',
        'requirements',
        'accommodation_type',
        'meal_plan',
        'transportation',
        'is_featured',
        'is_popular',
        'is_active',
        'status',
        'available_from',
        'available_until',
        'available_dates',
        'advance_booking_days',
        'cancellation_policy',
        'tags',
        'meta_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'gallery' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'itinerary' => 'array',
        'highlights' => 'array',
        'requirements' => 'array',
        'available_dates' => 'array',
        'tags' => 'array',
        'meta_data' => 'array',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'available_from' => 'date',
        'available_until' => 'date',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'package_amenities');
    }

    public function reviews()
    {
        return $this->hasMany(PackageReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(PackageReview::class)->where('is_approved', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular(Builder $query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDestination(Builder $query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    public function scopePriceRange(Builder $query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeDurationRange(Builder $query, $minDays = null, $maxDays = null)
    {
        if ($minDays) {
            $query->where('duration_days', '>=', $minDays);
        }
        if ($maxDays) {
            $query->where('duration_days', '<=', $maxDays);
        }
        return $query;
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('destination', function ($destQuery) use ($search) {
                  $destQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('country', 'like', "%{$search}%");
              })
              ->orWhereHas('category', function ($catQuery) use ($search) {
                  $catQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Accessors
    public function getDurationTextAttribute()
    {
        return "{$this->duration_days} Days / {$this->duration_nights} Nights";
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return 0;
        }
        
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 0);
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? '$' . number_format($this->original_price, 0) : null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function getIsAvailableAttribute()
    {
        $now = now();
        
        if ($this->available_from && $now->lt($this->available_from)) {
            return false;
        }
        
        if ($this->available_until && $now->gt($this->available_until)) {
            return false;
        }
        
        return $this->is_active && $this->status === 'published';
    }

    // Methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function updateRating()
    {
        $avgRating = $this->approvedReviews()->avg('rating') ?: 0;
        $totalReviews = $this->approvedReviews()->count();
        
        $this->update([
            'rating' => round($avgRating, 2),
            'total_reviews' => $totalReviews,
        ]);
    }

    public function incrementBookings()
    {
        $this->increment('total_bookings');
    }

    public function canBeBooked($date = null)
    {
        if (!$this->is_available) {
            return false;
        }

        $bookingDate = $date ? carbon($date) : now();
        $minBookingDate = now()->addDays($this->advance_booking_days);

        return $bookingDate->gte($minBookingDate);
    }
}
