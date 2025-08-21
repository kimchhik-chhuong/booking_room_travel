<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Get the province that owns the adventure.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
