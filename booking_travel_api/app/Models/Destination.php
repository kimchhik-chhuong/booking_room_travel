<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['name', 'country', 'description', 'image_url'];

    public function hotelMetadata()
    {
        return $this->hasMany(HotelMetadata::class);
    }

    public function restaurantMetadata()
    {
        return $this->hasMany(RestaurantMetadata::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
