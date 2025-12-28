@extends('layouts.app')

@section('title', 'Dashboard - SmartDoorz')

@section('content')
<div class="container mx-auto py-6">
    <!-- Welcome Section -->
    <div class="mb-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
        <h1 class="text-4xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-blue-100 text-lg">Kelola akses ruangan dan perpanjang sewa dengan mudah</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Rental Status -->
                @if(!auth()->user()->rental_start)
                <div class="mb-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">⏳ Status: Menunggu Persetujuan</h3>
                    <p class="text-yellow-700">Akun Anda sedang dalam tahap pending. Admin akan segera mengalokasikan ruangan untuk Anda.</p>
                </div>
                @elseif(auth()->user()->isRentalExpired())
                <div class="mb-6 p-6 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Sewa Berakhir</h3>
                    <p class="text-red-700 mb-4">Masa sewa Anda telah berakhir pada {{ auth()->user()->rental_end->format('d M Y H:i') }}.</p>
                    @if(auth()->user()->rooms->count() > 0)
                    <button onclick="showPaymentModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        💳 Perpanjang Sewa Sekarang
                    </button>
                    @else
                    <p class="text-red-600 text-sm">Hubungi admin untuk mendapatkan ruangan.</p>
                    @endif
                </div>
                @else
                <div class="mb-6 p-6 bg-green-50 border border-green-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">✅ Sewa Aktif</h3>
                    <p class="text-green-700 mb-2">Masa sewa berakhir: <strong>{{ auth()->user()->rental_end->format('d M Y H:i') }}</strong></p>
                    <p class="text-sm text-green-600 mb-4">Sisa waktu: {{ auth()->user()->rental_end->diffForHumans() }}</p>
                    @if(auth()->user()->rooms->count() > 0)
                    <button onclick="showPaymentModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        💳 Perpanjang Sewa (+1 Bulan)
                    </button>
                    @else
                    <p class="text-yellow-600 text-sm">Hubungi admin untuk mendapatkan ruangan.</p>
                    @endif
                </div>
                @endif

                <!-- Pricing Info -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">💰 Harga Sewa</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">Rp 500.000</div>
                    <div class="text-sm text-gray-600">per bulan</div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
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
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                    <p class="text-yellow-900 text-sm">⚠️ Belum ada ruangan yang ditugaskan.</p>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">🔗 Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('profile.edit') }}" class="block w-full bg-gray-500 text-white text-center py-3 px-4 rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                        👤 Edit Profil
                    </a>
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

        <form id="paymentForm" class="mb-6">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                <select id="paymentMethod" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="transfer">Transfer Bank</option>
                    <option value="cash">Tunai</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Referensi / Bukti Pembayaran</label>
                <input type="text" id="paymentReference" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    placeholder="Contoh: TRX123456789" required>
                <p class="text-xs text-gray-500 mt-1">Masukkan nomor transaksi atau kode bukti pembayaran</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea id="paymentNotes" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
        </form>

        <div class="flex space-x-3">
            <button onclick="processPayment()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                Konfirmasi Pembayaran
            </button>
            <button onclick="closePaymentModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-lg transition">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function showPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }

    function processPayment() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const paymentReference = document.getElementById('paymentReference').value.trim();
        const paymentNotes = document.getElementById('paymentNotes').value.trim();
        
        if (!paymentReference) {
            alert('Harap masukkan nomor referensi atau bukti pembayaran');
            return;
        }
        
        confirmPayment(paymentMethod, paymentReference, paymentNotes);
    }

    async function confirmPayment(paymentMethod, paymentReference, paymentNotes) {
        try {
            const response = await fetch('/rental/extend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    payment_method: paymentMethod,
                    payment_reference: paymentReference,
                    payment_notes: paymentNotes
                })
            });

            const data = await response.json();
            
            if (data.success) {
                closePaymentModal();
                alert('✅ Pembayaran berhasil dikonfirmasi! Sewa diperpanjang sampai ' + data.new_end_date);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }
</script>

@endsection