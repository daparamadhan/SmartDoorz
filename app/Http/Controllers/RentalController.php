<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        return view('rental.index');
    }
    
    public function extend(Request $request)
    {
        $user = auth()->user();
        
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
        
        // Log payment (optional)
        \Log::info('Rental extended', [
            'user_id' => $user->id,
            'payment_method' => $request->payment_method ?? 'qris',
            'new_end_date' => $newEndDate
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Sewa berhasil diperpanjang',
            'new_end_date' => $newEndDate->format('d M Y H:i')
        ]);
    }
}