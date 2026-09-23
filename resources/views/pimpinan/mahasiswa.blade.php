<x-app-layout>
    <x-slot name="title">
        Mahasiswa- MagangApp
    </x-slot>
    <div class="px-4 py-6 min-h-screen sm:px-6 lg:px-8">

        <div class="mx-auto max-w-7xl">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="mb-2 text-2xl font-bold text-gray-800 md:text-3xl">
                    Data Mahasiswa PKL
                </h1>
                <p class="text-sm text-emerald-600">
                    Ringkasan dan daftar mahasiswa berdasarkan status PKL
                </p>
            </div>

            {{-- Statistik Cards --}}
            <div class="grid grid-cols-2 gap-4 mb-10 sm:grid-cols-4">
                <div class="p-4 bg-yellow-50 rounded-lg border-t-4 border-yellow-200 shadow-sm">
                    <p class="text-sm text-gray-600">Mengajukan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $mengajukan->count() }}</p>
                </div>

                <div class="p-4 bg-green-50 rounded-lg border-t-4 border-green-200 shadow-sm">
                    <p class="text-sm text-gray-600">Sedang PKL</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $sedang->count() }}</p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border-t-4 border-gray-400 shadow-sm">
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $selesai->count() }}</p>
                </div>

                <div class="p-4 bg-red-50 rounded-lg border-t-4 border-red-300 shadow-sm">
                    <p class="text-sm text-gray-600">Belum PKL</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $belum->total() }}</p>
                </div>
            </div>

            {{-- MENGAJUKAN --}}
            @if ($mengajukan->count() > 0)
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Mengajukan</h2>
                        <span
                            class="px-2 py-1 text-xs text-yellow-700 bg-yellow-100 rounded-full">{{ $mengajukan->count() }}
                            Mahasiswa</span>
                    </div>

                    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                        <div id="mengajukan" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-yellow-100">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-left text-yellow-900">NIM</th>
                                        <th class="px-4 py-3 font-semibold text-left text-yellow-900">Nama Mahasiswa
                                        </th>
                                        <th class="px-4 py-3 font-semibold text-left text-yellow-900">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-yellow-50">
                                    @foreach ($mengajukan as $m)
                                        <tr>
                                            <td class="px-4 py-3 text-yellow-800">{{ $m->nim }}</td>
                                            <td class="px-4 py-3 font-medium text-yellow-800">{{ $m->nama }}</td>
                                            <td class="px-4 py-3">
                                                @if ($m->status_pengajuan == 'pending_tu')
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-medium text-orange-500 bg-orange-100 rounded-full">Pending
                                                        TU</span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-medium text-orange-700 bg-orange-200 rounded-full">Pending
                                                        Kaprodi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $mengajukan->appends(request()->query())->fragment('mengajukan')->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- SEDANG PKL --}}
            @if ($sedang->count() > 0)
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Sedang PKL</h2>
                        <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded-full">{{ $sedang->count() }}
                            Mahasiswa</span>
                    </div>

                    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                        <div id="sedang" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-green-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-left text-green-900">NIM</th>
                                        <th class="px-4 py-3 font-semibold text-left text-green-900">Nama Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-green-50">
                                    @foreach ($sedang as $m)
                                        <tr class="border-t border-green-100">
                                            <td class="px-4 py-3 text-green-800">{{ $m->nim }}</td>
                                            <td class="px-4 py-3 font-medium text-green-800">{{ $m->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $sedang->appends(request()->query())->fragment('sedang')->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- SELESAI PKL --}}
            @if ($selesai->count() > 0)
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Selesai PKL</h2>
                        <span class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded-full">{{ $selesai->count() }}
                            Mahasiswa</span>
                    </div>

                    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                        <div id="selesai" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-300">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-left text-gray-700">NIM</th>
                                        <th class="px-4 py-3 font-semibold text-left text-gray-700">Nama Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selesai as $m)
                                        <tr class="border-t border-gray-100 transition hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-700">{{ $m->nim }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-800">{{ $m->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $selesai->appends(request()->query())->fragment('selesai')->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- DAFTAR MAHASISWA BELUM PKL (DIKOMENTARI) --}}
            @if ($belum->count() > 0)
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-gray-800">Belum PKL</h2>
                        <span class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded-full">{{ $belum->total() }}
                            Mahasiswa</span>
                    </div>

                    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                        <div id="belum" class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-red-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-left text-red-900">NIM</th>
                                        <th class="px-4 py-3 font-semibold text-left text-red-900">Nama Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($belum as $m)
                                        <tr class="border-t border-red-100">
                                            <td class="px-4 py-3 text-red-800">{{ $m->nim }}</td>
                                            <td class="px-4 py-3 font-medium text-red-800">{{ $m->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-3">
                                {{ $belum->appends(request()->query())->fragment('belum')->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Empty State jika semua data kosong --}}
            @if ($mengajukan->count() == 0 && $sedang->count() == 0 && $selesai->count() == 0 && $belum->count() == 0)
                <div class="p-8 text-center bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500">Belum ada data mahasiswa</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
