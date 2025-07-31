<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'image',
        'price',
        'day',
        'description',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function adventures()
    {
        return $this->belongsToMany(Adventure::class, 'adventure_hotel');
    }
}
