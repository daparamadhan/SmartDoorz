<!DOCTYPE html>
<html>
<head>
    <title>Test Scanner Expired</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Scanner - Expired Rental</h1>
    
    <div>
        <label>QR Code:</label>
        <input type="text" id="qrCode" placeholder="Masukkan QR Code" value="ROOM001_QR_12345">
        <button onclick="testScan()">Test Scan</button>
    </div>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>

    <script>
        function testScan() {
            const qrCode = document.getElementById('qrCode').value;
            const resultDiv = document.getElementById('result');
            
            fetch('/scanner/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    qr_code: qrCode
                })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.innerHTML = `
                    <h3>Result:</h3>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Message:</strong> ${data.message}</p>
                    <p><strong>Action:</strong> ${data.action || 'N/A'}</p>
                `;
                
                if (data.status === 'expired') {
                    resultDiv.style.backgroundColor = '#ffebee';
                    resultDiv.style.color = '#c62828';
                } else if (data.status === 'success') {
                    resultDiv.style.backgroundColor = '#e8f5e8';
                    resultDiv.style.color = '#2e7d32';
                } else {
                    resultDiv.style.backgroundColor = '#fff3e0';
                    resultDiv.style.color = '#ef6c00';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `<p style="color: red;">Error: ${error}</p>`;
            });
        }
    </script>
</body>
</html>