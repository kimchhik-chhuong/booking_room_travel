<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelMetadata extends Model
{
    
    protected $fillable = ['destination_id', 'hotel_name', 'price'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function hotelBookings()
    {
        return $this->hasMany(HotelBooking::class);
    }
}
