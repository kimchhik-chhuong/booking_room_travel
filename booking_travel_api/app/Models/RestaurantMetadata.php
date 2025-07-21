<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantMetadata extends Model
{
      protected $fillable = ['destination_id', 'restaurant_name', 'cuisine_type'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
