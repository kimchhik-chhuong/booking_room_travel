<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Adventure extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'province_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_path'];

    /**
     * Get the full image path with URL.
     *
     * @return string
     */
    public function getImagePathAttribute()
    {
        // If no image URL is set, return the default image
        if (empty($this->image_url)) {
            return asset('images/default-adventure.jpg');
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        // Check if file exists in storage
        if (Storage::disk('public')->exists($this->image_url)) {
            return asset('storage/' . $this->image_url);
        }

        // Check if file exists in public directory (legacy support)
        if (file_exists(public_path($this->image_url))) {
            return asset($this->image_url);
        }

        // Return default image if file doesn't exist
        return asset('images/default-adventure.jpg');
    }

    /**
     * Get the province that owns the adventure.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
