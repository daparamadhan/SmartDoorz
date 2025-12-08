@extends('layouts.app')

@section('title', 'Cetak QR Code - ' . $room->room_number)

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:underline">← Kembali</a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
            🖨️ Cetak
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-12 print:shadow-none">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">SmartDoorz</h1>
            <p class="text-gray-600">Sistem Manajemen Akses Ruangan</p>
        </div>

        <!-- QR Code Container -->
        <div class="max-w-sm mx-auto">
            <!-- Room Info -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Ruangan {{ $room->room_number }}</h2>
                <p class="text-gray-600 text-sm">{{ $room->notes }}</p>
            </div>

            <!-- QR Code Image -->
            <div class="bg-white p-8 border-4 border-gray-300 rounded-lg mb-6 text-center">
                <img src="{{ $room->getQrImageUrl() }}" alt="QR Code" class="w-64 h-64 mx-auto">
            </div>

            <!-- QR Code Text -->
            <div class="text-center mb-6 pb-6 border-b-2 border-gray-300">
                <p class="text-xs text-gray-600 break-all font-mono">{{ $room->qr_code }}</p>
            </div>

            <!-- Instructions -->
            <div class="bg-gray-50 rounded-lg p-4 text-center text-sm text-gray-700 leading-relaxed">
                <p class="font-semibold mb-2">Cara Menggunakan:</p>
                <p>Scan kode QR ini dengan perangkat seluler Anda untuk membuka akses ke ruangan ini.</p>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 pt-6 border-t-2 border-gray-300 text-xs text-gray-500">
                <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            button {
                display: none;
            }
        }
    </style>
</div>
@endsection
