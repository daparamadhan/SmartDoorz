<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'qr_code', 'user_id', 'status', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Kosong']);
    }

    public function accessLogs()
    {
        return $this->hasMany(DoorAccessLog::class);
    }

    public function isAvailable()
    {
        return $this->user_id === null || $this->user_id === 0;
    }

    public function getQrImageUrl()
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($this->qr_code);
    }

    public function doorAccessLogs()
    {
        return $this->hasMany(DoorAccessLog::class);
    }
}
