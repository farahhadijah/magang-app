<x-guest-layout>

    <x-slot name="title">
        Login - Sibolang
    </x-slot>
    <x-slot name="hideGuestHeader">
        true
    </x-slot>

    <x-slot name="hideGuestFooter">
        true
    </x-slot>

    <div class="flex justify-center items-center px-4 py-8 min-h-screen bg-green-50">

        <div class="overflow-hidden w-full max-w-5xl bg-white rounded-2xl border border-gray-200 shadow-xl">

            <div class="grid grid-cols-1 md:grid-cols-2">

                {{-- =====================================================
                    PANEL KIRI - INFORMASI
                ====================================================== --}}
                <div class="overflow-hidden relative p-8 text-white md:p-10" style="background-color: #1f8a1b;">

                    {{-- Dekorasi lingkaran --}}
                    <div class="absolute -top-32 -right-32 w-72 h-72 rounded-full opacity-20"
                        style="background-color: #167a5b;"></div>

                    <div class="absolute -left-32 -bottom-48 w-80 h-80 rounded-full opacity-20"
                        style="background-color: #167a5b;"></div>

                    <div class="relative z-10">

                        {{-- Identitas --}}
                        <div class="mb-10">

                            <h2 class="text-xl font-bold tracking-wider">
                                SIBOLANG UNISLA
                            </h2>

                            <p class="mt-1 text-sm text-green-50">
                                Sistem Informasi Logbook Magang
                            </p>

                        </div>


                        {{-- Judul Informasi --}}
                        <div>

                            <h1 class="mb-6 text-4xl font-bold">
                                Informasi
                            </h1>


                            {{-- Data dari database --}}
                            <div x-data="informasiModal" class="space-y-4">
                                @forelse ($informasi as $item)
                                    <button type="button"
                                        @click="openModal( '{{ Storage::url($item->file_path) }}', @js($item->judul)
            )"
                                        class="flex gap-3 items-start w-full text-base text-left transition hover:translate-x-1 hover:text-green-100">
                                        <i class="mt-1 text-lg fa-regular fa-file-lines"></i>

                                        <span>
                                            {{ $item->judul }}
                                        </span>
                                    </button>
                                @empty
                                    <p class="text-sm text-green-100">
                                        Belum ada informasi yang tersedia.
                                    </p>
                                @endforelse

                                {{-- MODAL INFORMASI --}}
                                <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal()"
                                    class="flex fixed inset-0 z-50 justify-center items-center p-4">
                                    {{-- Overlay --}}
                                    <div class="absolute inset-0 bg-black/60" @click="closeModal()"></div>

                                    {{-- Modal --}}
                                    <div x-show="isOpen" x-transition
                                        class="relative z-10 flex flex-col w-full max-w-5xl max-h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl">

                                        {{-- Header --}}
                                        <div class="flex justify-between items-center px-6 py-4 border-b">
                                            <div class="pr-4">
                                                <h2 class="text-lg font-bold text-gray-800" x-text="title"></h2>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    Informasi Sibolang
                                                </p>
                                            </div>

                                            <button type="button" @click="closeModal()"
                                                class="flex justify-center items-center w-9 h-9 text-gray-500 rounded-full transition hover:bg-gray-100 hover:text-gray-700">
                                                <i class="text-lg fa-solid fa-xmark"></i>
                                            </button>
                                        </div>

                                        {{-- Content --}}
                                        <div class="overflow-auto bg-gray-100">

                                            {{-- PDF --}}
                                            <template x-if="isPdf()">
                                                <iframe :src="fileUrl" class="w-full h-[75vh]"
                                                    frameborder="0"></iframe>
                                            </template>

                                            {{-- IMAGE --}}
                                            <template x-if="isImage()">
                                                <div class="flex justify-center items-center p-6 min-h-[50vh]">
                                                    <img :src="fileUrl" :alt="title"
                                                        class="object-contain max-w-full max-h-[75vh] rounded-lg shadow">
                                                </div>
                                            </template>

                                            {{-- OFFICE --}}
                                            <template x-if="isOffice()">
                                                <iframe :src="viewerSrc()" class="w-full h-[75vh]"
                                                    frameborder="0"></iframe>
                                            </template>

                                            {{-- UNKNOWN --}}
                                            <template x-if="isUnknown()">
                                                <div
                                                    class="flex flex-col justify-center items-center p-10 min-h-[300px] text-center">
                                                    <i class="mb-4 text-5xl text-gray-400 fa-solid fa-file"></i>

                                                    <p class="text-gray-600">
                                                        File tidak dapat ditampilkan langsung.
                                                    </p>

                                                    <a :href="fileUrl" target="_blank"
                                                        class="px-5 py-2 mt-4 text-sm font-semibold text-white rounded-lg"
                                                        style="background-color: #1f8a1b;">
                                                        Buka File
                                                    </a>
                                                </div>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{-- =================================================
                            INFORMASI TAMBAHAN
                        ================================================== --}}
                        <div class="p-5 mt-10 rounded-xl border"
                            style="
                                background-color: #167a5b;
                                border-color: rgba(255,255,255,0.15);
                            ">

                            <div class="flex gap-4 items-start">

                                <div
                                    class="flex flex-shrink-0 justify-center items-center w-12 h-12 rounded-full bg-white/20">
                                    <i class="text-xl fa-solid fa-circle-info"></i>
                                </div>

                                <div>

                                    <p class="text-sm leading-relaxed text-white">
                                        Silakan gunakan informasi yang tersedia
                                        sebagai panduan dalam pelaksanaan
                                        Praktik Kerja Lapangan dan Magang.
                                    </p>

                                </div>

                            </div>


                            <div class="my-5 border-t border-white/20"></div>


                            <div class="flex gap-3 items-center">

                                <img src="{{ asset('img/logounisla.png') }}" alt="Logo Universitas Islam Lamongan"
                                    class="object-contain w-12 h-12" />

                                <div>

                                    <p class="text-sm font-semibold">
                                        Universitas Islam Lamongan
                                    </p>

                                    <p class="text-xs text-green-100">
                                        Sistem Informasi Sibolang
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                    PANEL KANAN - LOGIN
                ====================================================== --}}
                <div class="p-8 md:p-12">

                    {{-- Header --}}
                    <div class="mb-10">

                        <h1 class="text-3xl font-bold" style="color: #167a5b;">
                            Log In -
                            <span style="color: #1f8a1b;">
                                SIBOLANG UNISLA
                            </span>
                        </h1>

                        <p class="mt-2 text-sm text-gray-600">
                            Sistem Informasi Logbook Magang
                            Universitas Islam Lamongan.
                        </p>

                    </div>


                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-5" :status="session('status')" />


                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">

                        @csrf


                        {{-- Username --}}
                        <div class="flex flex-col gap-2">

                            <x-input-label for="username" :value="__('Username (NIM / NIDN / NIP)')" class="font-semibold"
                                style="color: #167a5b;" />

                            <div class="relative">

                                <i class="absolute left-4 top-1/2 text-lg -translate-y-1/2" style="color: #167a5b;"
                                    class="fa-solid fa-user"></i>

                                <x-text-input id="username"
                                    class="block py-3 pr-4 pl-12 w-full text-sm rounded-xl border focus:ring-2"
                                    style="border-color: #b8ded5;" type="text" name="username" :value="old('username')"
                                    required autofocus autocomplete="off" placeholder="Masukkan Username" />

                            </div>

                            <x-input-error :messages="$errors->get('username')" class="text-sm text-red-600" />

                        </div>


                        {{-- Password --}}
                        <div class="flex flex-col gap-2">

                            <x-input-label for="password" :value="__('Password')" class="font-semibold"
                                style="color: #167a5b;" />

                            <div class="relative">

                                <i class="absolute left-4 top-1/2 text-lg -translate-y-1/2" style="color: #167a5b;"
                                    class="fa-solid fa-lock"></i>

                                <x-text-input id="password"
                                    class="block py-3 pr-4 pl-12 w-full text-sm rounded-xl border focus:ring-2"
                                    style="border-color: #b8ded5;" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Masukkan Password" />

                            </div>

                            <x-input-error :messages="$errors->get('password')" class="text-sm text-red-600" />

                        </div>


                        {{-- Tombol Login --}}
                        <div class="pt-2">

                            <x-primary-button
                                class="flex gap-2 justify-center items-center px-8 py-3 text-sm font-semibold text-white rounded-xl transition hover:opacity-90"
                                style="background-color: #1f8a1b;">

                                <i class="fa-solid fa-right-to-bracket"></i>

                                Sign In

                            </x-primary-button>

                        </div>

                    </form>

                    {{-- Footer Login --}}
                    <div class="mt-8 text-sm text-center text-green-700">
                        &copy; 2026 SIBOLANG.
                        Developed by Nur Faizah | Farah Hadijah.
                        All rights reserved.
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
