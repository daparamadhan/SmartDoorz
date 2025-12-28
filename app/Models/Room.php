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
        // Ruangan tersedia jika:
        // 1. Tidak ada user_id
        // 2. User ada tapi sewa sudah expired
        return $this->user_id === null || 
               $this->user_id === 0 || 
               ($this->user && $this->user->isRentalExpired());
    }

    public function getQrImageUrl()
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($this->qr_code);
    }

    public function doorAccessLogs()
    {
        return $this->hasMany(DoorAccessLog::class);
    }

    public function getRoomStatus()
    {
        if ($this->status === 'maintenance') {
            return 'maintenance';
        }
        
        if (!$this->user_id) {
            return 'tersedia';
        }
        
        // Cek apakah sewa user sudah expired
        if ($this->user && $this->user->isRentalExpired()) {
            return 'expired';
        }
        
        if ($this->user && $this->user->status === 'delay') {
            return 'delay';
        }
        
        return 'ditempati';
    }

    public function getStatusColor()
    {
        switch ($this->getRoomStatus()) {
            case 'tersedia':
                return 'bg-green-500';
            case 'ditempati':
                return 'bg-red-500';
            case 'expired':
                return 'bg-orange-500';
            case 'delay':
                return 'bg-blue-500';
            case 'maintenance':
                return 'bg-gray-500';
            default:
                return 'bg-gray-500';
        }
    }
}
