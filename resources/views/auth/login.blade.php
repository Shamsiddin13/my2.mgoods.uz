<x-guest-layout>

    <header class="dark:bg-gray-100 p-4">
        <h2 class="text-4xl font-bold text-center">Login</h2>
    </header>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Username or Source name -->
        <div>
            <x-input-label for="login" :value="__('Email / Username')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Parol')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-4 w-full">
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Menga eslat') }}</span>
                </label>
            </div>
            <!-- View or Hide Password -->
            <div class="block mt-4">
                <label for="toggle_password" class="inline-flex items-center">
                    <input id="toggle_password" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" onclick="togglePasswordVisibility();">
                    <span id="password_label" class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __("Parolni ko'rish") }}</span>
                </label>
            </div>
            <div></div>
            <div></div>
        </div>

        <div class="flex justify-between items-center mt-4 w-full">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Parolingizni unitdingizmi?') }}
                </a>
            @else
                <div></div> <!-- Maintains spacing when only one link is present -->
            @endif

            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                    {{ __("Ro'yhatdan o'tish") }}
                </a>
            @else
                <div></div> <!-- Maintains spacing when only one link is present -->
            @endif

            <x-primary-button>
                {{ __('KIRISH') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var passwordLabel = document.getElementById('password_label');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordLabel.innerHTML = '{{ __("Parolni yashirish") }}';
        } else {
            passwordInput.type = 'password';
            passwordLabel.innerHTML = '{{ __("Parolni ko'rish") }}';
        }
    }
</script>
