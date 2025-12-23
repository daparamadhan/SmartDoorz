@extends('layouts.app')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Ruangan</h1>
            <p class="text-gray-600 mt-1">Kelola ruangan dan kode QR pengguna</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            + Tambah Ruangan
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600 text-sm font-semibold">Total Ruangan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $rooms->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600 text-sm font-semibold">Ditempati</p>
            <p class="text-2xl font-bold text-green-600">{{ $rooms->where('status', 'occupied')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600 text-sm font-semibold">Tersedia</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $rooms->where('status', 'available')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-600 text-sm font-semibold">Pemeliharaan</p>
            <p class="text-2xl font-bold text-red-600">{{ $rooms->where('status', 'maintenance')->count() }}</p>
        </div>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($rooms as $room)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $room->room_number }}</h3>
                    <p class="text-sm text-gray-600">{{ $room->notes }}</p>
                </div>
                <span class="inline-block px-3 py-1 text-white text-sm font-semibold rounded-full
                    @if ($room->status === 'available') bg-yellow-500
                    @elseif ($room->status === 'occupied') bg-green-500
                    @else bg-red-500 @endif">
                    @if ($room->status === 'available') Tersedia
                    @elseif ($room->status === 'occupied') Ditempati
                    @else Pemeliharaan @endif
                </span>
            </div>

            <!-- User Info -->
            <div class="mb-4 pb-4 border-b border-gray-200">
                @if ($room->user)
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-semibold">Pengguna:</span> {{ $room->user->name }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Email:</span> {{ $room->user->email }}
                </p>
                @else
                <p class="text-sm text-gray-500 italic">Tidak ada pengguna yang ditugaskan</p>
                @endif
            </div>

            <!-- QR Code -->
            <div class="mb-4 pb-4 border-b border-gray-200 text-center">
                <img src="{{ $room->getQrImageUrl() }}" alt="QR Code" class="w-32 h-32 mx-auto mb-2">
                <p class="text-xs text-gray-600 truncate">{{ $room->qr_code }}</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <a href="{{ route('rooms.show', $room) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-3 rounded transition text-center">
                    Lihat Detail
                </a>
                <a href="{{ route('rooms.edit', $room) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold py-2 px-3 rounded transition text-center">
                    Edit
                </a>
                <div class="flex-1 relative">
                    <button onclick="toggleDropdown('dropdown-{{ $room->id }}')" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-3 rounded transition flex items-center justify-center">
                        📥 Download QR ▼
                    </button>
                    <div id="dropdown-{{ $room->id }}" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-b shadow-lg z-10">
                        <a href="{{ route('rooms.download-qr', $room) }}?format=png" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Download PNG
                        </a>
                        <a href="{{ route('rooms.download-qr', $room) }}?format=jpg" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Download JPG
                        </a>
                    </div>
                </div>
                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-3 rounded transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-600 text-lg">Belum ada ruangan. <a href="{{ route('rooms.create') }}" class="text-blue-600 hover:underline">Buat sekarang</a></p>
        </div>
        @endforelse
    </div>
</div>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== dropdownId) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleDropdown"]')) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});
</script>
@endsection
