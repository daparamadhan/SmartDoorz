@extends('layouts.app')

@section('content')

<div class="mb-5">
    <h1 class="text-3xl font-bold">Tambah Stat Dashboard</h1>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <form method="POST" action="{{ route('dashboard.store') }}">
        @csrf

        <div class="mb-4">
            <label for="label" class="block text-gray-700 font-bold mb-2">Label</label>
            <input 
                type="text" 
                name="label" 
                id="label"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('label') border-red-500 @enderror"
                value="{{ old('label') }}"
                required
            >
            @error('label')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="value" class="block text-gray-700 font-bold mb-2">Nilai</label>
            <input 
                type="text" 
                name="value" 
                id="value"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('value') border-red-500 @enderror"
                value="{{ old('value') }}"
                required
            >
            @error('value')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="icon" class="block text-gray-700 font-bold mb-2">Icon (opsional)</label>
            <input 
                type="text" 
                name="icon" 
                id="icon"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('icon') border-red-500 @enderror"
                value="{{ old('icon') }}"
                placeholder="Contoh: fas fa-chart-bar"
            >
            @error('icon')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button 
                type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Simpan
            </button>
            <a 
                href="{{ route('dashboard.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
            >
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
