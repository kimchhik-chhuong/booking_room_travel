<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'hotel_id',
        'check_in_date',
        'check_out_date',
        'room_type',
        'num_rooms',
        'num_guests',
        'price_per_night',
        'total_hotel_price',
        'status'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'price_per_night' => 'decimal:2',
        'total_hotel_price' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function hotelMetadata()
    {
        return $this->belongsTo(HotelMetadata::class, 'hotel_id', 'hotel_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type');
    }

    /**
     * Calculate total nights between check-in and check-out
     */
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Get formatted price per night with currency
     */
    public function getFormattedPricePerNightAttribute()
    {
        return '$' . number_format($this->price_per_night, 2);
    }

    /**
     * Get formatted total hotel price with currency
     */
    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_hotel_price, 2);
    }
}
