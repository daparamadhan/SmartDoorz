<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Models\User;

class DebugAllQr extends Command
{
    protected $signature = 'debug:all-qr';
    protected $description = 'Debug all QR codes and test scan functionality';

    public function handle()
    {
        $this->info('=== DEBUGGING ALL QR CODES ===');
        
        // Get all rooms with QR codes
        $rooms = Room::all();
        
        $this->info("Total rooms: " . $rooms->count());
        $this->newLine();
        
        foreach ($rooms as $room) {
            $this->info("Room: {$room->room_number}");
            $this->info("  QR Code: {$room->qr_code}");
            $this->info("  User ID: " . ($room->user_id ?? 'NULL'));
            $this->info("  Status: {$room->status}");
            
            if ($room->user_id) {
                $user = User::find($room->user_id);
                $this->info("  User: " . ($user ? $user->name : 'NOT FOUND'));
                $this->info("  User Status: " . ($user ? $user->status : 'N/A'));
            }
            
            // Test if QR code can be found
            $foundRoom = Room::where('qr_code', $room->qr_code)->first();
            if ($foundRoom) {
                $this->info("  ✅ QR Code searchable");
            } else {
                $this->error("  ❌ QR Code NOT searchable");
            }
            
            $this->newLine();
        }
        
        // Test scan for users with rooms
        $this->info('=== TESTING SCAN FOR USERS WITH ROOMS ===');
        
        $usersWithRooms = User::whereHas('rooms')->with('rooms')->get();
        
        foreach ($usersWithRooms as $user) {
            $this->info("User: {$user->name} (ID: {$user->id}) - Status: {$user->status}");
            
            foreach ($user->rooms as $room) {
                $this->info("  Testing Room {$room->room_number}:");
                $this->info("    QR: {$room->qr_code}");
                
                // Simulate scan logic
                $foundRoom = Room::where('qr_code', $room->qr_code)->first();
                if (!$foundRoom) {
                    $this->error("    ❌ QR Code not found in database");
                    continue;
                }
                
                $isAuthorized = $foundRoom->user_id === $user->id;
                if ($isAuthorized) {
                    $this->info("    ✅ AUTHORIZED - Scan should work");
                } else {
                    $this->warn("    ⚠️  UNAUTHORIZED - Room belongs to user ID: {$foundRoom->user_id}");
                }
            }
            $this->newLine();
        }
        
        return 0;
    }
}