<x-app-layout>
    <x-slot name="title">
        Input Nilai - MagangApp
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="p-4 text-green-800 border border-green-200 bg-green-50 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 text-yellow-800 border border-yellow-200 bg-yellow-50 rounded-xl">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-green-700">
                Input Nilai PKL
            </h2>
            <p class="text-sm text-gray-500">
                Daftar mahasiswa yang telah menyelesaikan laporan akhir
            </p>
        </div>

        {{-- Table Card --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-2xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-green-100 text-slate-800">
                    <tr>
                        <th class="px-6 py-3">Mahasiswa</th>
                        <th class="px-6 py-3">Status Nilai</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-green-100">
                    @forelse($pkls as $pkl)
                        <tr class="transition hover:bg-green-50">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($pkl->nilaiPkl)
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if(!$pkl->nilaiPkl)
                                    <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                       class="px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                                        Input Nilai
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                                Tidak ada mahasiswa yang perlu dinilai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div>
            {{ $pkls->links() }}
        </div>

    </div>
</x-app-layout>