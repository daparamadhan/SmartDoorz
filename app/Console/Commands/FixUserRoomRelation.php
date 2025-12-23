<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Room;

class FixUserRoomRelation extends Command
{
    protected $signature = 'fix:user-room-relation';
    protected $description = 'Fix and debug user-room relationship';

    public function handle()
    {
        $this->info('Checking User-Room relationships...');
        
        // Show all users and their rooms
        $users = User::with('rooms')->get();
        
        foreach ($users as $user) {
            $this->info("User: {$user->name} (ID: {$user->id}) - Status: {$user->status}");
            
            if ($user->rooms->count() > 0) {
                foreach ($user->rooms as $room) {
                    $this->info("  - Room: {$room->room_number} (Status: {$room->status})");
                }
            } else {
                $this->warn("  - No rooms assigned");
            }
        }
        
        $this->info("\nChecking Rooms and their users...");
        
        // Show all rooms and their users
        $rooms = Room::with('user')->get();
        
        foreach ($rooms as $room) {
            $userName = $room->user ? $room->user->name : 'No user';
            $this->info("Room: {$room->room_number} - User: {$userName} (user_id: {$room->user_id})");
        }
        
        return 0;
    }
}