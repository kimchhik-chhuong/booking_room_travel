<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'hotel_metadata_id',
        'name',
        'description',
        'price',
        'max_occupancy',
        'available_rooms',
        'amenities',
        'image_url'
    ];

    protected $casts = [
        'amenities' => 'array',
        'price' => 'decimal:2',
    ];

    public function hotelMetadata()
    {
        return $this->belongsTo(HotelMetadata::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
