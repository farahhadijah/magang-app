<x-app-layout>
    <x-slot name="title">
        Mahasiswa- MagangApp
    </x-slot>
<div class="py-6 px-4 sm:px-6 lg:px-8 min-h-screen">

    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                Data Mahasiswa PKL
            </h1>
            <p class="text-emerald-600 text-sm">
                Ringkasan dan daftar mahasiswa berdasarkan status PKL
            </p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            <div class="bg-yellow-50 rounded-lg shadow-sm p-4 border-t-4 border-yellow-200">
                <p class="text-sm text-gray-600">Mengajukan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $mengajukan->count() }}</p>
            </div>

            <div class="bg-green-50 rounded-lg shadow-sm p-4 border-t-4 border-green-200">
                <p class="text-sm text-gray-600">Sedang PKL</p>
                <p class="text-2xl font-bold text-gray-800">{{ $sedang->count() }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg shadow-sm p-4 border-t-4 border-gray-400">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $selesai->count() }}</p>
            </div>

            <div class="bg-red-50 rounded-lg shadow-sm p-4 border-t-4 border-red-300">
                <p class="text-sm text-gray-600">Belum PKL</p>
                <p class="text-2xl font-bold text-gray-800">{{ $belum->total() }}</p>
            </div>
        </div>

        {{-- MENGAJUKAN --}}
        @if($mengajukan->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Mengajukan</h2>
                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">{{ $mengajukan->count() }} Mahasiswa</span>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div id="mengajukan" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-yellow-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-yellow-900">NIM</th>
                                <th class="px-4 py-3 text-left font-semibold text-yellow-900">Nama Mahasiswa</th>
                                <th class="px-4 py-3 text-left font-semibold text-yellow-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-yellow-50">
                            @foreach($mengajukan as $m)
                            <tr>
                                <td class="px-4 py-3 text-yellow-800">{{ $m->nim }}</td>
                                <td class="px-4 py-3 font-medium text-yellow-800">{{ $m->nama }}</td>
                                <td class="px-4 py-3">
                                    @if($m->status_pengajuan == 'pending_tu')
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-500">Pending TU</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-orange-200 text-orange-700">Pending Kaprodi</span>
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
        @if($sedang->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Sedang PKL</h2>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ $sedang->count() }} Mahasiswa</span>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div id="sedang" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-green-900">NIM</th>
                                <th class="px-4 py-3 text-left font-semibold text-green-900">Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-green-50">
                            @foreach($sedang as $m)
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
        @if($selesai->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Selesai PKL</h2>
                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">{{ $selesai->count() }} Mahasiswa</span>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div id="selesai" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">NIM</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selesai as $m)
                            <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
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

        {{-- BELUM PKL --}}
        @if($belum->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Belum PKL</h2>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">{{ $belum->total() }} Mahasiswa</span>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div id="belum" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-red-200">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-red-900">NIM</th>
                                <th class="px-4 py-3 text-left font-semibold text-red-900">Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($belum as $m)
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
        @if($mengajukan->count() == 0 && $sedang->count() == 0 && $selesai->count() == 0 && $belum->count() == 0)
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <p class="text-gray-500">Belum ada data mahasiswa</p>
        </div>
        @endif
    </div>

</div>
</x-app-layout>