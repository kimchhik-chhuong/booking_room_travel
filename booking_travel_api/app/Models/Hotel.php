<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
        'day',
        'description',
        'province_id',
        'adventure_id',
    ];

    public function adventure()
    {
        return $this->belongsTo(Adventure::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
