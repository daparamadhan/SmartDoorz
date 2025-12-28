<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class TestRoomStatus extends Command
{
    protected $signature = 'test:room-status';
    protected $description = 'Test room status with expired rental';

    public function handle()
    {
        $this->info('=== TESTING ROOM STATUS ===');
        
        $rooms = Room::with('user')->get();
        
        foreach ($rooms as $room) {
            $this->info("Room {$room->room_number}:");
            $this->info("  User ID: " . ($room->user_id ?? 'NULL'));
            
            if ($room->user) {
                $this->info("  User: {$room->user->name}");
                $this->info("  Rental End: " . ($room->user->rental_end ? $room->user->rental_end->format('d M Y H:i') : 'NULL'));
                $this->info("  Is Expired: " . ($room->user->isRentalExpired() ? 'YES' : 'NO'));
            }
            
            $status = $room->getRoomStatus();
            $isAvailable = $room->isAvailable();
            
            $this->info("  Status: {$status}");
            $this->info("  Available: " . ($isAvailable ? 'YES' : 'NO'));
            $this->info("  Color: {$room->getStatusColor()}");
            $this->newLine();
        }
        
        return 0;
    }
}