<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class GetRoomQr extends Command
{
    protected $signature = 'get:room-qr {room_number}';
    protected $description = 'Get QR code for a room';

    public function handle()
    {
        $roomNumber = $this->argument('room_number');
        
        $room = Room::where('room_number', $roomNumber)->first();
        
        if (!$room) {
            $this->error("Room {$roomNumber} not found");
            return 1;
        }
        
        $this->info("Room: {$room->room_number}");
        $this->info("QR Code: {$room->qr_code}");
        $this->info("User ID: {$room->user_id}");
        $this->info("Status: {$room->status}");
        
        return 0;
    }
}