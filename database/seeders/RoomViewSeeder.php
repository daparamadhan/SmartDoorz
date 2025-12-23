<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\User;
use App\Helpers\QrCodeGenerator;

class RoomViewSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample users with different statuses
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'delay'],
            ['name' => 'Bob Wilson', 'email' => 'bob@example.com', 'status' => 'active'],
            ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'status' => 'delay'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'status' => 'active'],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                    'status' => $userData['status'],
                    'is_admin' => false,
                ]
            );
            $createdUsers[] = $user;
        }

        // Create 32 rooms (4x8 grid like cinema seats)
        for ($i = 1; $i <= 32; $i++) {
            $roomNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            
            // Determine room status and occupancy
            $status = 'available';
            $userId = null;
            
            if ($i <= 15) {
                // First 15 rooms are occupied
                $status = 'occupied';
                $userId = $createdUsers[array_rand($createdUsers)]->id;
            } elseif ($i >= 29) {
                // Last 4 rooms are maintenance
                $status = 'maintenance';
            }
            // Rooms 16-28 remain available
            
            Room::firstOrCreate(
                ['room_number' => $roomNumber],
                [
                    'qr_code' => QrCodeGenerator::generateQrCode(),
                    'status' => $status,
                    'user_id' => $userId,
                    'notes' => $status === 'maintenance' ? 'Sedang dalam perbaikan' : null,
                ]
            );
        }
    }
}