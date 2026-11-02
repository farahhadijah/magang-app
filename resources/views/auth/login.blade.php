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

    <div class="flex justify-center items-center px-0 py-4 min-h-screen bg-green-50 sm:px-4 sm:py-6">

        <div class="overflow-hidden w-full max-w-4xl bg-white rounded-2xl border border-gray-200 shadow-xl">

            {{-- Panel kiri (informasi) lebih sempit, panel kanan (login) lebih lebar --}}
            <div class="grid grid-cols-1 md:grid-cols-5">

                {{-- =====================================================
                    PANEL KIRI - INFORMASI (2/5 lebar)
                ====================================================== --}}
                <div class="overflow-hidden relative p-4 text-white sm:p-6 md:col-span-2 md:p-6"
                    style="background-color: #1f8a1b;">

                    {{-- Dekorasi lingkaran --}}
                    <div class="absolute -top-32 -right-32 w-72 h-72 rounded-full opacity-20"
                        style="background-color: #167a5b;"></div>

                    <div class="absolute -left-32 -bottom-48 w-80 h-80 rounded-full opacity-20"
                        style="background-color: #167a5b;"></div>

                    <div class="relative z-10">

                        {{-- Identitas --}}
                        <div class="mb-3 md:mb-5">
                            <h2 class="text-base font-bold tracking-wider sm:text-xl">
                                SIBOLANG UNISLA
                            </h2>
                            <p class="mt-1 text-[11px] text-green-50 sm:text-sm">
                                Sistem Informasi Logbook Magang
                            </p>
                        </div>


                        {{-- Judul Informasi --}}
                        <div>
                            <h1 class="mb-3 text-xl font-bold sm:text-2xl md:mb-4">
                                Informasi
                            </h1>

                            {{-- Data dari database --}}
                            {{-- FUNGSI ALPINE DITARUH LANGSUNG DI SINI AGAR ANTI GAGAL --}}
                            <div x-data="{
                                isOpen: false,
                                fileUrl: '',
                                title: '',
                                openModal(url, titleText) {
                                    this.fileUrl = url;
                                    this.title = titleText;
                                    this.isOpen = true;
                                },
                                closeModal() {
                                    this.isOpen = false;
                                    this.fileUrl = '';
                                    this.title = '';
                                },
                                isPdf() {
                                    if (!this.fileUrl) return false;
                                    return this.fileUrl.toLowerCase().split('?')[0].endsWith('.pdf');
                                },
                                isImage() {
                                    if (!this.fileUrl) return false;
                                    const u = this.fileUrl.toLowerCase().split('?')[0];
                                    return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/.test(u);
                                },
                                isOffice() {
                                    if (!this.fileUrl) return false;
                                    const u = this.fileUrl.toLowerCase().split('?')[0];
                                    return /\.(doc|docx|xls|xlsx|ppt|pptx)$/.test(u);
                                },
                                isUnknown() {
                                    return this.fileUrl && !this.isPdf() && !this.isImage() && !this.isOffice();
                                },
                                pdfViewerUrl() {
                                    if (!this.isPdf()) return '';
                                    // GUNAKAN PDF.JS LOKAL (SELF-HOST)
                                    const viewer = '/vendor/pdfjs/web/viewer.html';
                                    return `${viewer}?file=${encodeURIComponent(this.fileUrl)}`;
                                },
                                viewerSrc() {
                                    if (!this.isOffice()) return '';
                                    return `https://docs.google.com/gview?url=${encodeURIComponent(this.fileUrl)}&embedded=true`;
                                }
                            }">

                                {{-- MOBILE: Grid card kecil (2 kolom) --}}
                                {{-- DESKTOP: List vertikal --}}
                                <div class="grid grid-cols-2 gap-2 md:block md:space-y-3">

                                    @forelse ($informasi as $item)
                                        <button type="button"
                                            @click="openModal('{{ Storage::url($item->file_path) }}', @js($item->judul))"
                                            class="flex flex-col gap-1.5 items-start p-2.5 w-full text-left rounded-lg transition bg-white/10 hover:bg-white/20 active:bg-white/30 md:flex-row md:gap-3 md:p-0 md:bg-transparent md:rounded-none md:hover:bg-transparent md:hover:translate-x-1 md:hover:text-green-100">

                                            <i class="text-base fa-regular fa-file-lines sm:text-lg md:mt-1"></i>

                                            <span
                                                class="text-[11px] font-medium leading-snug line-clamp-2 sm:text-sm md:text-sm md:line-clamp-none">
                                                {{ $item->judul }}
                                            </span>
                                        </button>
                                    @empty
                                        <p class="col-span-2 text-xs text-green-100 sm:text-sm">
                                            Belum ada informasi yang tersedia.
                                        </p>
                                    @endforelse

                                </div>

                                {{-- MODAL INFORMASI (DIPERLUAS & FULL SCREEN) --}}
                                <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal()"
                                    class="flex fixed inset-0 z-50 justify-center items-center p-0 backdrop-blur-sm bg-black/70 sm:p-4">

                                    <div class="absolute inset-0" @click="closeModal()"></div>

                                    <div x-show="isOpen" x-transition
                                        class="relative z-10 flex flex-col w-full h-full sm:h-[90vh] max-w-6xl sm:rounded-2xl overflow-hidden bg-white shadow-2xl">

                                        {{-- Header Modal --}}
                                        <div
                                            class="flex justify-between items-center px-4 py-3 border-b sm:px-6 sm:py-4 shrink-0">
                                            <div class="pr-4">
                                                <h2 class="text-base font-bold text-gray-800 sm:text-lg" x-text="title">
                                                </h2>
                                                <p class="mt-1 text-xs text-gray-500">Informasi Sibolang</p>
                                            </div>
                                            <button type="button" @click="closeModal()"
                                                class="flex flex-shrink-0 justify-center items-center w-8 h-8 text-gray-500 rounded-full transition sm:w-9 sm:h-9 hover:bg-red-100 hover:text-red-600">
                                                <i class="text-lg fa-solid fa-xmark"></i>
                                            </button>
                                        </div>

                                        {{-- Body Modal --}}
                                        <div class="overflow-hidden flex-1 w-full bg-gray-100">
                                            {{-- PDF (via PDF.js lokal) --}}
                                            <template x-if="isPdf()">
                                                <iframe x-bind:src="pdfViewerUrl()" class="w-full h-full border-0"
                                                    allowfullscreen></iframe>
                                            </template>

                                            {{-- IMAGE --}}
                                            <template x-if="isImage()">
                                                <div
                                                    class="flex overflow-auto justify-center items-center p-3 w-full h-full bg-gray-200 sm:p-6">
                                                    <img :src="fileUrl" :alt="title"
                                                        class="object-contain max-w-full max-h-full rounded-lg shadow-md">
                                                </div>
                                            </template>

                                            {{-- OFFICE (Word/Excel/PPT) --}}
                                            <template x-if="isOffice()">
                                                <iframe x-bind:src="viewerSrc()" class="w-full h-full border-0"
                                                    allowfullscreen></iframe>
                                            </template>

                                            {{-- UNKNOWN --}}
                                            <template x-if="isUnknown()">
                                                <div
                                                    class="flex flex-col justify-center items-center p-6 w-full h-full text-center sm:p-10">
                                                    <i
                                                        class="mb-4 text-4xl text-gray-400 fa-solid fa-file sm:text-5xl"></i>
                                                    <p class="text-sm text-gray-600 sm:text-base">
                                                        File tidak dapat ditampilkan langsung.
                                                    </p>
                                                    <a :href="fileUrl" target="_blank"
                                                        class="px-5 py-2 mt-4 text-sm font-semibold text-white rounded-lg transition hover:opacity-90"
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


                        {{-- INFORMASI TAMBAHAN (desktop only) --}}
                        <div class="hidden p-4 mt-5 rounded-xl border md:block"
                            style="background-color: #167a5b; border-color: rgba(255,255,255,0.15);">

                            <div class="flex gap-3 items-start">
                                <div
                                    class="flex flex-shrink-0 justify-center items-center w-9 h-9 rounded-full bg-white/20">
                                    <i class="text-base fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    <p class="text-xs leading-relaxed text-white sm:text-sm">
                                        Silakan gunakan informasi yang tersedia
                                        sebagai panduan dalam pelaksanaan
                                        Praktik Kerja Lapangan dan Magang.
                                    </p>
                                </div>
                            </div>

                            <div class="my-3 border-t border-white/20"></div>

                            <div class="flex gap-3 items-center">
                                <img src="{{ asset('img/logounisla.png') }}" alt="Logo Universitas Islam Lamongan"
                                    class="object-contain w-9 h-9" />
                                <div>
                                    <p class="text-xs font-semibold sm:text-sm">
                                        Universitas Islam Lamongan
                                    </p>
                                    <p class="text-[11px] text-green-100 sm:text-xs">
                                        Sistem Informasi Sibolang
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- =====================================================
                    PANEL KANAN - LOGIN (3/5 lebar)
                ====================================================== --}}
                <div class="p-6 sm:p-8 md:col-span-3 md:p-8">

                    {{-- Header --}}
                    <div class="mb-5 md:mb-6">
                        <h1 class="text-xl font-bold sm:text-2xl" style="color: #167a5b;">
                            Log In -
                            <span style="color: #1f8a1b;">
                                SIBOLANG UNISLA
                            </span>
                        </h1>
                        <p class="mt-1.5 text-xs text-gray-600 sm:text-sm">
                            Sistem Informasi Logbook Magang
                            Universitas Islam Lamongan.
                        </p>
                    </div>


                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />


                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">

                        @csrf

                        {{-- Username --}}
                        <div class="flex flex-col gap-2">
                            <x-input-label for="username" :value="__('Username (NIM / NIDN / NIP)')" class="text-sm font-semibold"
                                style="color: #167a5b;" />

                            <div class="relative">
                                <i class="absolute left-4 top-1/2 text-base -translate-y-1/2 fa-solid fa-user"
                                    style="color: #167a5b;"></i>
                                <x-text-input id="username"
                                    class="block py-2.5 pr-4 pl-11 w-full text-sm rounded-xl border focus:ring-2"
                                    style="border-color: #b8ded5;" type="text" name="username" :value="old('username')"
                                    required autofocus autocomplete="off" placeholder="Masukkan Username" />
                            </div>

                            <x-input-error :messages="$errors->get('username')" class="text-sm text-red-600" />
                        </div>


                        {{-- Password --}}
                        <div class="flex flex-col gap-2">
                            <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold"
                                style="color: #167a5b;" />

                            <div class="relative">
                                <i class="absolute left-4 top-1/2 text-base -translate-y-1/2 fa-solid fa-lock"
                                    style="color: #167a5b;"></i>
                                <x-text-input id="password"
                                    class="block py-2.5 pr-4 pl-11 w-full text-sm rounded-xl border focus:ring-2"
                                    style="border-color: #b8ded5;" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Masukkan Password" />
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="text-sm text-red-600" />
                        </div>


                        {{-- Tombol Login --}}
                        <div class="pt-1">
                            <x-primary-button
                                class="flex gap-2 justify-center items-center px-6 py-2.5 text-sm font-semibold text-white rounded-xl transition hover:opacity-90"
                                style="background-color: #1f8a1b;">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Sign In
                            </x-primary-button>
                        </div>

                    </form>

                    {{-- Footer Login --}}
                    <div class="mt-5 text-[11px] text-center text-green-700 sm:text-xs">
                        &copy; 2026 SIBOLANG.
                        Developed by Nur Faizah | Farah Hadijah.
                        All rights reserved.
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
