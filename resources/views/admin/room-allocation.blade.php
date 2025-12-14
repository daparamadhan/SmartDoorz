@extends('layouts.app')

@section('title', 'Alokasi Ruangan - Admin')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">🏠 Alokasi Ruangan</h1>
        <p class="text-gray-600 mt-2">Kelola user pending dan alokasikan ruangan seperti sistem kursi bioskop</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Pending Users -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">👥 User Pending</h2>
            <div id="pendingUsers" class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($pendingUsers as $user)
                <div class="user-card p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 transition" 
                     data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">Daftar: {{ $user->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-yellow-500">⏳</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <p>✅ Tidak ada user pending</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Room Layout -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">🏢 Layout Ruangan</h2>
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span>Tersedia</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span>Terisi</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span>Dipilih</span>
                    </div>
                </div>
            </div>

            <div id="selectedUser" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                <p class="text-blue-900"><strong>User Terpilih:</strong> <span id="selectedUserName"></span></p>
                <p class="text-sm text-blue-700">Pilih ruangan yang tersedia untuk user ini</p>
            </div>

            <!-- Room Grid -->
            <div class="grid grid-cols-5 gap-3" id="roomGrid">
                @foreach($rooms as $room)
                <div class="room-seat relative" 
                     data-room-id="{{ $room->id }}" 
                     data-room-number="{{ $room->room_number }}">
                    <div class="w-16 h-16 rounded-lg border-2 flex flex-col items-center justify-center cursor-pointer transition-all
                        {{ $room->user_id ? 'bg-red-500 border-red-600 text-white' : 'bg-green-500 border-green-600 text-white hover:bg-green-600' }}">
                        <div class="text-xs font-bold">{{ $room->room_number }}</div>
                        @if($room->user_id)
                        <div class="text-xs">👤</div>
                        @endif
                    </div>
                    @if($room->user_id)
                    <div class="absolute -bottom-6 left-0 right-0 text-xs text-center text-gray-600 truncate">
                        {{ $room->user->name ?? 'Unknown' }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex space-x-3">
                <button id="assignBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition disabled:opacity-50" disabled>
                    Alokasikan Ruangan
                </button>
                <button id="cancelBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedUser = null;
let selectedRoom = null;

// User selection
document.querySelectorAll('.user-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.user-card').forEach(c => c.classList.remove('bg-blue-100', 'border-blue-300'));
        this.classList.add('bg-blue-100', 'border-blue-300');
        selectedUser = {
            id: this.dataset.userId,
            name: this.dataset.userName
        };
        document.getElementById('selectedUser').classList.remove('hidden');
        document.getElementById('selectedUserName').textContent = selectedUser.name;
        resetRoomSelection();
    });
});

// Room selection
document.querySelectorAll('.room-seat').forEach(seat => {
    seat.addEventListener('click', function() {
        if (!selectedUser) {
            alert('Pilih user terlebih dahulu');
            return;
        }
        
        const roomDiv = this.querySelector('div');
        if (roomDiv.classList.contains('bg-red-500')) {
            alert('Ruangan sudah terisi');
            return;
        }
        
        document.querySelectorAll('.room-seat div').forEach(div => {
            if (div.classList.contains('bg-blue-500')) {
                div.classList.remove('bg-blue-500', 'border-blue-600');
                div.classList.add('bg-green-500', 'border-green-600');
            }
        });
        
        roomDiv.classList.remove('bg-green-500', 'border-green-600');
        roomDiv.classList.add('bg-blue-500', 'border-blue-600');
        
        selectedRoom = {
            id: this.dataset.roomId,
            number: this.dataset.roomNumber
        };
        
        document.getElementById('assignBtn').disabled = false;
    });
});

// Assign room
document.getElementById('assignBtn').addEventListener('click', async function() {
    if (!selectedUser || !selectedRoom) return;
    
    if (!confirm(`Alokasikan Ruangan ${selectedRoom.number} untuk ${selectedUser.name}?`)) return;
    
    try {
        const response = await fetch('/admin/allocate-room', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: selectedUser.id,
                room_id: selectedRoom.id
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ Ruangan berhasil dialokasikan!');
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    } catch (error) {
        alert('❌ Terjadi kesalahan: ' + error.message);
    }
});

function resetRoomSelection() {
    document.querySelectorAll('.room-seat div').forEach(div => {
        if (div.classList.contains('bg-blue-500')) {
            div.classList.remove('bg-blue-500', 'border-blue-600');
            div.classList.add('bg-green-500', 'border-green-600');
        }
    });
    selectedRoom = null;
    document.getElementById('assignBtn').disabled = true;
}
</script>

@endsection