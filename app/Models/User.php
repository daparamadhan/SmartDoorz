<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rental_start',
        'rental_end',
        'rental_months',
    ];

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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rental_start' => 'datetime',
            'rental_end' => 'datetime',
        ];
    }

    public function isRentalExpired()
    {
        return $this->rental_end && $this->rental_end->isPast();
    }

    public function getRentalStatusAttribute()
    {
        if (!$this->rental_start) return 'pending';
        if ($this->isRentalExpired()) return 'expired';
        return 'active';
    }

    public function room()
    {
        return $this->hasOne(Room::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(DoorAccessLog::class);
    }
}
