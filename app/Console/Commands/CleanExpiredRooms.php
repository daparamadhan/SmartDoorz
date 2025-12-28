<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class CleanExpiredRooms extends Command
{
    protected $signature = 'clean:expired-rooms';
    protected $description = 'Remove users from rooms where rental has expired';

    public function handle()
    {
        $this->info('Membersihkan ruangan dengan sewa expired...');
        
        // Cari ruangan yang user-nya expired
        $expiredRooms = Room::whereNotNull('user_id')
            ->whereHas('user', function($query) {
                $query->whereNotNull('rental_end')
                      ->where('rental_end', '<', now());
            })
            ->with('user')
            ->get();
            
        $cleaned = 0;
        
        foreach ($expiredRooms as $room) {
            $this->info("Membersihkan Room {$room->room_number} - User: {$room->user->name} (Expired: {$room->user->rental_end})");
            
            // Set user_id menjadi null
            $room->update(['user_id' => null]);
            $cleaned++;
        }
        
        $this->info("Berhasil membersihkan {$cleaned} ruangan yang expired.");
        
        return 0;
    }
}