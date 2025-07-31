<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambodiaTrip extends Model
{
    use HasFactory;

    protected $table = 'cambodia_trips';

    protected $fillable = [
        'country_name',
        'hotel_name',
    ];
}
