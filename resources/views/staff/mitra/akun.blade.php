<x-app-layout>
<x-slot name="title">
    Akun Mitra - MagangApp
</x-slot>

<div x-data="copyTextarea()" class="max-w-4xl px-0 py-6 mx-auto md:py-8">

    <div class="overflow-hidden bg-white border border-green-200 shadow-lg rounded-2xl">

        {{-- Header --}}
        <div class="px-5 py-4 md:px-6 bg-green-700">
            <h3 class="text-lg font-semibold text-white">
                Detail Akun Mitra
            </h3>
            <p class="text-sm text-green-100">
                Informasi akun login untuk mitra PKL
            </p>
        </div>

        {{-- Warning --}}
        @if($account_notice ?? false)
            <div class="p-4 text-sm text-yellow-800 border border-yellow-200 bg-yellow-50">
                ⚠️ Data akun ini hanya ditampilkan satu kali.
                Pastikan Anda menyimpan atau mengirimkan data ini sebelum keluar.
            </div>
        @endif

        <div class="p-4 space-y-6 md:p-6">

            @if($akun)

                {{-- Informasi Akun --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                        <p class="text-xs text-gray-500">Username</p>
                        <p class="font-semibold text-gray-800 break-all">
                            {{ $akun['username'] }}
                        </p>
                    </div>

                    <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                        <p class="text-xs text-gray-500">Password</p>
                        <p class="font-bold text-red-600 break-all">
                            {{ $akun['password'] }}
                        </p>
                    </div>

                    <div class="p-4 border border-green-200 rounded-lg bg-green-50 md:col-span-2">
                        <p class="text-xs text-gray-500">Nomor HP Mitra</p>
                        <p class="font-semibold text-gray-800">
                            {{ $tempat->no_hp }}
                        </p>
                    </div>

                </div>

                <div class="border-t"></div>

                {{-- Template Pesan --}}
                @php
                    $pesan = "Yth. {$tempat->nama_tempat},\n\n"
                        . "Berikut akun login Mitra PKL:\n"
                        . "Username: {$akun['username']}\n"
                        . "Password: {$akun['password']}\n\n"
                        . "Silakan login:\n"
                        . url('/login') . "\n\n"
                        . "Dimohon segera mengganti password setelah login pertama.\n\n"
                        . "Terima kasih.";
                @endphp

                <div>
                    <h4 class="mb-2 font-semibold text-gray-700">
                        Template Pesan ke Mitra
                    </h4>

                    <textarea
                        id="templatePesan"
                        class="w-full p-3 text-sm border border-gray-300 rounded-lg bg-green-50 focus:outline-none focus:ring focus:ring-green-200"
                        rows="8"
                        readonly>{{ $pesan }}</textarea>

                    <button type="button" @click="copy('templatePesan')"
                        class="w-full sm:w-auto px-4 py-2 mt-3 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Copy Pesan
                    </button>
                </div>

                <div class="border-t"></div>

                {{-- Mahasiswa --}}
                <div>

                    <h4 class="mb-3 font-semibold text-gray-700">
                        Mahasiswa PKL di Tempat Ini
                    </h4>

                    @forelse($mahasiswas as $mhs)

                        @php
                            $pesanMahasiswa = "Halo {$mhs->nama},\n\n"
                                . "Berikut akun login Mitra PKL untuk instansi {$tempat->nama_tempat}:\n\n"
                                . "Username: {$akun['username']}\n"
                                . "Password: {$akun['password']}\n\n"
                                . "Mohon disampaikan kepada pihak instansi.\n\n"
                                . "Login:\n"
                                . url('/login');
                        @endphp

                        <div class="flex flex-col gap-3 p-4 mb-3 border border-green-200 rounded-lg bg-green-50 sm:flex-row sm:items-center sm:justify-between">

                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $mhs->nama }}
                                </p>

                                <p class="text-sm text-gray-600 break-all">
                                    {{ $mhs->no_hp ?? 'Nomor HP tidak tersedia' }}
                                </p>
                            </div>

                            @if($mhs->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $mhs->no_hp) }}?text={{ urlencode($pesanMahasiswa) }}"
                                   target="_blank"
                                   class="w-full text-center sm:w-auto px-4 py-2 text-sm text-white rounded-lg bg-amber-500 hover:bg-amber-600">
                                    Kirim Ke Mahasiswa
                                </a>
                            @else
                                <span class="text-sm text-red-500">
                                    Nomor tidak tersedia
                                </span>
                            @endif

                        </div>

                    @empty

                        <div class="p-4 text-sm text-red-700 border border-red-200 rounded-lg bg-red-50">
                            Tidak ada mahasiswa dengan nomor HP yang tersedia.
                        </div>

                    @endforelse

                </div>

                <div class="border-t"></div>

                {{-- Tombol --}}
                <div class="flex flex-col gap-2 pt-4 sm:flex-row sm:justify-end">

                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $tempat->no_hp) }}?text={{ urlencode($pesan) }}"
                       target="_blank"
                       class="w-full text-center sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg shadow hover:bg-green-700">
                        Kirim ke Mitra
                    </a>

                    <a href="{{ route('staff.mitra.index') }}"
                       class="w-full text-center sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-gray-600 rounded-lg shadow hover:bg-gray-700">
                        Kembali
                    </a>

                </div>

            @else

                <div class="p-6 text-center">

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