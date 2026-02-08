<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard Kaprodi
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

            <div class="p-6 bg-blue-100 rounded shadow">
                <h3 class="font-semibold">Total Mahasiswa PKL</h3>
                <p class="mt-2 text-3xl font-bold">{{ $totalMahasiswa }}</p>
            </div>

            <div class="p-6 bg-yellow-100 rounded shadow">
                <h3 class="font-semibold">Menunggu Verifikasi</h3>
                <p class="mt-2 text-3xl font-bold">{{ $totalMenunggu }}</p>
            </div>

            <div class="p-6 bg-green-100 rounded shadow">
                <h3 class="font-semibold">PKL Aktif</h3>
                <p class="mt-2 text-3xl font-bold">{{ $totalAktif }}</p>
            </div>

            <div class="p-6 bg-purple-100 rounded shadow">
                <h3 class="font-semibold">PKL Selesai</h3>
                <p class="mt-2 text-3xl font-bold">{{ $totalSelesai }}</p>
            </div>

        </div>

        <!-- Menu Akses -->
        <div class="p-6 bg-white rounded shadow">
            <h3 class="mb-4 text-lg font-semibold">Menu Kaprodi</h3>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('kaprodi.pengajuan.index') }}"
                   class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Verifikasi PKL
                </a>

                <a href="{{ route('kaprodi.pengajuan.histori_ditolak') }}"
                   class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                    Histori Ditolak
                </a>

                <a href="{{ route('kaprodi.mahasiswa.index') }}"
                   class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Data Mahasiswa PKL
                </a>

                <a href="{{ route('kaprodi.nilai.index') }}"
                   class="px-4 py-2 text-white bg-purple-600 rounded hover:bg-purple-700">
                    Nilai PKL
                </a>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                * Data ditarik langsung dari database
            </p>
        </div>

    </div>
</x-app-layout>
