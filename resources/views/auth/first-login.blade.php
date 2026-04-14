<x-guest-layout>
    <x-slot name="title">
        Frist Login - MagangApp
    </x-slot>
    <div class="flex flex-col items-center px-0 pt-5 bg-green-50">

        {{-- Card --}}
        <div class="w-full max-w-md p-8 transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-2xl">

            {{-- Header --}}
            <div class="mb-6 text-center">
                <h2 class="flex items-center justify-center gap-2 text-2xl font-bold text-green-900">
                    <i class="fa-solid fa-key"></i> Ganti Password Pertama
                </h2>
                <p class="mt-1 text-sm text-green-700">
                    Demi keamanan akun, silakan buat password baru sebelum melanjutkan.
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.first') }}" class="space-y-4">
                @csrf

                <!-- Password Baru -->
                <div class="flex flex-col gap-1">
                    <x-input-label for="password" value="Password Baru" class="font-medium text-green-800"/>
                    <div class="relative">
                        <i class="absolute text-green-400 -translate-y-1/2 fa-solid fa-lock left-3 top-1/2"></i>
                        <x-text-input
                            id="password"
                            class="block w-full p-2 pl-10 mt-1 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            type="password"
                            name="password"
                            required
                            autofocus
                            autocomplete="new-password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="text-sm text-red-600"/>
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-1">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" class="font-medium text-green-800"/>
                    <div class="relative">
                        <i class="absolute text-green-400 -translate-y-1/2 fa-solid fa-lock left-3 top-1/2"></i>
                        <x-text-input
                            id="password_confirmation"
                            class="block w-full p-2 pl-10 mt-1 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-sm text-red-600"/>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end mt-6">
                    <x-primary-button class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Simpan & Lanjutkan
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>
