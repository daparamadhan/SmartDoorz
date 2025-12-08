@extends('layouts.app')

@section('content')

<div class="mb-5">
    <h1 class="text-3xl font-bold">Tambah Barcode</h1>
</div>

<div class="bg-white p-6 rounded-lg shadow max-w-2xl">
    <form method="POST" action="{{ route('barcode.store') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="block text-gray-700 font-bold mb-2">Kode Barcode</label>
            <input 
                type="text" 
                name="code" 
                id="code"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('code') border-red-500 @enderror"
                value="{{ old('code') }}"
                placeholder="Kode otomatis dibuat"
                readonly
                required
            >
            @error('code')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="product_name" class="block text-gray-700 font-bold mb-2">Nama Produk</label>
            <input 
                type="text" 
                name="product_name" 
                id="product_name"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('product_name') border-red-500 @enderror"
                value="{{ old('product_name') }}"
                required
            >
            @error('product_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Jumlah</label>
            <input 
                type="number" 
                name="quantity" 
                id="quantity"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('quantity') border-red-500 @enderror"
                value="{{ old('quantity', 1) }}"
                min="1"
                required
            >
            @error('quantity')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 font-bold mb-2">User</label>
            <select 
                name="user_id" 
                id="user_id"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('user_id') border-red-500 @enderror"
                required
            >
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-gray-700 font-bold mb-2">Catatan</label>
            <textarea 
                name="notes" 
                id="notes"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('notes') border-red-500 @enderror"
                rows="3"
            >{{ old('notes') }}</textarea>
            @error('notes')
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
                href="{{ route('barcode.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
            >
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        if (!codeInput.value) {
            codeInput.value = 'BC-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9).toUpperCase();
        }
    });
</script>

@endsection
