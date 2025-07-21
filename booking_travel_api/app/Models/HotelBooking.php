<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    protected $fillable = ['booking_id', 'hotel_metadata_id', 'nights'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function hotelMetadata()
    {
        return $this->belongsTo(HotelMetadata::class);
    }
}
