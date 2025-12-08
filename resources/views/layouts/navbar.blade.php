<nav class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between">

        <a href="/dashboard" class="font-bold text-xl">
            My Dashboard
        </a>

        <div class="flex items-center gap-4">

            @auth
                <span>{{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 text-white rounded">
                        Logout
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="text-blue-600">Register</a>
            @endguest
            
        </div>

    </div>
</nav>
