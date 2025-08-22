<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

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
