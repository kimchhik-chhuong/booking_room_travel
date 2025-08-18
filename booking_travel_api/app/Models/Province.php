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
            return null;
        }

        // Check if the image is already a full URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Check if the file exists in storage
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // Fallback to the old path if file doesn't exist in the new location
        $oldPath = str_replace('provinces/', 'provinces/images/', $this->image);
        if (Storage::disk('public')->exists($oldPath)) {
            return Storage::url($oldPath);
        }

        return null;
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