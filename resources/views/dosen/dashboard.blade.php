<x-app-layout>
    <x-slot name="title">
        Dashboard Dosen - MagangApp
    </x-slot>

    <div class="py-6 space-y-6">
        @if($isKaprodi)
            {{-- Statistik Kaprodi --}}
        @endif
        @if($isKaprodi)

            <div class="mt-6 space-y-6">

            <h2 class="text-lg font-semibold">Ringkasan Statistik Prodi</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

            <div class="p-6 bg-blue-100 rounded shadow">
            <h3 class="font-semibold">Mahasiswa Ajukan PKL</h3>
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

            </div>

            @endif

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

            {{-- Mahasiswa Aktif --}}
            <div class="p-6 transition bg-white border border-green-100 shadow rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-green-700">
                        Mahasiswa Aktif
                    </h3>
                    <i class="text-green-600 fa-solid fa-users"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-green-900">
                    {{ $mahasiswaCount ?? 0 }}
                </p>
            </div>

            {{-- Logbook Pending --}}
            <div class="p-6 transition bg-white border shadow border-amber-100 rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-amber-700">
                        Logbook Pending
                    </h3>
                    <i class="text-amber-600 fa-solid fa-clock"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-amber-900">
                    {{ $logbookPendingCount ?? 0 }}
                </p>
            </div>

            {{-- Laporan Akhir --}}
            <div class="p-6 transition bg-white border border-blue-100 shadow rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-blue-700">
                        Laporan Akhir
                    </h3>
                    <i class="text-blue-600 fa-solid fa-file-pdf"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-blue-900">
                    {{ $laporanAkhirCount ?? 0 }}
                </p>
            </div>

            {{-- PKL Selesai --}}
            <div class="p-6 transition bg-white border border-green-100 shadow rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-green-700">
                        PKL Selesai
                    </h3>
                    <i class="text-green-600 fa-solid fa-check-circle"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-green-900">
                    {{ $pklSelesaiCount ?? 0 }}
                </p>
            </div>

</div>

        {{-- Informasi --}}
        <div class="flex flex-col gap-2 p-6 transition border shadow-lg border-amber-200 rounded-xl bg-amber-50 hover:shadow-xl">
            <h3 class="flex items-center gap-2 font-semibold text-amber-800">
                <i class="fa-solid fa-info-circle"></i> Informasi
            </h3>
            <p class="mt-2 text-sm text-amber-700">
                Pastikan seluruh logbook mahasiswa diperiksa secara berkala.
            </p>
        </div>

    </div>
</x-app-layout>
