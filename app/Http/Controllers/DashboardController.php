<?php

namespace App\Http\Controllers;

use App\Models\DashboardStat;
use App\Models\Room;
use App\Models\User;
use App\Models\DoorAccessLog;
use Illuminate\Support\Facades\DB;

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
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        
        // Recent access logs with relationships
        $recentAccessLogs = DoorAccessLog::with('room', 'user')
            ->orderBy('access_time', 'desc')
            ->limit(15)
            ->get();

        // Daily access chart data (last 7 days)
        $dailyAccess = DoorAccessLog::selectRaw('DATE(access_time) as date, COUNT(*) as count')
            ->where('status', 'success')
            ->whereBetween('access_time', [
                now()->subDays(7),
                now()
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Access statistics
        $successfulAccess = DoorAccessLog::where('status', 'success')->count();
        $failedAccess = DoorAccessLog::where('status', 'failed')->count();
        $unauthorizedAccess = DoorAccessLog::where('status', 'unauthorized')->count();

        // Stats customizable (kept for backward compatibility)
        $stats = DashboardStat::all();

        return view('dashboard.index', compact(
            'stats',
            'totalUsers',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'recentAccessLogs',
            'dailyAccess',
            'successfulAccess',
            'failedAccess',
            'unauthorizedAccess'
        ));
    }
}
