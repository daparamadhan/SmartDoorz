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

        <!-- Rental Management Section -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🏠 Manajemen Sewa</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="rental_start" class="block text-gray-700 font-bold mb-2">Tanggal Mulai Sewa</label>
                    <input 
                        type="datetime-local" 
                        name="rental_start" 
                        id="rental_start"
                        class="w-full px-4 py-2 border border-gray-300 rounded"
                        value="{{ old('rental_start', $user->rental_start ? $user->rental_start->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
                <div>
                    <label for="rental_end" class="block text-gray-700 font-bold mb-2">Tanggal Berakhir Sewa</label>
                    <input 
                        type="datetime-local" 
                        name="rental_end" 
                        id="rental_end"
                        class="w-full px-4 py-2 border border-gray-300 rounded"
                        value="{{ old('rental_end', $user->rental_end ? $user->rental_end->format('Y-m-d\TH:i') : '') }}"
                    >
                </div>
            </div>
            
            <div class="mb-4">
                <label for="rental_months" class="block text-gray-700 font-bold mb-2">Total Bulan Sewa</label>
                <input 
                    type="number" 
                    name="rental_months" 
                    id="rental_months"
                    class="w-full px-4 py-2 border border-gray-300 rounded"
                    value="{{ old('rental_months', $user->rental_months ?? 0) }}"
                    min="0"
                >
            </div>
            
            @if($user->rental_end)
            <div class="p-3 {{ $user->isRentalExpired() ? 'bg-red-100 border-red-200' : 'bg-green-100 border-green-200' }} border rounded">
                <p class="text-sm font-medium {{ $user->isRentalExpired() ? 'text-red-800' : 'text-green-800' }}">
                    Status: {{ $user->isRentalExpired() ? '❌ Expired' : '✅ Aktif' }}
                </p>
                <p class="text-xs {{ $user->isRentalExpired() ? 'text-red-600' : 'text-green-600' }} mt-1">
                    Berakhir: {{ $user->rental_end->format('d M Y H:i') }} ({{ $user->rental_end->diffForHumans() }})
                </p>
            </div>
            @endif
            
            <div class="mt-4 flex gap-2">
                <button type="button" onclick="extendRental(1)" class="bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600">
                    +1 Bulan
                </button>
                <button type="button" onclick="extendRental(3)" class="bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">
                    +3 Bulan
                </button>
                <button type="button" onclick="extendRental(6)" class="bg-purple-500 text-white px-3 py-2 rounded text-sm hover:bg-purple-600">
                    +6 Bulan
                </button>
            </div>
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

<script>
function extendRental(months) {
    const rentalEndInput = document.getElementById('rental_end');
    const rentalMonthsInput = document.getElementById('rental_months');
    const rentalStartInput = document.getElementById('rental_start');
    
    let currentEnd = rentalEndInput.value ? new Date(rentalEndInput.value) : new Date();
    let currentStart = rentalStartInput.value ? new Date(rentalStartInput.value) : new Date();
    
    // If no start date, set to now
    if (!rentalStartInput.value) {
        rentalStartInput.value = new Date().toISOString().slice(0, 16);
        currentStart = new Date();
    }
    
    // If current end is in the past, start from now
    if (currentEnd < new Date()) {
        currentEnd = new Date();
    }
    
    // Add months
    currentEnd.setMonth(currentEnd.getMonth() + months);
    
    // Update inputs
    rentalEndInput.value = currentEnd.toISOString().slice(0, 16);
    rentalMonthsInput.value = parseInt(rentalMonthsInput.value || 0) + months;
    
    // Show confirmation
    alert(`Sewa diperpanjang ${months} bulan. Berakhir: ${currentEnd.toLocaleDateString('id-ID')}`);
}
</script>

@endsection
