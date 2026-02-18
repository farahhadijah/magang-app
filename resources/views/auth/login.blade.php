<x-guest-layout>
    <x-slot name="title">
        Login - MagangApp
    </x-slot>
    <div class="flex items-center justify-center px-4 bg-green-50">
        <div class="w-full max-w-md p-8 transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-2xl">

            {{-- Header --}}
            <div class="mb-6 text-center">
                <h1 class="flex items-center justify-center gap-2 text-2xl font-bold text-green-900">
                    <i class="fa-solid fa-lock"></i> Sibolang MagangApp
                </h1>
                <p class="mt-1 text-sm text-green-700">Silakan login menggunakan Username & Password Anda</p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Username -->
                <div class="flex flex-col gap-1">
                    <x-input-label for="username" :value="__('Username (NIM / NIDN / NIP)')" class="font-medium text-green-800"/>
                    <div class="relative">
                        <i class="absolute text-green-400 -translate-y-1/2 fa-solid fa-user left-3 top-1/2"></i>
                        <x-text-input
                            id="username"
                            class="block w-full p-2 pl-10 mt-1 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            type="text"
                            name="username"
                            :value="old('username')"
                            required
                            autofocus
                            autocomplete="off"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="text-sm text-red-600"/>
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-1">
                    <x-input-label for="password" :value="__('Password')" class="font-medium text-green-800"/>
                    <div class="relative">
                        <i class="absolute text-green-400 -translate-y-1/2 fa-solid fa-lock left-3 top-1/2"></i>
                        <x-text-input
                            id="password"
                            class="block w-full p-2 pl-10 mt-1 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="text-sm text-red-600"/>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        class="text-green-600 border-green-300 rounded shadow-sm focus:ring-green-500"
                        name="remember"
                    >
                    <label for="remember" class="text-sm text-green-700 ms-2">Remember me</label>
                </div>

                <!-- Submit -->
                <div>
                    <x-primary-button class="flex items-center justify-center w-full gap-2 transition bg-green-600 hover:bg-green-700">
                        <i class="fa-solid fa-right-to-bracket"></i> Log in
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
