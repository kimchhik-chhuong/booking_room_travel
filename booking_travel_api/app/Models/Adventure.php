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
        if (!$this->image_url) {
            return url('/uploads/adventures/default-adventure.jpg');
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        // For local paths, generate the full URL
        if (str_starts_with($this->image_url, 'uploads/adventures/')) {
            return url($this->image_url);
        }

        // If it's just a filename, assume it's in the uploads/adventures directory
        return url('uploads/adventures/' . $this->image_url);
    }

    /**
     * Get the province that owns the adventure.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
