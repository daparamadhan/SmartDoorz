<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Helpers\QrCodeGenerator;
use Illuminate\Http\Request;

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
}
