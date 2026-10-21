<x-app-layout>
    <x-slot name="title">
        Akun Mitra - SIBOLANG
    </x-slot>

    <div x-data="copyTextarea()" class="px-0 py-6 mx-auto max-w-4xl md:py-8">
        <div class="overflow-hidden bg-white rounded-2xl border border-green-200 shadow-lg">

            {{-- Header --}}
            <div class="px-5 py-4 bg-green-700 md:px-6">
                <h3 class="text-lg font-semibold text-white">
                    Detail Akun Mitra
                </h3>
                <p class="text-sm text-green-100">
                    Informasi akun login mitra PKL
                </p>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="p-4 text-sm text-green-800 bg-green-50 border-b border-green-200">
                    <i class="mr-1 fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 text-sm text-red-800 bg-red-50 border-b border-red-200">
                    <i class="mr-1 fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 space-y-6 md:p-6">

                @if ($akun)

                    {{-- Informasi akun --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500">Nama Instansi</p>
                            <p class="font-semibold text-gray-800">
                                {{ $tempat->nama_tempat }}
                            </p>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500">Email Tujuan</p>
                            <p class="font-semibold text-gray-800 break-all">
                                {{ $tempat->email ?? '-' }}
                            </p>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500">Username</p>
                            <p class="font-semibold text-gray-800 break-all">
                                {{ $akun['username'] }}
                            </p>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500">Password Sementara</p>
                            <p class="font-bold text-red-600 break-all">
                                {{ $akun['password'] }}
                            </p>
                        </div>

                    </div>

                    {{-- Status pengiriman --}}
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex gap-3 items-start">
                            <i class="mt-1 text-blue-600 fa-solid fa-envelope"></i>
                            <div>
                                <p class="font-semibold text-blue-900">
                                    Pengiriman kredensial melalui email
                                </p>
                                <p class="mt-1 text-sm text-blue-800">
                                    @if (!empty($tempat->email))
                                        Email tujuan: {{ $tempat->email }}.
                                        Periksa kotak masuk atau folder spam untuk memastikan
                                        email akun diterima.
                                    @else
                                        Email instansi belum tersedia.
                                        Kredensial belum dapat dikirim melalui email.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Template untuk salinan manual --}}
                    @php
                        $pesan =
                            "Yth. {$tempat->nama_tempat},\n\n" .
                            "Akun Mitra PKL SIBOLANG Anda telah dibuat.\n\n" .
                            "Username: {$akun['username']}\n" .
                            "Password sementara: {$akun['password']}\n\n" .
                            "Silakan login melalui:\n" .
                            url('/login') .
                            "\n\n" .
                            "Demi keamanan, segera ganti password setelah login pertama.\n\n" .
                            "Terima kasih.\n" .
                            'Tim SIBOLANG UNISLA';
                    @endphp

                    <div>
                        <h4 class="mb-2 font-semibold text-gray-700">
                            Informasi Akun untuk Salinan
                        </h4>

                        <textarea id="templatePesan"
                            class="p-3 w-full text-sm bg-green-50 rounded-lg border border-gray-300 focus:outline-none focus:ring focus:ring-green-200"
                            rows="8" readonly>{{ $pesan }}</textarea>

                        <button type="button" @click="copy('templatePesan')"
                            class="px-4 py-2 mt-3 w-full text-sm text-white bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700">
                            <i class="mr-1 fa-solid fa-copy"></i>
                            Salin Informasi Akun
                        </button>
                    </div>

                    {{-- Mahasiswa terkait --}}
                    <div class="pt-5 border-t">
                        <h4 class="mb-3 font-semibold text-gray-700">
                            Mahasiswa PKL di Tempat Ini
                        </h4>

                        @forelse ($mahasiswas as $mhs)
                            <div class="p-4 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="font-semibold text-gray-800">
                                    {{ $mhs->nama }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    NIM: {{ $mhs->nim }}
                                </p>
                            </div>
                        @empty
                            <div class="p-4 text-sm text-gray-600 bg-gray-50 rounded-lg border border-gray-200">
                                Belum ada mahasiswa dengan pengajuan PKL yang disetujui di instansi ini.
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end pt-4 border-t">
                        <a href="{{ route('staff.mitra.index') }}"
                            class="inline-flex gap-2 justify-center items-center px-5 py-2.5 w-full text-sm font-medium text-white bg-gray-600 rounded-lg shadow sm:w-auto hover:bg-gray-700">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Manajemen Mitra
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <i class="mb-3 text-3xl text-gray-400 fa-solid fa-circle-info"></i>

                        <p class="text-gray-600">
                            Data akun tidak tersedia atau halaman telah direfresh.
                        </p>

                        <a href="{{ route('staff.mitra.index') }}"
                            class="inline-block px-5 py-2 mt-4 text-sm font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                            Kembali ke Manajemen Mitra
                        </a>
                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
