<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SmartDoorz - Login' }}</title>
    <link rel="icon" type="image/svg+xml" 
          href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%234F46E5'%3E%3Cpath d='M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z'/%3E%3Cpath d='M12 7c-1.1 0-2 .9-2 2v2c0 .55.45 1 1 1h2c.55 0 1-.45 1-1V9c0-1.1-.9-2-2-2z' fill='white'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .clean-bg { background: #f8fafc; }
        .card-clean {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="clean-bg min-h-screen p-4 relative">

    <div class="fixed top-6 right-6 z-50">
        <a href="{{ route('welcome') }}" 
           class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-all duration-300 font-medium">
            Kembali
        </a>
    </div>

    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <div class="card-clean p-8 rounded-2xl">
                {{ $slot }}
            </div>

            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm">&copy; 2026 SmartDoorz.</p>
            </div>
        </div>
    </div>

</body>
</html>
