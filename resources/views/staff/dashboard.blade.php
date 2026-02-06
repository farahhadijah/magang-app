<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard Staff TU
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <!-- Menunggu Verifikasi -->
            <div class="p-6 bg-yellow-100 rounded shadow">
                <h3 class="text-lg font-semibold">Menunggu Verifikasi</h3>
                <p class="mt-2 text-3xl font-bold">5</p>
            </div>

            <!-- Disetujui -->
            <div class="p-6 bg-green-100 rounded shadow">
                <h3 class="text-lg font-semibold">Disetujui</h3>
                <p class="mt-2 text-3xl font-bold">12</p>
            </div>

            <!-- Ditolak -->
            <div class="p-6 bg-red-100 rounded shadow">
                <h3 class="text-lg font-semibold">Ditolak</h3>
                <p class="mt-2 text-3xl font-bold">2</p>
            </div>

        </div>

        <div class="mt-8">
            <a href="{{ route('staff.pengajuan.index') }}"
               class="inline-block px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Verifikasi Pengajuan PKL
            </a>
        </div>
    </div>
</x-app-layout>
