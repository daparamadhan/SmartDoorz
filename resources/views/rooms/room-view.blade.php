@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row h-full gap-6">
    <!-- Room Grid Section -->
    <div class="flex-1 bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">View Ruangan</h1>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm">Kosong</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm">Terisi</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm">Delay</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-500 rounded mr-2"></div>
                    <span class="text-sm">Maintenance</span>
                </div>
            </div>
        </div>

        <!-- Room Grid Layout -->
        <div class="flex flex-wrap gap-4 justify-start">
            @foreach($rooms as $room)
                @php
                    $roomStatus = $room->getRoomStatus();
                    $statusColor = $room->getStatusColor();
                    $statusText = match($roomStatus) {
                        'available' => 'Kosong',
                        'occupied' => 'Terisi',
                        'delay' => 'Delay',
                        'maintenance' => 'Maintenance',
                        default => 'Unknown'
                    };
                @endphp
                
                <div class="relative group">
                    <div class="w-20 h-14 {{ $statusColor }} rounded-md flex items-center justify-center text-white font-bold text-sm cursor-pointer hover:opacity-80 hover:scale-105 transition-all duration-200 shadow-md border-2 border-white"
                         onclick="showRoomDetail('{{ $room->id }}', '{{ $room->room_number }}', '{{ $statusText }}', '{{ $room->user ? $room->user->name : 'Tidak ada' }}', '{{ $room->notes ?? 'Tidak ada catatan' }}')">
                        {{ $room->room_number }}
                    </div>
                    
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                        {{ $room->room_number }} - {{ $statusText }}
                        @if($room->user)
                            <br>{{ $room->user->name }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Statistics Sidebar -->
    <div class="w-full lg:w-80 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Statistik Ruangan</h2>
        
        <div class="grid grid-cols-2 lg:grid-cols-1 gap-4">
            <div class="bg-green-100 p-4 rounded-lg hover:bg-green-200 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $rooms->filter(fn($room) => $room->getRoomStatus() === 'available')->count() }}</div>
                        <div class="text-sm text-green-700">Kamar Kosong</div>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-100 p-4 rounded-lg hover:bg-red-200 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-red-600">{{ $rooms->filter(fn($room) => $room->getRoomStatus() === 'occupied')->count() }}</div>
                        <div class="text-sm text-red-700">Kamar Terisi</div>
                    </div>
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-100 p-4 rounded-lg hover:bg-blue-200 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $rooms->filter(fn($room) => $room->getRoomStatus() === 'delay')->count() }}</div>
                        <div class="text-sm text-blue-700">Kamar Delay</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-gray-600">{{ $rooms->filter(fn($room) => $room->getRoomStatus() === 'maintenance')->count() }}</div>
                        <div class="text-sm text-gray-700">Maintenance</div>
                    </div>
                    <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Summary -->
        <div class="mt-6 p-4 bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg">
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-800">{{ $rooms->count() }}</div>
                <div class="text-sm text-gray-600">Total Ruangan</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('rooms.index') }}" class="block w-full bg-blue-500 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                    Kelola Ruangan
                </a>
                <a href="{{ route('rooms.create') }}" class="block w-full bg-green-500 text-white text-center py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                    Tambah Ruangan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Room Detail Modal -->
<div id="roomModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Detail Ruangan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-50 p-3 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Ruangan</label>
                <p id="modalRoomNumber" class="text-gray-900 font-semibold"></p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <p id="modalStatus" class="text-gray-900"></p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Penghuni</label>
                <p id="modalUser" class="text-gray-900"></p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <p id="modalNotes" class="text-gray-900"></p>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                Tutup
            </button>
            <a id="modalEditLink" href="#" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                Edit
            </a>
        </div>
    </div>
</div>

<script>
function showRoomDetail(roomId, roomNumber, status, user, notes) {
    document.getElementById('modalRoomNumber').textContent = roomNumber;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalUser').textContent = user;
    document.getElementById('modalNotes').textContent = notes;
    document.getElementById('modalEditLink').href = `/rooms/${roomId}/edit`;
    
    const modal = document.getElementById('roomModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animate modal appearance
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('roomModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animate modal disappearance
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('roomModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection