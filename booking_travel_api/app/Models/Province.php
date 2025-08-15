<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
    ];

    /**
     * Get all adventures for the province.
     */
    public function adventures()
    {
        return $this->hasMany(Adventure::class);
    }

    /**
     * Get all hotels for the province.
     */
    public function hotels()
    {
        return $this->hasMany(HotelMetadata::class);
    }
}