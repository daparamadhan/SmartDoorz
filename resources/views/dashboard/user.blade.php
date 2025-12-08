@extends('layouts.app')

@section('title', 'Pemindai QR Ruangan')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header Info -->
        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-blue-900">Cara Menggunakan Scanner</h3>
                    <div class="mt-2 text-sm text-blue-800 space-y-1">
                        <p><strong>✓ Opsi 1:</strong> Gunakan kamera untuk scan kode QR ruangan</p>
                        <p><strong>✓ Opsi 2:</strong> Gunakan barcode scanner fisik</p>
                        <p><strong>✓ Opsi 3:</strong> Masukkan kode QR secara manual</p>
                        <p><strong>ℹ️ Catatan:</strong> Anda hanya bisa membuka ruangan yang ditugaskan ke akun Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Pemindai QR Ruangan</h1>
                <p class="text-gray-600 text-lg mt-2">Pindai kode QR untuk membuka akses ke ruangan Anda</p>
            </div>

            <!-- QR Input Form: Camera + Manual -->
            <form id="scanForm" action="{{ route('scanner.scan') }}" method="POST" class="mb-8">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">📷 Pemindai Kamera</label>
                    
                    <!-- Camera Selection -->
                    <div class="mb-3">
                        <label for="cameraSelect" class="block text-xs text-gray-600 mb-2">Pilih Kamera:</label>
                        <select id="cameraSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Memuat kamera...</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <button id="startCameraBtn" type="button" onclick="startScanner()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition">Mulai Kamera</button>
                        <button id="stopCameraBtn" type="button" onclick="stopScanner()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded hidden transition">Hentikan Kamera</button>
                        <p id="cameraHint" class="text-sm text-gray-500">Pilih kamera yang tersedia di perangkat Anda.</p>
                    </div>

                    <div id="qr-reader" style="width:100%; max-width:480px; margin-bottom:12px; display:none;"></div>
                </div>

                <div class="mb-6">
                    <label for="qr_code" class="block text-sm font-semibold text-gray-700 mb-3">
                        🔍 Kode QR Ruangan (Manual)
                    </label>
                    <input type="text" name="qr_code" id="qr_code" autocomplete="off"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-lg"
                        placeholder="Arahkan barcode scanner ke kode QR atau ketik kode secara manual...">
                    <p class="text-xs text-gray-500 mt-3">
                        💡 Input ini akan menerima scan dari barcode scanner, kamera, atau masukan manual. Tekan Enter setelah memasukkan kode.
                    </p>
                </div>
            </form>

            <!-- Ruangan yang Tersedia -->
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-4">📋 Ruangan Saya</h2>
                @if(auth()->user()->rooms)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-900 font-semibold mb-3">Ruangan yang ditugaskan ke akun Anda:</p>
                        <div class="space-y-2">
                            @foreach(auth()->user()->rooms as $room)
                            <div class="flex items-center justify-between bg-white p-3 rounded border border-green-200">
                                <div>
                                    <p class="font-semibold text-gray-900">Ruangan {{ $room->room_number }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $room->qr_code }}</p>
                                </div>
                                <span class="text-2xl">🚪</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-900">⚠️ Belum ada ruangan yang ditugaskan ke akun Anda. Hubungi admin.</p>
                    </div>
                @endif
            </div>

            <!-- Status Badges Explanation -->
            <div class="space-y-3 mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Status Akses</h2>
                
                <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900">✓ Akses Berhasil</p>
                        <p class="text-xs text-green-800 mt-1">Pintu akan terbuka. Anda adalah pemilik ruangan ini.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-yellow-900">⚠️ Akses Ditolak</p>
                        <p class="text-xs text-yellow-800 mt-1">Ruangan ini bukan milik Anda. Admin akan diberitahu tentang percobaan akses ini.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-900">✗ Kode Tidak Valid</p>
                        <p class="text-xs text-red-800 mt-1">Kode QR tidak ditemukan. Pastikan Anda memindai kode yang benar.</p>
                    </div>
                </div>
            </div>

            <!-- Test QR Codes -->
            @if(optional(auth()->user())->rooms && optional(auth()->user())->rooms->count() > 0)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-purple-900 mb-4">🧪 Test Scan</h3>
                <p class="text-sm text-purple-800 mb-4">Klik tombol di bawah untuk test scan kode QR ruangan Anda:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(optional(auth()->user())->rooms ?? [] as $room)
                    <button type="button" onclick="testScan('{{ $room->qr_code }}')" 
                        class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Test Scan: Ruangan {{ $room->room_number }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal untuk hasil scan -->
    <div id="resultModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full mx-4 text-center">
            <div id="resultIcon" class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 id="resultTitle" class="text-xl font-bold text-gray-900 mb-2">Loading...</h3>
            <p id="resultMessage" class="text-gray-600 mb-6">Memproses kode QR Anda...</p>
            <button type="button" onclick="closeModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition w-full">
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.3/dist/html5-qrcode.min.js"></script>
<script>
    const form = document.getElementById('scanForm');
    const qrInput = document.getElementById('qr_code');
    const resultModal = document.getElementById('resultModal');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMessage = document.getElementById('resultMessage');
    const qrReaderEl = document.getElementById('qr-reader');
    const startCameraBtn = document.getElementById('startCameraBtn');
    const stopCameraBtn = document.getElementById('stopCameraBtn');
    const cameraSelect = document.getElementById('cameraSelect');

    let html5QrScanner = null;
    let isScannerRunning = false;
    let availableCameras = [];

    // Load available cameras on page load
    window.addEventListener('load', function() {
        qrInput.focus();
        loadCameras();
    });

    async function loadCameras() {
        try {
            const cameras = await Html5Qrcode.getCameras();
            availableCameras = cameras;
            
            cameraSelect.innerHTML = '';
            
            if (cameras && cameras.length > 0) {
                cameras.forEach((camera, index) => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.text = camera.label || `Kamera ${index + 1}`;
                    cameraSelect.appendChild(option);
                });
                
                // Select first camera by default
                cameraSelect.selectedIndex = 0;
            } else {
                cameraSelect.innerHTML = '<option value="">Tidak ada kamera tersedia</option>';
                startCameraBtn.disabled = true;
            }
        } catch (err) {
            console.error('Error loading cameras:', err);
            cameraSelect.innerHTML = '<option value="">Error memuat kamera</option>';
            startCameraBtn.disabled = true;
        }
    }

    function startScanner() {
        if (isScannerRunning) return;
        
        const selectedCameraId = cameraSelect.value;
        if (!selectedCameraId) {
            alert('Pilih kamera terlebih dahulu');
            return;
        }

        if (!html5QrScanner) {
            html5QrScanner = new Html5Qrcode("qr-reader");
        }

        qrReaderEl.style.display = 'block';
        startCameraBtn.classList.add('hidden');
        stopCameraBtn.classList.remove('hidden');
        cameraSelect.disabled = true;

        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrScanner.start(
            selectedCameraId, // Use specific camera ID
            config,
            async (decodedText, decodedResult) => {
                // on success: stop continuous scanning and submit once
                qrInput.value = decodedText;
                // stop immediately to prevent duplicate reads
                try { stopScanner(); } catch (e) { /* ignore */ }
                await submitForm();
            },
            (errorMessage) => {
                // ignore per-frame decode errors
            }
        ).then(() => {
            isScannerRunning = true;
        }).catch((err) => {
            console.error('Unable to start scanner', err);
            qrReaderEl.style.display = 'none';
            startCameraBtn.classList.remove('hidden');
            stopCameraBtn.classList.add('hidden');
            cameraSelect.disabled = false;
            alert('Tidak dapat mengakses kamera. Periksa izin browser dan pastikan kamera tidak digunakan aplikasi lain.');
        });
    }

    function stopScanner() {
        if (!isScannerRunning || !html5QrScanner) return;
        html5QrScanner.stop().then(() => {
            html5QrScanner.clear();
            qrReaderEl.style.display = 'none';
            startCameraBtn.classList.remove('hidden');
            stopCameraBtn.classList.add('hidden');
            cameraSelect.disabled = false;
            isScannerRunning = false;
        }).catch(err => {
            console.error('Error stopping scanner', err);
        });
    }

    // Auto-submit when QR code is detected or Enter is pressed
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

        if (data.status === 'success') {
            icon = '<svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-green-100';
        } else if (data.status === 'unauthorized') {
            icon = '<svg class="w-12 h-12 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-yellow-100';
        } else {
            icon = '<svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-red-100';
        }

        resultIcon.innerHTML = `<div class="${bgColor} inline-block p-4 rounded-full">${icon}</div>`;
        resultTitle.textContent = data.status === 'success' ? '✓ Akses Berhasil!' : 
                                  data.status === 'unauthorized' ? '⚠️ Akses Ditolak' : '✗ Kode Tidak Valid';
        
        if (data.status !== 'success') {
            const contactNote = '\n\nJika perlu, silakan hubungi petugas/administrator.';
            resultMessage.textContent = data.message + contactNote;
        } else {
            resultMessage.textContent = data.message;
        }
        resultModal.classList.remove('hidden');

        // Auto-close successful access modal
        if (data.status === 'success') {
            setTimeout(closeModal, 2500);
        }
    }

    function closeModal() {
        resultModal.classList.add('hidden');
        qrInput.focus();
    }
</script>

@endsection
@extends('layouts.app')

@section('title', 'Pemindai QR Ruangan')

@section('content')
<div class="container mx-auto py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header Info -->
        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-blue-900">Cara Menggunakan Scanner</h3>
                    <div class="mt-2 text-sm text-blue-800 space-y-1">
                        <p><strong>✓ Opsi 1:</strong> Gunakan kamera untuk scan kode QR ruangan</p>
                        <p><strong>✓ Opsi 2:</strong> Gunakan barcode scanner fisik</p>
                        <p><strong>✓ Opsi 3:</strong> Masukkan kode QR secara manual</p>
                        <p><strong>ℹ️ Catatan:</strong> Anda hanya bisa membuka ruangan yang ditugaskan ke akun Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Pemindai QR Ruangan</h1>
                <p class="text-gray-600 text-lg mt-2">Pindai kode QR untuk membuka akses ke ruangan Anda</p>
            </div>

            <!-- QR Input Form: Camera + Manual -->
            <form id="scanForm" action="{{ route('scanner.scan') }}" method="POST" class="mb-8">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">📷 Pemindai Kamera</label>
                    
                    <!-- Camera Selection -->
                    <div class="mb-3">
                        <label for="cameraSelect" class="block text-xs text-gray-600 mb-2">Pilih Kamera:</label>
                        <select id="cameraSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Memuat kamera...</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <button id="startCameraBtn" type="button" onclick="startScanner()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition">Mulai Kamera</button>
                        <button id="stopCameraBtn" type="button" onclick="stopScanner()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded hidden transition">Hentikan Kamera</button>
                        <p id="cameraHint" class="text-sm text-gray-500">Pilih kamera yang tersedia di perangkat Anda.</p>
                    </div>

                    <div id="qr-reader" style="width:100%; max-width:480px; margin-bottom:12px; display:none;"></div>
                </div>

                <div class="mb-6">
                    <label for="qr_code" class="block text-sm font-semibold text-gray-700 mb-3">
                        🔍 Kode QR Ruangan (Manual)
                    </label>
                    <input type="text" name="qr_code" id="qr_code" autocomplete="off"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-lg"
                        placeholder="Arahkan barcode scanner ke kode QR atau ketik kode secara manual...">
                    <p class="text-xs text-gray-500 mt-3">
                        💡 Input ini akan menerima scan dari barcode scanner, kamera, atau masukan manual. Tekan Enter setelah memasukkan kode.
                    </p>
                </div>
            </form>

            <!-- Ruangan yang Tersedia -->
            <div class="mb-8 pb-8 border-b-2 border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-4">📋 Ruangan Saya</h2>
                @if(auth()->user()->rooms)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-900 font-semibold mb-3">Ruangan yang ditugaskan ke akun Anda:</p>
                        <div class="space-y-2">
                            @foreach(auth()->user()->rooms as $room)
                            <div class="flex items-center justify-between bg-white p-3 rounded border border-green-200">
                                <div>
                                    <p class="font-semibold text-gray-900">Ruangan {{ $room->room_number }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $room->qr_code }}</p>
                                </div>
                                <span class="text-2xl">🚪</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-900">⚠️ Belum ada ruangan yang ditugaskan ke akun Anda. Hubungi admin.</p>
                    </div>
                @endif
            </div>

            <!-- Status Badges Explanation -->
            <div class="space-y-3 mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Status Akses</h2>
                
                <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900">✓ Akses Berhasil</p>
                        <p class="text-xs text-green-800 mt-1">Pintu akan terbuka. Anda adalah pemilik ruangan ini.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-yellow-900">⚠️ Akses Ditolak</p>
                        <p class="text-xs text-yellow-800 mt-1">Ruangan ini bukan milik Anda. Admin akan diberitahu tentang percobaan akses ini.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex-shrink-0">
                        <div class="inline-block p-2 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-900">✗ Kode Tidak Valid</p>
                        <p class="text-xs text-red-800 mt-1">Kode QR tidak ditemukan. Pastikan Anda memindai kode yang benar.</p>
                    </div>
                </div>
            </div>

            <!-- Test QR Codes -->
            @if(optional(auth()->user())->rooms && optional(auth()->user())->rooms->count() > 0)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-bold text-purple-900 mb-4">🧪 Test Scan</h3>
                <p class="text-sm text-purple-800 mb-4">Klik tombol di bawah untuk test scan kode QR ruangan Anda:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(optional(auth()->user())->rooms ?? [] as $room)
                    <button type="button" onclick="testScan('{{ $room->qr_code }}')" 
                        class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Test Scan: Ruangan {{ $room->room_number }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal untuk hasil scan -->
    <div id="resultModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full mx-4 text-center">
            <div id="resultIcon" class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 id="resultTitle" class="text-xl font-bold text-gray-900 mb-2">Loading...</h3>
            <p id="resultMessage" class="text-gray-600 mb-6">Memproses kode QR Anda...</p>
            <button type="button" onclick="closeModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition w-full">
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.3/dist/html5-qrcode.min.js"></script>
<script>
    const form = document.getElementById('scanForm');
    const qrInput = document.getElementById('qr_code');
    const resultModal = document.getElementById('resultModal');
    const resultIcon = document.getElementById('resultIcon');
    const resultTitle = document.getElementById('resultTitle');
    const resultMessage = document.getElementById('resultMessage');
    const qrReaderEl = document.getElementById('qr-reader');
    const startCameraBtn = document.getElementById('startCameraBtn');
    const stopCameraBtn = document.getElementById('stopCameraBtn');
    const cameraSelect = document.getElementById('cameraSelect');

    let html5QrScanner = null;
    let isScannerRunning = false;
    let availableCameras = [];

    // Load available cameras on page load
    window.addEventListener('load', function() {
        qrInput.focus();
        loadCameras();
    });

    async function loadCameras() {
        try {
            const cameras = await Html5Qrcode.getCameras();
            availableCameras = cameras;
            
            cameraSelect.innerHTML = '';
            
            if (cameras && cameras.length > 0) {
                cameras.forEach((camera, index) => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.text = camera.label || `Kamera ${index + 1}`;
                    cameraSelect.appendChild(option);
                });
                
                // Select first camera by default
                cameraSelect.selectedIndex = 0;
            } else {
                cameraSelect.innerHTML = '<option value="">Tidak ada kamera tersedia</option>';
                startCameraBtn.disabled = true;
            }
        } catch (err) {
            console.error('Error loading cameras:', err);
            cameraSelect.innerHTML = '<option value="">Error memuat kamera</option>';
            startCameraBtn.disabled = true;
        }
    }

    function startScanner() {
        if (isScannerRunning) return;
        
        const selectedCameraId = cameraSelect.value;
        if (!selectedCameraId) {
            alert('Pilih kamera terlebih dahulu');
            return;
        }

        if (!html5QrScanner) {
            html5QrScanner = new Html5Qrcode("qr-reader");
        }

        qrReaderEl.style.display = 'block';
        startCameraBtn.classList.add('hidden');
        stopCameraBtn.classList.remove('hidden');
        cameraSelect.disabled = true;

        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrScanner.start(
            selectedCameraId, // Use specific camera ID
            config,
            async (decodedText, decodedResult) => {
                // on success: stop continuous scanning and submit once
                qrInput.value = decodedText;
                // stop immediately to prevent duplicate reads
                try { stopScanner(); } catch (e) { /* ignore */ }
                await submitForm();
            },
            (errorMessage) => {
                // ignore per-frame decode errors
            }
        ).then(() => {
            isScannerRunning = true;
        }).catch((err) => {
            console.error('Unable to start scanner', err);
            qrReaderEl.style.display = 'none';
            startCameraBtn.classList.remove('hidden');
            stopCameraBtn.classList.add('hidden');
            cameraSelect.disabled = false;
            alert('Tidak dapat mengakses kamera. Periksa izin browser dan pastikan kamera tidak digunakan aplikasi lain.');
        });
    }

    function stopScanner() {
        if (!isScannerRunning || !html5QrScanner) return;
        html5QrScanner.stop().then(() => {
            html5QrScanner.clear();
            qrReaderEl.style.display = 'none';
            startCameraBtn.classList.remove('hidden');
            stopCameraBtn.classList.add('hidden');
            cameraSelect.disabled = false;
            isScannerRunning = false;
        }).catch(err => {
            console.error('Error stopping scanner', err);
        });
    }

    // Auto-submit when QR code is detected or Enter is pressed
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

        if (data.status === 'success') {
            icon = '<svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-green-100';
        } else if (data.status === 'unauthorized') {
            icon = '<svg class="w-12 h-12 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-yellow-100';
        } else {
            icon = '<svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-red-100';
        }

        resultIcon.innerHTML = `<div class="${bgColor} inline-block p-4 rounded-full">${icon}</div>`;
        resultTitle.textContent = data.status === 'success' ? '✓ Akses Berhasil!' : 
                                  data.status === 'unauthorized' ? '⚠️ Akses Ditolak' : '✗ Kode Tidak Valid';
        
        if (data.status !== 'success') {
            const contactNote = '\n\nJika perlu, silakan hubungi petugas/administrator.';
            resultMessage.textContent = data.message + contactNote;
        } else {
            resultMessage.textContent = data.message;
        }
        resultModal.classList.remove('hidden');

        // Auto-close successful access modal
        if (data.status === 'success') {
            setTimeout(closeModal, 2500);
        }
    }

    function closeModal() {
        resultModal.classList.add('hidden');
        qrInput.focus();
    }
</script>

@endsection
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
                    <h2 class="text-3xl font-bold text-gray-900">Pemindai QR Ruangan</h2>
                    <p class="text-gray-600 mt-2">Pindai kode QR untuk membuka akses ke ruangan Anda</p>
                </div>

                <!-- QR Input Form -->
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

                <!-- Quick Instructions -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-900"><strong>💡 Cara Menggunakan:</strong></p>
                    <ul class="text-sm text-blue-800 mt-2 space-y-1">
                        <li>✓ Arahkan barcode scanner ke kode QR ruangan</li>
                        <li>✓ Atau ketik kode QR secara manual</li>
                        <li>✓ Tekan Enter untuk membuka pintu</li>
                    </ul>
                </div>
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

            <!-- Status Guide -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Status Akses</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">✓</span>
                        <div>
                            <p class="font-semibold text-green-700">Berhasil</p>
                            <p class="text-xs text-gray-600">Pintu terbuka</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <p class="font-semibold text-yellow-700">Ditolak</p>
                            <p class="text-xs text-gray-600">Bukan ruangan Anda</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">✗</span>
                        <div>
                            <p class="font-semibold text-red-700">Tidak Valid</p>
                            <p class="text-xs text-gray-600">Kode tidak ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Info Akun</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Nama</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900 break-all">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block mt-4 text-center bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-2 rounded transition">
                        Edit Profil
                    </a>
                </div>
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

        if (data.status === 'success') {
            icon = '<svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-green-100';
            titleClass = 'text-green-600';
        } else if (data.status === 'unauthorized') {
            icon = '<svg class="w-16 h-16 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-yellow-100';
            titleClass = 'text-yellow-600';
        } else {
            icon = '<svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            bgColor = 'bg-red-100';
            titleClass = 'text-red-600';
        }

        resultIcon.innerHTML = `<div class="${bgColor} inline-block p-4 rounded-full">${icon}</div>`;
        resultTitle.textContent = data.status === 'success' ? '✓ Akses Berhasil!' : 
                                  data.status === 'unauthorized' ? '⚠️ Akses Ditolak' : '✗ Kode Tidak Valid';
        resultTitle.className = `text-2xl font-bold mb-3 ${titleClass}`;
        resultMessage.textContent = data.message;
        resultModal.classList.remove('hidden');

        // Auto-close successful access modal
        if (data.status === 'success') {
            setTimeout(closeModal, 3000);
        }
    }

    function closeModal() {
        resultModal.classList.add('hidden');
        qrInput.focus();
    }

    // Focus on input when page loads
    window.addEventListener('load', function() {
        qrInput.focus();
    });
</script>

@endsection
