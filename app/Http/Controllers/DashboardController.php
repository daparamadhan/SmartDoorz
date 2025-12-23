<?php

namespace App\Http\Controllers;

use App\Models\DashboardStat;
use App\Models\Room;
use App\Models\User;
use App\Models\DoorAccessLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Jika user biasa, tampilkan user dashboard dengan scanner
        if (!auth()->user()->is_admin) {
            return view('dashboard.user');
        }

        // Admin dashboard dengan analytics
        // Overall Stats
        $totalUsers = User::count();
        $totalRooms = Room::count();
        $availableRooms = Room::where('user_id', null)->where('status', 'available')->count();
        $occupiedRooms = Room::where('user_id', '!=', null)->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();
        
        // Recent access logs with relationships
        $recentAccessLogs = DoorAccessLog::with('room', 'user')
            ->orderBy('access_time', 'desc')
            ->limit(15)
            ->get();

        // Daily access chart data (last 7 days) - menggunakan timezone lokal
        $dailyAccess = DoorAccessLog::selectRaw('DATE(CONVERT_TZ(access_time, "+00:00", "+07:00")) as date, COUNT(*) as count')
            ->where('status', 'success')
            ->whereBetween('access_time', [
                Carbon::now()->subDays(7)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Access statistics
        $successfulAccess = DoorAccessLog::where('status', 'success')->count();
        $failedAccess = DoorAccessLog::where('status', 'failed')->count();
        $unauthorizedAccess = DoorAccessLog::where('status', 'unauthorized')->count();

        // User statistics
        $adminUsers = User::where('is_admin', true)->count();
        $regularUsers = User::where('is_admin', false)->count();
        $activeUsers = User::where('status', 'active')->count();
        $delayUsers = User::where('status', 'delay')->count();

        // Stats customizable (kept for backward compatibility)
        $stats = DashboardStat::all();

        return view('dashboard.index', compact(
            'stats',
            'totalUsers',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'recentAccessLogs',
            'dailyAccess',
            'successfulAccess',
            'failedAccess',
            'unauthorizedAccess',
            'adminUsers',
            'regularUsers',
            'activeUsers',
            'delayUsers'
        ));
    }
}
