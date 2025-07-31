<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['name', 'country', 'description', 'image_url'];

    public function hotels()
    {
        return $this->hasMany(HotelMetadata::class);
    }
}
