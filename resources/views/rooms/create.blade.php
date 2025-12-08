@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6">
        <a href="{{ route('rooms.index') }}" class="text-blue-600 hover:underline">← Kembali ke Manajemen Ruangan</a>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Tambah Ruangan Baru</h1>

        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <p class="font-semibold mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            <!-- Nomor Ruangan -->
            <div class="mb-6">
                <label for="room_number" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="room_number" id="room_number" placeholder="Contoh: 101, A-01, dll"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('room_number') border-red-500 @enderror"
                    value="{{ old('room_number') }}">
                @error('room_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror">
                    <option value="">-- Pilih Status --</option>
                    <option value="available" @selected(old('status') === 'available')>Tersedia</option>
                    <option value="maintenance" @selected(old('status') === 'maintenance')>Pemeliharaan</option>
                </select>
                @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pengguna (Opsional) -->
            <div class="mb-6">
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tugaskan Pengguna <span class="text-gray-500">(Opsional)</span>
                </label>
                <select name="user_id" id="user_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">-- Tidak Ada Pengguna --</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') === (string)$user->id)>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan <span class="text-gray-500">(Opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="4" placeholder="Tambahkan catatan tentang ruangan ini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                    Buat Ruangan
                </button>
                <a href="{{ route('rooms.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
