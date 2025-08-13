<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'images',
        'is_verified',
        'is_featured',
        'is_approved',
        'travel_date',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'travel_date' => 'datetime',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Boot method to update package rating when review is saved
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            if ($review->is_approved) {
                $review->package->updateRating();
            }
        });

        static::deleted(function ($review) {
            $review->package->updateRating();
        });
    }
}
