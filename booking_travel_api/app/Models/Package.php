<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'destination_id',
        'price',
        'duration_days',
        'duration_nights',
        'image',
        'status',
        'featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'featured' => 'boolean'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getDurationAttribute()
    {
        return "{$this->duration_days} Days";
    }

    public function getNightsAttribute()
    {
        return "{$this->duration_nights} nights";
    }
}