<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelMetadata extends Model
{

protected $fillable = ['destination_id', 'name', 'price', 'day', 'address', 'star_rating', 'description', 'image_url', 'contact_phone', 'website_url'];

public function destination()
{
    return $this->belongsTo(Destination::class);
}

public function hotelBookings()
{
    return $this->hasMany(HotelBooking::class);
}

public function province()
{
    return $this->belongsTo(Province::class);
}

}
