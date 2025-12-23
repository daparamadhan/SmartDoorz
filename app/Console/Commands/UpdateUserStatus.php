<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class UpdateUserStatus extends Command
{
    protected $signature = 'fix:update-user-status';
    protected $description = 'Update user status for users who have rooms assigned';

    public function handle()
    {
        $this->info('Updating user status for users with assigned rooms...');
        
        // Find users who have rooms but status is still pending
        $usersWithRooms = User::whereHas('rooms')->where('status', 'pending')->get();
        
        foreach ($usersWithRooms as $user) {
            $this->info("Updating user: {$user->name} (ID: {$user->id})");
            
            // Update user status and rental info
            $user->update([
                'status' => 'active',
                'rental_start' => Carbon::now(),
                'rental_end' => Carbon::now()->addMonth(),
                'rental_months' => 1
            ]);
            
            $this->info("  - Status updated to: active");
            $this->info("  - Rental period: " . Carbon::now()->format('Y-m-d') . " to " . Carbon::now()->addMonth()->format('Y-m-d'));
        }
        
        $this->info("\nUpdate completed!");
        
        return 0;
    }
}