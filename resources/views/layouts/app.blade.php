<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite('resources/css/app.css')
    @stack('scripts')
</head>
<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100 flex">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Content --}}
        <main class="flex-1 ml-64 p-6">
            {{-- If used as a Blade component, render the component slot --}}
            @isset($header)
                <header class="mb-6">
                    {{ $header }}
                </header>
            @endisset

            {{ $slot ?? '' }}
            {{-- Backwards-compatible: also allow @section('content') usage --}}
            @yield('content')
        </main>

    </div>

</body>
</html>
