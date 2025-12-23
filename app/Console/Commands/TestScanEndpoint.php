<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Models\User;
use App\Http\Controllers\QrScannerController;
use Illuminate\Http\Request;

class TestScanEndpoint extends Command
{
    protected $signature = 'test:scan-endpoint {user_id} {room_number}';
    protected $description = 'Test scan endpoint directly';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $roomNumber = $this->argument('room_number');
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("User not found");
            return 1;
        }
        
        $room = Room::where('room_number', $roomNumber)->first();
        if (!$room) {
            $this->error("Room not found");
            return 1;
        }
        
        $this->info("Testing scan endpoint:");
        $this->info("User: {$user->name} (ID: {$user->id})");
        $this->info("Room: {$room->room_number}");
        $this->info("QR Code: {$room->qr_code}");
        $this->newLine();
        
        // Simulate authenticated request
        auth()->login($user);
        
        // Create mock request
        $request = new Request();
        $request->merge(['qr_code' => $room->qr_code]);
        $request->setMethod('POST');
        
        // Call controller method
        $controller = new QrScannerController();
        
        try {
            $response = $controller->scan($request);
            $data = $response->getData(true);
            
            $this->info("Response Status: " . $data['status']);
            $this->info("Response Message: " . $data['message']);
            
            if ($data['status'] === 'success') {
                $this->info("✅ SCAN SUCCESSFUL");
            } else {
                $this->warn("❌ SCAN FAILED");
            }
            
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
        }
        
        return 0;
    }
}