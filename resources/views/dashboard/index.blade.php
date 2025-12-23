@extends('layouts.app')

@section('title', 'Analitik Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Analitik Dashboard</h1>
        <p class="text-gray-600 mt-2">Pantau aktivitas akses ruangan secara real-time</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm font-semibold">Total Pengguna</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h2v-2a11 11 0 00-22 0v2h2v-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Rooms -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-100 text-sm font-semibold">Total Ruangan</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalRooms }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4l4 2m-8-4l4-2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-100 text-sm font-semibold">Ruangan Terisi</p>
                    <p class="text-4xl font-bold mt-2">{{ $occupiedRooms }}</p>
                    <p class="text-green-100 text-xs mt-2">{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}% Kapasitas</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-yellow-100 text-sm font-semibold">Ruangan Kosong</p>
                    <p class="text-4xl font-bold mt-2">{{ $availableRooms }}</p>
                    <p class="text-yellow-100 text-xs mt-2">{{ $totalRooms > 0 ? round(($availableRooms / $totalRooms) * 100, 1) : 0 }}% Tersedia</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Room Occupancy Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Status Ruangan</h2>
            <div class="flex items-center justify-center h-64">
                <canvas id="occupancyChart"></canvas>
            </div>
        </div>

        <!-- Top Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Statistik Cepat</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Pengguna Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminUsers }}</p>
                    </div>
                    <span class="text-3xl text-blue-500">👑</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Pengguna Regular</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $regularUsers }}</p>
                    </div>
                    <span class="text-3xl text-purple-500">👤</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Pengguna Delay</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $delayUsers }}</p>
                    </div>
                    <span class="text-3xl text-orange-500">⏰</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Access Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Akses Harian (7 Hari Terakhir)</h2>
        <div class="h-64">
            <canvas id="dailyAccessChart"></canvas>
        </div>
    </div>

    <!-- Recent Access Log -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Aktivitas Akses Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Pengguna</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Ruangan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentAccessLogs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-900">
                            <span class="font-medium">{{ $log->access_time->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-900">
                            @if($log->user)
                                <span class="font-medium">{{ $log->user->name }}</span>
                            @else
                                <span class="text-gray-500 italic">Unknown</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-900">
                            <span class="font-medium">{{ $log->room->room_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($log->status === 'success')
                                <span class="inline-block px-3 py-1 text-white bg-green-500 rounded-full text-xs font-semibold">✓ Berhasil</span>
                            @elseif($log->status === 'unauthorized')
                                <span class="inline-block px-3 py-1 text-white bg-yellow-500 rounded-full text-xs font-semibold">⚠ Tidak Sah</span>
                            @else
                                <span class="inline-block px-3 py-1 text-white bg-red-500 rounded-full text-xs font-semibold">✗ Gagal</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs font-mono">{{ $log->ip_address ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data akses
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Room Occupancy Chart
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Terisi', 'Kosong'],
            datasets: [{
                data: [{{ $occupiedRooms }}, {{ $availableRooms }}],
                backgroundColor: ['#10b981', '#fbbf24'],
                borderColor: ['#059669', '#f59e0b'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14, weight: 'bold' },
                        padding: 20
                    }
                }
            }
        }
    });

    // Daily Access Chart
    @php
        $dates = [];
        $counts = [];
        $allDates = [];
        
        // Generate last 7 days dengan timezone lokal
        for($i = 6; $i >= 0; $i--) {
            $allDates[] = \Carbon\Carbon::now('Asia/Jakarta')->subDays($i)->format('Y-m-d');
        }
        
        // Map data
        foreach($dailyAccess as $item) {
            $dates[$item->date] = $item->count;
        }
        
        // Fill in all dates
        foreach($allDates as $date) {
            $counts[] = $dates[$date] ?? 0;
        }
    @endphp
    
    const dailyAccessCtx = document.getElementById('dailyAccessChart').getContext('2d');
    new Chart(dailyAccessCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(function($d) { return \Carbon\Carbon::parse($d)->format('d/m'); }, $allDates)) !!},
            datasets: [{
                label: 'Akses Berhasil',
                data: {!! json_encode($counts) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#1e40af',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: { font: { size: 14, weight: 'bold' }, padding: 20 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 } }
                }
            }
        }
    });
</script>

@endsection
