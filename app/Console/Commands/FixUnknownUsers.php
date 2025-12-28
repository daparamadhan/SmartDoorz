<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DoorAccessLog;

class FixUnknownUsers extends Command
{
    protected $signature = 'fix:unknown-users';
    protected $description = 'Fix access logs with null user_id by using room owner';

    public function handle()
    {
        $this->info('Memperbaiki data access log dengan user_id null...');
        
        $logsToFix = DoorAccessLog::whereNull('user_id')
            ->whereHas('room', function($query) {
                $query->whereNotNull('user_id');
            })
            ->with('room')
            ->get();
            
        $fixed = 0;
        
        foreach ($logsToFix as $log) {
            if ($log->room && $log->room->user_id) {
                $log->update(['user_id' => $log->room->user_id]);
                $fixed++;
            }
        }
        
        $this->info("Berhasil memperbaiki {$fixed} record access log.");
        
        return 0;
    }
}