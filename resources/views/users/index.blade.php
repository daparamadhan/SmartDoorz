@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="text-3xl font-bold">Manajemen Pengguna</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Pengguna</a>
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
                <th class="text-left p-3">ID</th>
                <th class="text-left p-3">Nama</th>
                <th class="text-left p-3">Email</th>
                <th class="text-left p-3">Terdaftar</th>
                <th class="text-center p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $user->id }}</td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="p-3 text-center space-x-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm inline-block">Edit</a>
                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-center text-gray-500">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $users->links() }}
</div>

@endsection
