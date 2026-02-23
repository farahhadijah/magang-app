<x-app-layout>
    <x-slot name="title">
        Akun Mitra - MagangApp
    </x-slot>

    <div class="max-w-4xl px-4 py-8 mx-auto">

        <div class="overflow-hidden bg-white border border-green-200 shadow-lg rounded-2xl">

            {{-- Header --}}
            <div class="px-6 py-4 bg-green-700">
                <h3 class="text-lg font-semibold text-white">
                    Detail Akun Mitra
                </h3>
                <p class="text-sm text-green-100">
                    Informasi akun login untuk mitra PKL
                </p>
            </div>

            <div class="p-6 space-y-6">

                @php
                    $akun = session('generated_account');
                @endphp

                @if($akun)

                    {{-- Informasi Akun --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="text-base font-semibold text-gray-800">
                                {{ $akun['username'] }}
                            </p>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="text-sm text-gray-500">Password</p>
                            <p class="text-base font-bold text-red-600">
                                {{ $akun['password'] }}
                            </p>
                        </div>

                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 md:col-span-2">
                            <p class="text-sm text-gray-500">Nomor HP Mitra</p>
                            <p class="text-base font-semibold text-gray-800">
                                {{ $tempat->no_hp }}
                            </p>
                        </div>

                    </div>

                    {{-- Divider --}}
                    <div class="border-t"></div>

                    @php
                        $pesan = "Yth. {$tempat->nama_tempat},\n\n"
                            . "Berikut akun login Mitra PKL:\n"
                            . "Username: {$akun['username']}\n"
                            . "Password: {$akun['password']}\n\n"
                            . "Silakan login di:\n"
                            . url('/login') . "\n\n"
                            . "Dimohon segera mengganti password setelah login pertama.\n\n"
                            . "Terima kasih.";
                    @endphp

                    {{-- Template WA --}}
                    <div>
                        <h4 class="mb-2 font-semibold text-gray-700">
                            Template Pesan WhatsApp
                        </h4>

                        <textarea 
                            class="w-full p-4 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                            rows="8"
                            readonly>{{ $pesan }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex flex-wrap justify-end gap-3 pt-4">

                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $tempat->no_hp) }}?text={{ urlencode($pesan) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg shadow hover:bg-green-700 transition">
                            Kirim ke WhatsApp
                        </a>

                        <a href="{{ route('staff.mitra.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gray-600 rounded-lg shadow hover:bg-gray-700 transition">
                            Kembali
                        </a>

                    </div>

                @else

                    {{-- Jika session tidak ada --}}
                    <div class="p-6 text-center">
                        <p class="text-gray-600">
                            Data akun tidak tersedia atau halaman telah direfresh.
                        </p>

                        <a href="{{ route('staff.mitra.index') }}"
                           class="inline-block px-5 py-2 mt-4 text-sm font-medium text-white bg-gray-600 rounded-lg shadow hover:bg-gray-700">
                            Kembali ke Manajemen Mitra
                        </a>
                    </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>