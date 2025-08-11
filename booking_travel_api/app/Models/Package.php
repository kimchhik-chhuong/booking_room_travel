<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'title',
        'location',
        'duration',
        'price',
        'rating',
        'bookings',
        'status',
        'image',
        'category'
    ];
}
