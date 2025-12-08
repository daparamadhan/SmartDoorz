<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorAccessLog extends Model
{
    protected $fillable = ['room_id', 'user_id', 'qr_code', 'status', 'access_time', 'ip_address', 'user_agent'];

    protected $casts = [
        'access_time' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
