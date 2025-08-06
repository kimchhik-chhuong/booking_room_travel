<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = ['title', 'discount', 'description', 'code', 'valid_until', 'limit', 'status', 'color', 'used'];
}