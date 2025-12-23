<!DOCTYPE html>
<html>
<head>
    <title>QR Scanner Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">QR Scanner Test</h1>
        
        @auth
            <p class="mb-4">Logged in as: <strong>{{ auth()->user()->name }}</strong></p>
            
            <form id="testForm">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">QR Code:</label>
                    <input type="text" id="qrInput" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter QR code">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                    Test Scan
                </button>
            </form>
            
            <div id="result" class="mt-4 p-4 rounded-lg hidden"></div>
            
            <div class="mt-6">
                <h3 class="font-bold mb-2">Your Rooms:</h3>
                @foreach(auth()->user()->rooms as $room)
                    <div class="mb-2 p-2 bg-gray-50 rounded">
                        <p><strong>Room {{ $room->room_number }}</strong></p>
                        <p class="text-xs text-gray-600">{{ $room->qr_code }}</p>
                        <button onclick="testQr('{{ $room->qr_code }}')" class="mt-1 text-xs bg-green-500 text-white px-2 py-1 rounded">
                            Test This QR
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p>Please login first</p>
            <a href="/login" class="text-blue-500">Login</a>
        @endauth
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const qrCode = document.getElementById('qrInput').value;
            await testScan(qrCode);
        });

        function testQr(qrCode) {
            document.getElementById('qrInput').value = qrCode;
            testScan(qrCode);
        }

        async function testScan(qrCode) {
            const resultDiv = document.getElementById('result');
            
            try {
                resultDiv.className = 'mt-4 p-4 rounded-lg bg-blue-100 text-blue-800';
                resultDiv.textContent = 'Testing...';
                resultDiv.classList.remove('hidden');

                const response = await fetch('/scanner/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ qr_code: qrCode })
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                const data = await response.json();
                console.log('Response data:', data);

                if (data.status === 'success') {
                    resultDiv.className = 'mt-4 p-4 rounded-lg bg-green-100 text-green-800';
                    resultDiv.textContent = '✅ ' + data.message;
                } else if (data.status === 'unauthorized') {
                    resultDiv.className = 'mt-4 p-4 rounded-lg bg-yellow-100 text-yellow-800';
                    resultDiv.textContent = '⚠️ ' + data.message;
                } else {
                    resultDiv.className = 'mt-4 p-4 rounded-lg bg-red-100 text-red-800';
                    resultDiv.textContent = '❌ ' + data.message;
                }
            } catch (error) {
                console.error('Error:', error);
                resultDiv.className = 'mt-4 p-4 rounded-lg bg-red-100 text-red-800';
                resultDiv.textContent = '❌ Error: ' + error.message;
            }
        }
    </script>
</body>
</html>