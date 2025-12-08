<aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-0">
    <div class="p-6 font-bold text-xl border-b">
        <span class="text-blue-600">Smart</span><span class="text-purple-600">Doorz</span>
    </div>

    <nav class="p-4 space-y-2">
        <!-- Dashboard for all users -->
        <a href="{{ route('home') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('/') || request()->is('home') ? 'bg-gray-100 font-semibold' : '' }}">
            🏠 Dashboard
        </a>

        <!-- Scanner for all users -->
        <a href="{{ route('scanner.index') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('scanner*') ? 'bg-gray-100 font-semibold' : '' }}">
            🔍 Pemindai QR
        </a>

        <!-- Admin Only Menu -->
        @if(auth()->user()?->is_admin)
        <hr class="my-2">
        <p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">Panel Admin</p>
        
        <a href="{{ route('dashboard.index') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
            📊 Analitik
        </a>

        <a href="{{ route('users.index') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('users*') ? 'bg-gray-100 font-semibold' : '' }}">
            👥 Manajemen Pengguna
        </a>

        <a href="{{ route('rooms.index') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('rooms*') ? 'bg-gray-100 font-semibold' : '' }}">
            🚪 Manajemen Ruangan
        </a>
        @endif

        <!-- Profile & Logout -->
        <hr class="my-2">
        <a href="{{ route('profile.edit') }}" 
           class="block p-3 rounded hover:bg-gray-100 {{ request()->is('profile*') ? 'bg-gray-100 font-semibold' : '' }}">
            ⚙️ Profil
        </a>

        <form action="{{ route('logout') }}" method="POST" class="pt-2">
            @csrf
            <button 
                class="w-full text-left p-3 rounded hover:bg-red-100 text-red-600 font-medium">
                🚪 Logout
            </button>
        </form>

    </nav>
</aside>
