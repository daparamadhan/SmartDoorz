@extends('layouts.app')

@section('content')

<div class="mb-5">
    <h1 class="text-3xl font-bold">Edit Pengguna</h1>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Nama</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('name') border-red-500 @enderror"
                value="{{ old('name', $user->name) }}"
                required
            >
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('email') border-red-500 @enderror"
                value="{{ old('email', $user->email) }}"
                required
            >
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Password (Kosongkan jika tidak ingin diubah)</label>
            <input 
                type="password" 
                name="password" 
                id="password"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation"
                class="w-full px-4 py-2 border border-gray-300 rounded"
            >
        </div>

        <div class="flex gap-2">
            <button 
                type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Perbarui
            </button>
            <a 
                href="{{ route('users.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
            >
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
