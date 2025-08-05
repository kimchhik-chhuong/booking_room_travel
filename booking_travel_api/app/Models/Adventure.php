<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adventure extends Model
{
    use HasFactory;
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
        return $this->hasMany(Hotel::class);
    }
}
