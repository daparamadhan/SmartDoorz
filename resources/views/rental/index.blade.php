@extends('layouts.app')

@section('title', 'Perpanjang Sewa - SmartDoorz')

@section('content')
<div class="container mx-auto py-6">
    <div class="mb-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg shadow-lg p-8 text-white">
        <h1 class="text-4xl font-bold mb-2">💰 Perpanjangan Sewa Ruangan</h1>
        <p class="text-green-100 text-lg">Kelola masa sewa ruangan Anda per bulan</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Perpanjangan Sewa Ruangan</h2>
                    <p class="text-gray-600 mt-2">Kelola masa sewa ruangan Anda per bulan</p>
                </div>

                @if(auth()->user()->rental_status == 'pending')
                <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">⏳ Status: Menunggu Persetujuan</h3>
                    <p class="text-yellow-700">Akun Anda sedang dalam tahap pending. Admin akan segera mengalokasikan ruangan untuk Anda.</p>
                </div>
                @elseif(auth()->user()->rental_status == 'expired')
                <div class="mb-6 p-6 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Sewa Berakhir</h3>
                    <p class="text-red-700 mb-4">Masa sewa Anda telah berakhir pada {{ auth()->user()->rental_end->format('d M Y') }}.</p>
                    <button onclick="showPaymentModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        💳 Perpanjang Sewa Sekarang
                    </button>
                </div>
                @else
                <div class="mb-6 p-6 bg-green-50 border border-green-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">✅ Sewa Aktif</h3>
                    <p class="text-green-700 mb-2">Masa sewa berakhir: <strong>{{ auth()->user()->rental_end->format('d M Y H:i') }}</strong></p>
                    <p class="text-sm text-green-600 mb-4">Sisa waktu: {{ auth()->user()->rental_end->diffForHumans() }}</p>
                    <button onclick="showPaymentModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        💳 Perpanjang Sewa (+1 Bulan)
                    </button>
                </div>
                @endif

                @if(auth()->user()->rental_status == 'active')
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔓 Akses Ruangan</h3>
                    <div class="mb-6 text-center">
                        <button id="openCameraBtn" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                            📷 Buka Kamera Scanner
                        </button>
                    </div>

                    <form id="scanForm" action="{{ route('scanner.scan') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="qr_code" class="block text-sm font-semibold text-gray-700 mb-3">
                                🔍 Masukkan Kode QR
                            </label>
                            <input type="text" name="qr_code" id="qr_code" autocomplete="off"
                                class="w-full px-4 py-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-lg font-mono"
                                placeholder="Gunakan barcode scanner atau ketik kode..." autofocus>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">🚪 Ruangan Saya</h3>
                @if(auth()->user()->rooms->count() > 0)
                <div class="space-y-3">
                    @foreach(auth()->user()->rooms as $room)
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="font-semibold text-gray-900">Ruangan {{ $room->room_number }}</p>
                        <p class="text-xs text-gray-600 mt-1 font-mono break-all">{{ $room->qr_code }}</p>
                        @if(auth()->user()->rental_status == 'active')
                        <button type="button" onclick="testScan('{{ $room->qr_code }}')" 
                            class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-3 rounded text-sm transition">
                            Test Scan
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                    <p class="text-yellow-900 text-sm">⚠️ Belum ada ruangan yang ditugaskan.</p>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">💰 Harga Sewa</h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">Rp 500.000</div>
                    <div class="text-sm text-gray-600">per bulan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">💳 Pembayaran Sewa</h3>
            <p class="text-gray-600">Perpanjang sewa ruangan untuk 1 bulan</p>
        </div>

        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-700">Sewa 1 Bulan</span>
                <span class="font-bold text-green-600">Rp 500.000</span>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-3">Metode Pembayaran</h4>
            <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-blue-900">QRIS</p>
                            <p class="text-sm text-blue-700">Scan QR untuk bayar</p>
                        </div>
                    </div>
                    <div class="text-blue-600">✓</div>
                </div>
            </div>
        </div>

        <div class="flex space-x-3">
            <button onclick="processPayment()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                Bayar Sekarang
            </button>
            <button onclick="closePaymentModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-lg transition">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- QRIS Modal -->
<div id="qrisModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 text-center">
        <h3 class="text-xl font-bold text-gray-900 mb-4">📱 Scan QRIS</h3>
        <div class="mb-6">
            <div class="w-48 h-48 mx-auto bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"/>
                    </svg>
                    <p class="text-sm text-gray-500">QR Code QRIS</p>
                </div>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-4">Scan dengan aplikasi mobile banking atau e-wallet Anda</p>
        <div class="text-lg font-bold text-green-600 mb-6">Rp 500.000</div>
        <button onclick="confirmPayment()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition">
            Saya Sudah Bayar
        </button>
        <button onclick="closeQrisModal()" class="w-full mt-2 text-gray-500 hover:text-gray-700 transition">
            Batal
        </button>
    </div>
</div>

<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
<script>
    const qrInput = document.getElementById('qr_code');
    const openCameraBtn = document.getElementById('openCameraBtn');
    
    let stream = null;
    let codeReader = null;
    let isScanning = false;

    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }

    function processPayment() {
        closePaymentModal();
        document.getElementById('qrisModal').classList.remove('hidden');
        document.getElementById('qrisModal').classList.add('flex');
    }

    function closeQrisModal() {
        document.getElementById('qrisModal').classList.add('hidden');
        document.getElementById('qrisModal').classList.remove('flex');
    }

    async function confirmPayment() {
        try {
            const response = await fetch('/rental/extend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ payment_method: 'qris' })
            });

            const data = await response.json();
            
            if (data.success) {
                closeQrisModal();
                alert('✅ Pembayaran berhasil! Sewa diperpanjang sampai ' + data.new_end_date);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }

    if (qrInput) {
        qrInput.addEventListener('keypress', async function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                await submitForm();
                this.value = '';
                this.focus();
            }
        });
    }

    async function testScan(qrCode) {
        if (qrInput) {
            qrInput.value = qrCode;
            await submitForm();
            qrInput.value = '';
            qrInput.focus();
        }
    }

    async function submitForm() {
        try {
            const qrCode = qrInput.value.trim();
            if (!qrCode) {
                alert('Masukkan kode QR terlebih dahulu');
                return;
            }

            const response = await fetch('{{ route("scanner.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_code: qrCode })
            });

            const data = await response.json();
            
            if (data.status === 'success') {
                alert('✅ Akses Berhasil! ' + data.message);
            } else {
                alert('❌ Akses Gagal! ' + data.message);
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }
</script>

@endsection