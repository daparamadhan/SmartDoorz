<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class TestExpiredRental extends Command
{
    protected $signature = 'test:expired-rental {user_id}';
    protected $description = 'Set user rental to expired for testing';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User dengan ID {$userId} tidak ditemukan");
            return 1;
        }
        
        // Set rental expired kemarin
        $user->rental_end = Carbon::yesterday();
        $user->save();
        
        $this->info("User {$user->name} (ID: {$userId}) rental di-set expired pada: {$user->rental_end}");
        $this->info("Sekarang coba scan QR code untuk test akses expired");
        
        return 0;
    }
}