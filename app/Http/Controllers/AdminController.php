<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function roomAllocation()
    {
        $pendingUsers = User::where('status', 'pending')->orderBy('created_at', 'asc')->get();
        $rooms = Room::with('user')->orderBy('room_number', 'asc')->get();
        
        return view('admin.room-allocation', compact('pendingUsers', 'rooms'));
    }
    
    public function allocateRoom(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id'
        ]);
        
        $user = User::findOrFail($request->user_id);
        $room = Room::findOrFail($request->room_id);
        
        // Check if room is available
        if ($room->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sudah terisi'
            ]);
        }
        
        // Assign room to user
        $room->user_id = $user->id;
        $room->save();
        
        // Update user status and rental period
        $user->status = 'active';
        $user->rental_start = Carbon::now();
        $user->rental_end = Carbon::now()->addMonth();
        $user->rental_months = 1;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil dialokasikan'
        ]);
    }
}