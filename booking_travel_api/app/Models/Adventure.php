<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adventure extends Model
{
    protected $fillable = [
        'name',
        'description',
        'province_id',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'adventure_hotel');
    }
}
