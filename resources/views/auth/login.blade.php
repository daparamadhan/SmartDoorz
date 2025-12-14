<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
        <p class="text-gray-600">Silakan masuk untuk melanjutkan ke dashboard</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Masukkan email Anda"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password" class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                type="password"
                name="password"
                placeholder="Masukkan password Anda"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <a class="text-sm text-red-600 hover:text-red-800 underline"
                    href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" class="w-full bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                Masuk
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-gray-600 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-red-600 hover:text-red-800 font-medium underline ml-1">
                    Daftar sekarang
                </a>
            </p>
        </div>
        @endif
    </form>
</x-guest-layout>
