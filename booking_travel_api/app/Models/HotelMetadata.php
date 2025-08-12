<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelMetadata extends Model
{

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'contact_phone',
        'website_url',
        'map',
        'days',
        'destination_id',
        'adventure_id'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function hotelBookings()
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function adventure()
    {
        return $this->belongsTo(Adventure::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }
}
