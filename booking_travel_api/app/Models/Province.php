<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function adventures()
    {
        return $this->hasMany(Adventure::class);
    }
}
