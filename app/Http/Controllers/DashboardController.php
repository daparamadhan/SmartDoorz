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
        // Jika user biasa, tampilkan user dashboard
        if (!auth()->user()->is_admin) {
            return view('dashboard.user');
        }

        // Jika admin, redirect ke home (dashboard utama)
        return redirect()->route('home');
    }
}
