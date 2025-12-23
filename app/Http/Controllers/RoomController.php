<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Helpers\QrCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('user')->paginate(12);
        $available = Room::where('status', 'available')->count();
        $occupied = Room::where('status', 'occupied')->count();
        $maintenance = Room::where('status', 'maintenance')->count();
        
        return view('rooms.index', compact('rooms', 'available', 'occupied', 'maintenance'));
    }

    public function create()
    {
        $users = User::all();
        return view('rooms.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'status' => 'required|in:available,maintenance',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['qr_code'] = QrCodeGenerator::generateQrCode();

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function show(Room $room)
    {
        $room->load('user', 'doorAccessLogs');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $users = User::all();
        return view('rooms.edit', compact('room', 'users'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'status' => 'required|in:available,occupied,maintenance',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        // Auto-update status based on user assignment
        if ($validated['user_id']) {
            $validated['status'] = 'occupied';
            
            // Update user status if needed
            $user = User::find($validated['user_id']);
            if ($user && $user->status === 'pending') {
                $user->update([
                    'status' => 'active',
                    'rental_start' => Carbon::now(),
                    'rental_end' => Carbon::now()->addMonth(),
                    'rental_months' => 1
                ]);
            }
        } else {
            $validated['status'] = 'available';
        }

        $room->update($validated);

        return redirect()->route('rooms.show', $room)->with('success', 'Kamar berhasil diperbarui');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus');
    }

    public function printQrCode(Room $room)
    {
        return view('rooms.print-qr', compact('room'));
    }

    public function downloadQrCode(Room $room, Request $request)
    {
        try {
            $format = $request->get('format', 'png'); // default PNG
            $format = in_array($format, ['png', 'jpg']) ? $format : 'png';
            
            // Get QR code image URL with higher resolution for download
            $imageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&format={$format}&data=" . urlencode($room->qr_code);
            
            // Fetch image content from external API
            $response = Http::get($imageUrl);
            
            if (!$response->successful()) {
                return back()->with('error', 'Gagal mengunduh QR Code');
            }
            
            $imageContent = $response->body();
            $filename = 'qr-code-room-' . $room->room_number . '.' . $format;
            $contentType = $format === 'jpg' ? 'image/jpeg' : 'image/png';
            
            // Return download response
            return response($imageContent)
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh QR Code');
        }
    }

    public function roomView()
    {
        $rooms = Room::with('user')->orderBy('room_number')->get();
        return view('rooms.room-view', compact('rooms'));
    }
}
