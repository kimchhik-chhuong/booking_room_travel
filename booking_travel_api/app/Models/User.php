<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function traveler()
    {
        return $this->hasOne(Traveler::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Mass assignable attributes
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'is_active',
        'registration_date',
        'last_login',
        'profile_picture_url',
        'address',
        'role',
    ];

    /**
     * Hidden attributes for serialization
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'last_login'        => 'datetime',
        'is_active'         => 'boolean',
    ];

    /**
     * Accessor for profile image URL
     */
    public function getImageUrlAttribute(): string
    {
        return $this->profile_picture_url
            ? asset('storage/' . $this->profile_picture_url)
            : asset('images/default-avatar.png');
    }

    /**
     * Booted method to delete profile picture on user deletion
     */
    protected static function booted()
    {
        static::deleted(function ($user) {
            if ($user->profile_picture_url && Storage::disk('public')->exists($user->profile_picture_url)) {
                Storage::disk('public')->delete($user->profile_picture_url);
            }
        });
    }

    /**
     * Role checks
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    /**
     * Query scopes for roles
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeEmployers($query)
    {
        return $query->where('role', 'employer');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }
}
