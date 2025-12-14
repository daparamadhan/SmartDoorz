<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\DoorAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index');
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $room = Room::where('qr_code', $validated['qr_code'])->first();

        if (!$room) {
            return response()->json([
                'status' => 'failed',
                'message' => 'QR Code tidak ditemukan. Hubungi admin SmartDoorz',
                'action' => 'error'
            ]);
        }

        $currentUser = auth()->user();
        $isAuthorized = $room->user_id === $currentUser->id;

        // Log akses
        DoorAccessLog::create([
            'room_id' => $room->id,
            'user_id' => $currentUser->id,
            'qr_code' => $validated['qr_code'],
            'status' => $isAuthorized ? 'success' : 'unauthorized',
            'access_time' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if (!$isAuthorized) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Anda tidak memiliki akses ke kamar ini. Hubungi admin SmartDoorz',
                'action' => 'error'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pintu Terbuka! Selamat datang di kamar ' . $room->room_number,
            'action' => 'open_door',
            'room' => [
                'id' => $room->id,
                'room_number' => $room->room_number,
            ]
        ]);
    }

    public function scanPublic(Request $request)
    {
        try {
            $validated = $request->validate([
                'qr_code' => 'required|string',
            ]);

            $room = Room::where('qr_code', $validated['qr_code'])->first();

            if (!$room) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'QR Code tidak ditemukan atau tidak valid'
                ]);
            }

            // Log akses tanpa user_id (akses publik)
            DoorAccessLog::create([
                'room_id' => $room->id,
                'user_id' => null,
                'qr_code' => $validated['qr_code'],
                'status' => 'success',
                'access_time' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pintu berhasil dibuka! Ruangan ' . $room->room_number
            ]);
        } catch (\Exception $e) {
            Log::error('Scanner error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }
}
