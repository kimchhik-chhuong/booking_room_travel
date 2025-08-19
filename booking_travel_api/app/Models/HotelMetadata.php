<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'status'
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

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function hotelBookings()
    {
        return $this->hasMany(HotelBooking::class, 'hotel_id', 'hotel_id');
    }

    public function adventure()
    {
        return $this->belongsTo(Adventure::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class, 'hotel_metadata_id', 'hotel_id');
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
}
