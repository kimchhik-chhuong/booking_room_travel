<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getPackagesCountAttribute()
    {
        return $this->packages()->where('status', 'published')->where('is_active', true)->count();
    }

    // Methods
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
