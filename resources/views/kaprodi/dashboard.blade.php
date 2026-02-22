<x-app-layout>
    <x-slot name="title">
        Dashboard - MagangApp
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

    </div>
</x-app-layout>
