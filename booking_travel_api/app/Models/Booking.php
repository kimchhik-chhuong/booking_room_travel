<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Package;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'booking_reference',
        'booking_date',
        'travel_date',
        'participants',
        'total_amount',
        'currency',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'travel_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Accessor to get formatted total amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->total_amount, 2);
    }

    /**
     * Scope to get only confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
