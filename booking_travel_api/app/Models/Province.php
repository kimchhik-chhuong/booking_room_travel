<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'description'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * Get the URL for the province's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('storage/images/default-province.jpg');
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Clean up the path
        $path = ltrim($this->image, '/');
        $path = str_replace('storage/', '', $path);
        $path = str_replace('public/', '', $path);

        // Check if file exists in storage
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        // Try different possible paths
        $possiblePaths = [
            'uploads/adventures/' . basename($path),
            'adventures/' . basename($path),
            'provinces/' . basename($path),
            'uploads/provinces/' . basename($path),
        ];

        foreach ($possiblePaths as $possiblePath) {
            if (Storage::disk('public')->exists($possiblePath)) {
                return Storage::url($possiblePath);
            }
        }

        // If file still not found, return the default image
        return asset('storage/images/default-province.jpg');
    }

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