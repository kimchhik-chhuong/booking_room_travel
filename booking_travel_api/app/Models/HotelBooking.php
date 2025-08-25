<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'booking_id',
        'hotel_id',
        'room_type_id',
        'check_in_date',
        'check_out_date',
        'num_rooms',
        'num_guests',
        'price_per_night',
        'total_hotel_price',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_requests'
    ];

    protected $dates = [
        'check_in_date',
        'check_out_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'total_hotel_price' => 'decimal:2',
        'num_rooms' => 'integer',
        'num_guests' => 'integer',
    ];

    /**
     * Get the booking that owns the hotel booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the hotel that was booked.
     */
    public function hotel()
    {
        return $this->belongsTo(HotelMetadata::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Get the room type that was booked.
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Calculate the number of nights for the stay.
     */
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Check if the booking is active (check-in date is today or in the past, and check-out date is in the future).
     */
    public function getIsActiveAttribute()
    {
        $today = now()->startOfDay();
        return $this->check_in_date->lte($today) && $this->check_out_date->gt($today);
    }

    /**
     * Check if the booking is upcoming.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->check_in_date->isFuture();
    }

    /**
     * Check if the booking is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->check_out_date->isPast();
    }

    /**
     * Get the booking status with a badge.
     */
    public function getStatusBadgeAttribute()
    {
        $status = strtolower($this->status);
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'checked_in' => 'bg-blue-100 text-blue-800',
            'checked_out' => 'bg-purple-100 text-purple-800',
            'no_show' => 'bg-gray-100 text-gray-800',
        ];

        $badgeClass = $badges[$status] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $status)) . '</span>';
    }

    /**
     * Scope a query to only include active bookings.
     */
    public function scopeActive($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('check_in_date', '<=', $today)
                    ->where('check_out_date', '>=', $today);
    }

    /**
     * Scope a query to only include upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('check_in_date', '>', now());
    }

    /**
     * Scope a query to only include past bookings.
     */
    public function scopePast($query)
    {
        return $query->where('check_out_date', '<', now());
    }

    /**
     * Get the formatted price per night.
     */
    public function getFormattedPricePerNightAttribute()
    {
        return '$' . number_format($this->price_per_night, 2);
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_hotel_price, 2);
    }
}
