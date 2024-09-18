<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- User Type Select Dropdown -->
        <div class="mt-4" x-data="{ userType: '' }">
            <x-input-label for="user_type" :value="__('Foydalanuvchi turi')"/>
            <select x-model="userType" class="block mt-1 w-full"  id="userType" name="userType" for="userType">
                <option value="">Foydalanuvchi turini tanlang</option>
                <option value="target">Target</option>
                <option value="store">Store</option>
                <option value="msadmin">Landing</option>
                <option value="manager">Manager</option>
                <option value="storekeeper">Storekeeper</option>
                <option value="superadmin">Super Admin</option>
            </select>
            <x-input-error :messages="$errors->get('userType')" class="mt-2" />

            <div class="mt-4" x-show="userType === 'target' || userType === 'store' || userType === 'manager'">
                <input for="type_name" id="type_name" name="type_name" type="text" placeholder="Foydalanuvchi turi nomini kiriting" class="mt-1 w-full"/>
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Parol')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Parolni Tasdiqlang')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __("Allaqachon ro'yxatdan o'tganmisiz?") }}
            </a>

            <x-primary-button class="ms-4">
                {{ __("Ro'yhatdan o'tish") }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
