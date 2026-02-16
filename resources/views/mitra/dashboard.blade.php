<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Dashboard Mitra
        </h2>
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        <!-- Info Mitra -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-2 text-lg font-semibold">
                Selamat Datang, {{ $mitra->nama_perwakilan }}
            </h3>

            <p class="text-gray-600">
                Tempat PKL: 
                <span class="font-semibold">
                    {{ $mitra->tempatPkl->nama ?? '-' }}
                </span>
            </p>

            @if($mitra->jabatan)
                <p class="text-gray-600">
                    Jabatan: {{ $mitra->jabatan }}
                </p>
            @endif
        </div>

        <!-- Statistik -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold">
                Statistik
            </h3>

            <div class="text-3xl font-bold text-blue-600">
                {{ $jumlahMahasiswa }}
            </div>

            <p class="text-gray-600">
                Mahasiswa PKL Aktif di Tempat Anda
            </p>

            <a href="{{ route('mitra.mahasiswa') }}"
               class="inline-block px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                Lihat Daftar Mahasiswa
            </a>
        </div>

    </div>
</x-app-layout>
