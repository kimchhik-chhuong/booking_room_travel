<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Package;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\Traveler;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'traveler_id',
        'package_id',  // This will be null for hotel-only bookings
        'booking_reference',
        'booking_date',
        'travel_date',
        'participants',
        'total_amount',
        'currency',
        'status',
        'payment_status',
        'guest_first_name',
        'guest_last_name',
        'guest_email',
        'guest_phone',
        'guest_nationality'
    ];

    protected $attributes = [
        'package_id' => null,  // Default to null for hotel-only bookings
        'status' => 'pending',
        'payment_status' => 'pending',
        'currency' => 'USD',
        'participants' => 1
    ];

    protected $dates = [
        'booking_date',
        'travel_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'travel_date' => 'date',
        'total_amount' => 'decimal:2',
        'participants' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package that was booked (if any).
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withDefault();
    }

    /**
     * Get the hotel bookings for this booking.
     */
    public function hotelBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }

    /**
     * Get the payment for this booking.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the traveler for this booking.
     */
    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * Check if this is a hotel-only booking.
     */
    public function isHotelOnly(): bool
    {
        return is_null($this->package_id);
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if the booking is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if the booking is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get the booking status with a badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        $status = strtolower($this->status);
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ];

        $badgeClass = $badges[$status] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $badgeClass . '">' . ucfirst($status) . '</span>';
    }

    /**
     * Generate a unique booking reference.
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'BK-' . strtoupper(uniqid());
        } while (static::where('booking_reference', $reference)->exists());

        return $reference;
    }
}