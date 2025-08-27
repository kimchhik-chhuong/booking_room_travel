<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class HotelMetadata extends Model
{
    protected $table = 'hotel_metadata';
    protected $primaryKey = 'hotel_id';

    protected $fillable = [
        'name',
        'address',
        'star_rating',
        'description',
        'image_url',
        'images',
        'amenities',
        'contact_phone',
        'email',
        'website_url',
        'map',
        'latitude',
        'longitude',
        'check_in_time',
        'check_out_time',
        'adventure_id',
        'province_id',
        'status',
        'user_id'
    ];

    protected $casts = [
        'star_rating' => 'float',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'images' => 'array',
        'amenities' => 'array',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    protected $appends = ['full_image_url', 'full_images', 'additional_images'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the room types for the hotel.
     */
    public function roomTypes()
    {
        return $this->hasMany(RoomType::class, 'hotel_id', 'hotel_id');
    }

    public function hotelBookings()
    {
        return $this->hasMany(HotelBooking::class, 'hotel_id', 'hotel_id');
    }

    public function adventure()
    {
        return $this->belongsTo(Adventure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for getting the main image
    public function getMainImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return $this->image_url;
    }

    // Accessor for formatted rating
    public function getFormattedRatingAttribute()
    {
        return $this->star_rating ? number_format($this->star_rating, 1) : null;
    }

    // Accessor for full image URL
    public function getFullImageUrlAttribute()
    {
        return $this->getFullUrl($this->image_url);
    }

    /**
     * Get all images including the main image with full URLs
     *
     * @return array
     */
    public function getFullImagesAttribute()
    {
        $images = [];
        
        // Add main image if exists
        if ($this->image_url) {
            $images[] = $this->getFullUrl($this->image_url);
        }
        
        // Add additional images
        $additionalImages = $this->additional_images;
        if (!empty($additionalImages)) {
            $images = array_merge($images, $additionalImages);
        }
        
        return $images;
    }

    // Accessor for images
    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // If it's a JSON string, decode it
        $decoded = json_decode($value, true);
        
        // If decoding failed, return empty array
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        
        return $decoded;
    }

    /**
     * Get the additional images with full URLs
     *
     * @return array
     */
    public function getAdditionalImagesAttribute()
    {
        $images = $this->images;
        
        if (empty($images)) {
            return [];
        }
        
        // If $images is a string, try to decode it as JSON
        if (is_string($images)) {
            $images = json_decode($images, true);
            
            // If json_decode failed or returned null, return empty array
            if (json_last_error() !== JSON_ERROR_NONE || $images === null) {
                return [];
            }
        }
        
        // Ensure we have an array
        if (!is_array($images)) {
            return [];
        }
        
        // Map each image to its full URL
        return array_map(function($image) {
            return $this->getFullUrl($image);
        }, $images);
    }

    // Scope for active hotels
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for hotels with location
    public function scopeWithLocation($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    protected function getFullUrl($path)
    {
        if (empty($path)) {
            return null;
        }
        
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Remove 'public/' from the path if it exists
        $path = str_replace('public/', '', $path);
        
        // Return full URL
        return asset('storage/' . ltrim($path, '/'));
    }
}
