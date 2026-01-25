<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(auth()->user()->role === 'mahasiswa')
                <div class="p-4 bg-white rounded dark:bg-gray-800">
                    <h3 class="text-lg font-bold">Dashboard Mahasiswa</h3>
                    <p>Menu: Ajukan PKL, Logbook, Laporan</p>
                </div>

            @elseif(auth()->user()->role === 'staff_tu')
                <div class="p-4 bg-white rounded dark:bg-gray-800">
                    <h3 class="text-lg font-bold">Dashboard Staff TU</h3>
                    <p>Menu: Verifikasi Pengajuan</p>
                </div>

            @elseif(auth()->user()->role === 'kaprodi')
                <div class="p-4 bg-white rounded dark:bg-gray-800">
                    <h3 class="text-lg font-bold">Dashboard Kaprodi</h3>
                    <p>Menu: Persetujuan PKL</p>
                </div>

            @elseif(auth()->user()->role === 'dosen')
                <div class="p-4 bg-white rounded dark:bg-gray-800">
                    <h3 class="text-lg font-bold">Dashboard Dosen</h3>
                    <p>Menu: Monitoring PKL</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
