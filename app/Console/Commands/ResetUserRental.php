<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ResetUserRental extends Command
{
    protected $signature = 'test:reset-rental {user_id}';
    protected $description = 'Reset user rental to active for testing';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User dengan ID {$userId} tidak ditemukan");
            return 1;
        }
        
        // Set rental aktif untuk 1 bulan ke depan
        $user->rental_start = Carbon::now();
        $user->rental_end = Carbon::now()->addMonth();
        $user->save();
        
        $this->info("User {$user->name} (ID: {$userId}) rental di-reset ke aktif");
        $this->info("Rental End: {$user->rental_end}");
        
        return 0;
    }
}