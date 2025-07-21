<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
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
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
     * Check if user has 'user' role
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has 'admin' role
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has 'employer' role
     */
    public function isEmployer()
    {
        return $this->role === 'employer';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function getImageUrlAttribute()
    {
        return $this->profile_picture_url
            ? asset('storage/' . $this->profile_picture_url)
            : asset('images/default-avatar.png');
    }

    protected static function booted()
    {
        static::deleted(function ($user) {
            if ($user->profile_picture_url) {
                Storage::disk('public')->delete($user->profile_picture_url);
            }
        });
    }
}
