<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-800">
            Ganti Password Pertama
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Demi keamanan akun, silakan buat password baru sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.first') }}">
        @csrf

        <!-- Password Baru -->
        <div>
            <x-input-label for="password" value="Password Baru" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autofocus
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                Simpan & Lanjutkan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
