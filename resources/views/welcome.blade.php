<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartDoorz</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%234F46E5'%3E%3Cpath d='M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z'/%3E%3Cpath d='M12 7c-1.1 0-2 .9-2 2v2c0 .55.45 1 1 1h2c.55 0 1-.45 1-1V9c0-1.1-.9-2-2-2z' fill='white'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .clean-bg {
            background: #f8fafc;
        }
        .card-clean {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card-clean:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="clean-bg min-h-screen flex flex-col">
    <!-- Header -->
    <header class="p-6">
        <div class="flex items-center justify-between max-w-6xl mx-auto">
            <!-- Logo -->
            <div>
                <h1 class="text-2xl font-bold text-red-600">
                    SmartDoorz
                </h1>
                <p class="text-gray-600 text-sm">Sistem Kunci Pintu Digital</p>
            </div>

            <!-- Login Button (Top Right) -->
            <a href="{{ route('login') }}" 
               class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-all duration-300 font-medium">
                Masuk
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-6">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Hero Section -->
            <div class="mb-12">
                <div class="floating-animation mb-8">
                    <div class="w-32 h-32 mx-auto bg-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h-4.01M12 12V8M12 21h4a2 2 0 002-2V5a2 2 0 00-2 2v14a2 2 0 002 2h4z"></path>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-5xl font-bold text-gray-800 mb-6 leading-tight">
                    Akses Pintu dengan<br>
                    <span class="text-red-600">QR Code</span>
                </h2>
                
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Sistem keamanan modern yang menggantikan kunci fisik dengan teknologi QR Code. 
                    Aman, praktis, dan mudah dikelola.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-6">
                <!-- Camera Scan Button (Primary) -->
                <button id="scanButton" 
                        class="w-full max-w-md mx-auto bg-red-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pindai QR Code</span>
                </button>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-5xl mx-auto">
                    <div class="card-clean p-6 rounded-lg text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Keamanan Tinggi</h3>
                        <p class="text-gray-600 text-sm">QR Code unik untuk setiap pengguna dengan enkripsi yang aman</p>
                    </div>

                    <div class="card-clean p-6 rounded-lg text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Real-Time</h3>
                        <p class="text-gray-600 text-sm">Monitoring akses secara langsung dengan log aktivitas lengkap</p>
                    </div>

                    <div class="card-clean p-6 rounded-lg text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Mudah Dikelola</h3>
                        <p class="text-gray-600 text-sm">Dashboard admin untuk mengelola pengguna dan ruangan</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between text-gray-500 text-sm">
                <div class="flex items-center space-x-4">
                    <span>&copy; 2026 SmartDoorz</span>
                </div>
            </div>
        </div>
    </footer>

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
                    <button id="startScan" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Mulai Scan
                    </button>
                    <button id="stopScan" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Berhenti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Camera Modal functionality
        const scanButton = document.getElementById('scanButton');
        const cameraModal = document.getElementById('cameraModal');
        const closeModal = document.getElementById('closeModal');
        const startScan = document.getElementById('startScan');
        const stopScan = document.getElementById('stopScan');
        const qrVideo = document.getElementById('qrVideo');
        
        let stream = null;

        scanButton.addEventListener('click', () => {
            cameraModal.classList.remove('hidden');
            cameraModal.classList.add('flex');
        });

        closeModal.addEventListener('click', () => {
            stopCamera();
            cameraModal.classList.add('hidden');
            cameraModal.classList.remove('flex');
        });

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
                    setTimeout(() => {
                        processQRCode(result.text);
                    }, 500);
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error('Scanning error:', err);
                }
            });
        }

        async function processQRCode(qrCode) {
            try {
                const response = await fetch('/api/scanner/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ qr_code: qrCode })
                });

                const data = await response.json();
                showResult(data);
            } catch (error) {
                showResult({
                    status: 'error',
                    message: 'Terjadi kesalahan: ' + error.message
                });
            }
        }

        function showResult(data) {
            if (data.status === 'success') {
                alert('🎉 Akses Berhasil!\n\n' + data.message);
            } else if (data.status === 'unauthorized') {
                alert('⚠️ Akses Ditolak!\n\n' + data.message);
            } else {
                alert('❌ Kode Tidak Valid!\n\n' + data.message);
            }
        }

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

        // Close modal when clicking outside
        cameraModal.addEventListener('click', (e) => {
            if (e.target === cameraModal) {
                stopCamera();
                cameraModal.classList.add('hidden');
                cameraModal.classList.remove('flex');
            }
        });
    </script>
    
    <script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
</body>
</html>