<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomType extends Model
{
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'hotel_metadata_id',
        'name',
        'description',
        'price',
        'max_occupancy',
        'available_rooms',
        'amenities',
        'image_url',
        'is_available'
    ];

    protected $casts = [
        'price' => 'float',
        'max_occupancy' => 'integer',
        'available_rooms' => 'integer',
        'amenities' => 'array',
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['image_url_full'];

    /**
     * Get the hotel that owns the room type.
     */
    public function hotel()
    {
        return $this->belongsTo(HotelMetadata::class, 'hotel_metadata_id', 'hotel_id');
    }

    /**
     * Get the hotel metadata that owns the room type.
     */
    public function hotelMetadata()
    {
        return $this->belongsTo(HotelMetadata::class, 'hotel_metadata_id');
    }

    /**
     * Get the bookings for the room type.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the full URL for the room type image.
     */
    public function getImageUrlFullAttribute()
    {
        if (empty($this->image_url)) {
            return null;
        }
        
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }
        
        return Storage::disk('public')->url($this->image_url);
    }

    /**
     * Get the amenities as an array.
     */
    public function getAmenitiesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        return [];
    }

    /**
     * Set the amenities attribute.
     */
    public function setAmenitiesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['amenities'] = json_encode(array_values($value));
        } elseif (is_string($value)) {
            // Try to decode to validate it's valid JSON
            $decoded = json_decode($value, true);
            $this->attributes['amenities'] = json_encode(is_array($decoded) ? $decoded : []);
        } else {
            $this->attributes['amenities'] = json_encode([]);
        }
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically encode amenities when setting
        static::saving(function ($model) {
            if (is_array($model->amenities)) {
                $model->amenities = json_encode($model->amenities);
            } elseif (is_null($model->amenities)) {
                $model->amenities = json_encode([]);
            }
        });
    }
}
