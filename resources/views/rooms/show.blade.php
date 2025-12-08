@extends('layouts.app')

@section('title', 'Detail Ruangan - ' . $room->room_number)

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <a href="{{ route('rooms.index') }}" class="text-blue-600 hover:underline">← Kembali ke Manajemen Ruangan</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Room Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $room->room_number }}</h1>
                        <p class="text-gray-600 mt-1">{{ $room->notes }}</p>
                    </div>
                    <span class="inline-block px-4 py-2 text-white text-sm font-semibold rounded-full
                        @if ($room->status === 'available') bg-yellow-500
                        @elseif ($room->status === 'occupied') bg-green-500
                        @else bg-red-500 @endif">
                        @if ($room->status === 'available') Tersedia
                        @elseif ($room->status === 'occupied') Ditempati
                        @else Pemeliharaan @endif
                    </span>
                </div>

                <!-- Room Details -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor Ruangan</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $room->room_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <p class="text-lg font-semibold text-gray-900 capitalize">{{ $room->status }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Dibuat Tanggal</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $room->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Diperbarui Tanggal</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $room->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- User Assignment -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Pengguna yang Ditugaskan</p>
                    @if ($room->user)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $room->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $room->user->email }}</p>
                        </div>
                        <form action="{{ route('rooms.update', $room) }}" method="POST" onsubmit="return confirm('Lepaskan pengguna dari ruangan ini?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="user_id" value="">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-1 px-3 rounded transition">
                                Lepaskan
                            </button>
                        </form>
                    </div>
                    @else
                    <p class="text-gray-500 italic">Tidak ada pengguna yang ditugaskan ke ruangan ini</p>
                    @endif
                </div>

                <!-- QR Code Section -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-600 mb-4">Kode QR Ruangan</p>
                    <div class="text-center">
                        <img src="{{ $room->getQrImageUrl() }}" alt="QR Code" class="w-48 h-48 mx-auto mb-4 p-2 bg-white rounded">
                        <p class="text-xs text-gray-600 mb-3 break-all">{{ $room->qr_code }}</p>
                        <a href="{{ route('rooms.print-qr', $room) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded transition">
                            Cetak QR Code
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6 pt-6 border-t">
                    <a href="{{ route('rooms.edit', $room) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                        Edit Ruangan
                    </a>
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition">
                            Hapus Ruangan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Access Logs Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Akses Terbaru</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse ($room->doorAccessLogs()->latest()->limit(10)->get() as $log)
                    <div class="pb-3 border-b border-gray-200 last:border-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-block w-2 h-2 rounded-full
                                @if ($log->status === 'success') bg-green-500
                                @elseif ($log->status === 'unauthorized') bg-yellow-500
                                @else bg-red-500 @endif"></span>
                            <p class="text-xs font-semibold capitalize
                                @if ($log->status === 'success') text-green-600
                                @elseif ($log->status === 'unauthorized') text-yellow-600
                                @else text-red-600 @endif">
                                {{ $log->status === 'success' ? 'Berhasil' : ($log->status === 'unauthorized' ? 'Tidak Sah' : 'Gagal') }}
                            </p>
                        </div>
                        @if ($log->user)
                        <p class="text-xs text-gray-600">{{ $log->user->name }}</p>
                        @endif
                        <p class="text-xs text-gray-500">{{ $log->access_time->format('d/m H:i') }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 italic">Tidak ada riwayat akses</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
