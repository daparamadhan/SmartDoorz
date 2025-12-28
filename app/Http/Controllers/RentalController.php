<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    
    public function extend(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'payment_reference' => 'required|string|max:255',
            'payment_notes' => 'nullable|string|max:500'
        ]);
        
        $user = auth()->user();
        
        // Validasi user harus punya ruangan
        if ($user->rooms->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki ruangan. Hubungi admin untuk mendapatkan ruangan.'
            ]);
        }
        
        // Calculate new end date
        if ($user->rental_end && $user->rental_end->isFuture()) {
            // Extend from current end date
            $newEndDate = $user->rental_end->addMonth();
        } else {
            // Start from now if expired or no rental
            $newEndDate = Carbon::now()->addMonth();
            $user->rental_start = Carbon::now();
        }
        
        $user->rental_end = $newEndDate;
        $user->rental_months += 1;
        $user->save();
        
        // Log payment
        \Log::info('Rental extended', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'payment_notes' => $request->payment_notes,
            'new_end_date' => $newEndDate,
            'amount' => 500000
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Sewa berhasil diperpanjang',
            'new_end_date' => $newEndDate->format('d M Y H:i')
        ]);
    }
}