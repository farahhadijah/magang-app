<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                <h1 class="mb-4 text-2xl font-bold">
                    Selamat Datang, Admin
                </h1>

                <p class="text-gray-600">
                    Dari halaman ini, admin akan:
                </p>

                <ul class="mt-2 ml-6 text-gray-700 list-disc">
                    <li>Mendaftarkan Kaprodi</li>
                    <li>Mendaftarkan Staff TU</li>
                    <li>Mendaftarkan Dosen Pembimbing</li>
                    <li>Mengelola data pengguna</li>
                </ul>

            </div>
        </div>
    </div>
</x-app-layout>
