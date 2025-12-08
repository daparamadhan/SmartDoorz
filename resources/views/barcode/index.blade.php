@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="text-3xl font-bold">Manajemen Barcode</h1>
    <a href="{{ route('barcode.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Barcode</a>
</div>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <table class="w-full">
        <thead class="border-b">
            <tr>
                <th class="text-left p-3">Kode</th>
                <th class="text-left p-3">Nama Produk</th>
                <th class="text-left p-3">Qty</th>
                <th class="text-left p-3">User</th>
                <th class="text-left p-3">Catatan</th>
                <th class="text-center p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barcodes as $barcode)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-mono text-sm">{{ $barcode->code }}</td>
                    <td class="p-3">{{ $barcode->product_name }}</td>
                    <td class="p-3">{{ $barcode->quantity }}</td>
                    <td class="p-3">{{ $barcode->user->name }}</td>
                    <td class="p-3 text-sm">{{ Str::limit($barcode->notes, 30) }}</td>
                    <td class="p-3 text-center space-x-2">
                        <a href="{{ route('barcode.edit', $barcode->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm inline-block">Edit</a>
                        <form method="POST" action="{{ route('barcode.destroy', $barcode->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>

                        <!-- Send barcode download link to the assigned user -->
                        <form method="POST" action="{{ route('barcode.send-link', $barcode->user->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Kirim tautan unduh barcode ke pengguna ini?')">Kirim Link</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $barcodes->links() }}
</div>

@endsection
