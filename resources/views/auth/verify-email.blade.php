<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __("Ro'yxatdan o'tganingiz uchun tashakkur! \n\nIshni boshlashdan oldin, biz sizga yuborgan havolani bosish orqali elektron pochta manzilingizni tasdiqlay olasizmi? \n\nAgar siz xabarni olmagan bo'lsangiz, biz sizga mamnuniyat bilan boshqa xabar yuboramiz.") }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __("Ro'yxatdan o'tish paytida ko'rsatgan elektron pochta manzilingizga yangi tasdiqlash havolasi yuborildi.") }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Tasdiqlash xabarini qayta yuborish') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
