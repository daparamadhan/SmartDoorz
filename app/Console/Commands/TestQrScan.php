<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Room;

class TestQrScan extends Command
{
    protected $signature = 'test:qr-scan {user_id} {qr_code}';
    protected $description = 'Test QR scan functionality';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $qrCode = $this->argument('qr_code');
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $room = Room::where('qr_code', $qrCode)->first();
        if (!$room) {
            $this->error("Room with QR code '{$qrCode}' not found");
            $this->info("Available QR codes:");
            Room::all()->each(function($room) {
                $this->line("Room {$room->room_number}: {$room->qr_code}");
            });
            return 1;
        }
        
        $this->info("Test QR Scan:");
        $this->info("User: {$user->name} (ID: {$user->id})");
        $this->info("Room: {$room->room_number} (ID: {$room->id})");
        $this->info("Room Owner: " . ($room->user ? $room->user->name : 'None') . " (ID: {$room->user_id})");
        $this->info("QR Code: {$qrCode}");
        
        $isAuthorized = $room->user_id == $user->id;
        
        if ($isAuthorized) {
            $this->info("✅ AUTHORIZED - User can access this room");
        } else {
            $this->warn("❌ UNAUTHORIZED - User cannot access this room");
        }
        
        return 0;
    }
}