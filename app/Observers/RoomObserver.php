<?php

namespace App\Observers;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class RoomObserver
{
    public function updated(Room $room)
    {
        // When a room is assigned to a user
        if ($room->user_id && $room->isDirty('user_id')) {
            $user = User::find($room->user_id);
            
            if ($user && $user->status === 'pending') {
                $user->update([
                    'status' => 'active',
                    'rental_start' => Carbon::now(),
                    'rental_end' => Carbon::now()->addMonth(),
                    'rental_months' => 1
                ]);
            }
            
            // Update room status to occupied
            if ($room->status !== 'maintenance') {
                $room->status = 'occupied';
                $room->saveQuietly(); // Prevent infinite loop
            }
        }
        
        // When a room is unassigned from a user
        if (!$room->user_id && $room->isDirty('user_id')) {
            // Update room status to available if not maintenance
            if ($room->status !== 'maintenance') {
                $room->status = 'available';
                $room->saveQuietly(); // Prevent infinite loop
            }
        }
    }
}