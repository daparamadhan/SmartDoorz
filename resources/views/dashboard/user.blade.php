@extends('layouts.app')

@section('title', 'User Dashboard - SmartDoorz')

@section('content')
<div class="container mx-auto py-6">
    <!-- Welcome Section -->
    <div class="mb-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
        <h1 class="text-4xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-blue-100 text-lg">Kelola akses ruangan Anda dengan mudah menggunakan scanner QR</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Scanner Section (Main) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Perpanjangan Sewa Ruangan</h2>
                    <p class="text-gray-600 mt-2">Kelola masa sewa ruangan Anda per bulan</p>
                </div>

                <!-- Rental Status -->
                @if(auth()->user()->rental_status == 'pending')
                <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">⏳ Status: Menunggu Persetujuan</h3>
                    <p class="text-yellow-700">Akun Anda sedang dalam tahap pending. Admin akan segera mengalokasikan ruangan untuk Anda.</p>
                </div>
                @elseif(auth()->user()->rental_status == 'expired')
                <div class="mb-6 p-6 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Sewa Berakhir</h3>
                    <p class="text-red-700 mb-4">Masa sewa Anda telah berakhir pada {{ auth()->user()->rental_end->format('d M Y') }}.</p>
                    <button onclick="extendRental()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                        Perpanjang Sewa
                    </button>
                </div>
                @else
                <div class="mb-6 p-6 bg-green-50 border border-green-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">✅ Sewa Aktif</h3>
                    <p class="text-green-700 mb-2">Masa sewa berakhir: <strong>{{ auth()->user()->rental_end->format('d M Y H:i') }}</strong></p>
                    <p class="text-sm text-green-600 mb-4">Sisa waktu: {{ auth()->user()->rental_end->diffForHumans() }}</p>
                    <button onclick="extendRental()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        Perpanjang Sewa (+1 Bulan)
                    </button>
                </div>
                @endif

                <!-- QR Scanner for Access -->
                @if(auth()->user()->rental_status == 'active')
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
                        <p class="text-sm text-gray-500 mt-3">
                            💡 Tekan <kbd class="bg-gray-200 px-2 py-1 rounded">Enter</kbd> untuk scan
                        </p>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <!-- My Rooms Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">🚪 Ruangan Saya</h3>
                @if(auth()->user()->rooms->count() > 0)
                <div class="space-y-3">
                    @foreach(auth()->user()->rooms as $room)
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="font-semibold text-gray-900">Ruangan {{ $room->room_number }}</p>
                        <p class="text-xs text-gray-600 mt-1 font-mono break-all">{{ $room->qr_code }}</p>
                        <button type="button" onclick="testScan('{{ $room->qr_code }}')" 
                            class="mt-3 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-3 rounded text-sm transition">
                            Test Scan
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                    <p class="text-yellow-900 text-sm">⚠️ Belum ada ruangan yang ditugaskan. Hubungi admin.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div id="cameraModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pindai QR Code</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <video id="qrVideo" class="w-full rounded-lg bg-gray-100" style="height: 300px;"></video>
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-4">Arahkan kamera ke QR Code untuk akses</p>
            <div class="flex space-x-3">
                <button id="startScan" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Mulai Scan
                </button>
                <button id="stopScan" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Berhenti
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk hasil scan -->
<div id="resultModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full text-center">
        <div id="resultIcon" class="inline-block p-4 bg-gray-100 rounded-full mb-4">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h3 id="resultTitle" class="text-2xl font-bold text-gray-900 mb-3">Loading...</h3>
        <p id="resultMessage" class="text-gray-600 mb-6 text-lg"></p>
        <button type="button" onclick="closeModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition w-full">
            Tutup
        </button>
    </div>
</div>

<script>
    const qrInput = document.getElementById('qr_code');
    const resultModal = document.getElementById('resultModal');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMessage = document.getElementById('resultMessage');
    
    // Camera elements
    const openCameraBtn = document.getElementById('openCameraBtn');
    const cameraModal = document.getElementById('cameraModal');
    const closeModalBtn = document.getElementById('closeModal');
    const startScan = document.getElementById('startScan');
    const stopScan = document.getElementById('stopScan');
    const qrVideo = document.getElementById('qrVideo');
    
    let stream = null;

    // Open camera modal
    openCameraBtn.addEventListener('click', () => {
        cameraModal.classList.remove('hidden');
        cameraModal.classList.add('flex');
    });

    // Close camera modal
    closeModalBtn.addEventListener('click', () => {
        stopCamera();
        cameraModal.classList.add('hidden');
        cameraModal.classList.remove('flex');
    });

    // Start camera and scanning
    startScan.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment' } 
            });
            qrVideo.srcObject = stream;
            qrVideo.play();
            
            // Start barcode scanning
            startBarcodeScanning();
        } catch (err) {
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
            console.error('Error accessing camera:', err);
        }
    });

    // Barcode scanning with ZXing
    let codeReader = null;
    let isScanning = false;

    function startBarcodeScanning() {
        if (isScanning) return;
        
        codeReader = new ZXing.BrowserQRCodeReader();
        isScanning = true;
        
        codeReader.decodeFromVideoDevice(null, qrVideo, (result, err) => {
            if (result) {
                // QR Code detected
                stopCamera();
                cameraModal.classList.add('hidden');
                cameraModal.classList.remove('flex');
                
                // Process the scanned code
                qrInput.value = result.text;
                setTimeout(() => {
                    submitForm();
                }, 500);
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error('Scanning error:', err);
            }
        });
    }

    // Stop camera
    stopScan.addEventListener('click', () => {
        stopCamera();
    });

    function stopCamera() {
        if (codeReader) {
            codeReader.reset();
            codeReader = null;
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            qrVideo.srcObject = null;
        }
        isScanning = false;
    }

    // Handle Enter key for scan
    qrInput.addEventListener('keypress', async function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            await submitForm();
            this.value = '';
            this.focus();
        }
    });

    async function testScan(qrCode) {
        qrInput.value = qrCode;
        await submitForm();
        qrInput.value = '';
        qrInput.focus();
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
            showResult(data);
        } catch (error) {
            showResult({
                status: 'failed',
                message: 'Terjadi kesalahan: ' + error.message,
                action: 'error'
            });
        }
    }

    function showResult(data) {
        let icon = '';
        let bgColor = '';
        let titleClass = '';
        let title = '';

        if (data.status === 'success') {
            icon = '<svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-green-100';
            titleClass = 'text-green-600';
            title = 'Akses Berhasil! 🎉';
        } else if (data.status === 'unauthorized') {
            icon = '<svg class="w-16 h-16 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-yellow-100';
            titleClass = 'text-yellow-600';
            title = 'Akses Ditolak ⚠️';
        } else {
            icon = '<svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-red-100';
            titleClass = 'text-red-600';
            title = 'Kode Tidak Valid ❌';
        }

        resultIcon.innerHTML = `<div class="${bgColor} inline-block p-4 rounded-full">${icon}</div>`;
        resultTitle.textContent = title;
        resultTitle.className = `text-2xl font-bold mb-3 ${titleClass}`;
        resultMessage.textContent = data.message;
        resultModal.classList.remove('hidden');

        // Auto-close successful access modal after 2.5 seconds
        if (data.status === 'success') {
            setTimeout(() => {
                closeModal();
            }, 2500);
        }
    }

    function closeModal() {
        resultModal.classList.add('hidden');
        qrInput.focus();
    }

    // Focus on input when page loads
    window.addEventListener('load', function() {
        if (qrInput) qrInput.focus();
    });

    // Extend rental function
    async function extendRental() {
        if (!confirm('Perpanjang sewa ruangan untuk 1 bulan lagi?')) return;
        
        try {
            const response = await fetch('/rental/extend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            
            if (data.success) {
                alert('✅ Sewa berhasil diperpanjang sampai ' + data.new_end_date);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }
</script>

<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>

@endsection