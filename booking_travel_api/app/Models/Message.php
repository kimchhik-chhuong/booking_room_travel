<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'is_read',
        'priority'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}