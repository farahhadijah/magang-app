<x-app-layout>
    <x-slot name="title">
        Input Nilai - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-6xl sm:px-6">

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-50 rounded-xl border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="p-4 text-yellow-800 bg-yellow-50 rounded-xl border border-yellow-200">
                {{ session('warning') }}
            </div>
        @endif

        {{-- Header --}}
        <div>
            <h2 class="text-xl font-bold text-green-700 sm:text-2xl">
                Input Nilai PKL
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Daftar mahasiswa yang telah menyelesaikan laporan akhir
            </p>
        </div>

        {{-- ============================= --}}
        {{-- MOBILE VIEW — CARD ( < sm ) --}}
        {{-- ============================= --}}
        <div class="space-y-3 sm:hidden">
            @forelse($pkls as $pkl)
                <div class="p-4 bg-white rounded-2xl border border-green-100 shadow-sm">

                    {{-- Nama + Status --}}
                    <div class="flex gap-3 justify-between items-start">
                        <div class="min-w-0">
                            <p class="text-xs text-gray-400">Mahasiswa</p>
                            <p class="font-semibold text-gray-800 truncate">
                                {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                            </p>
                        </div>

                        {{-- Status Nilai (badge ringkas) --}}
                        <div class="shrink-0">
                            @if ($pkl->nilaiPkl)
                                @if ($pkl->nilaiPkl->status_approval === 'approved')
                                    <span
                                        class="inline-flex gap-1 items-center px-2.5 py-1 text-[11px] font-semibold text-green-800 bg-green-100 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Disetujui
                                    </span>
                                @elseif($pkl->nilaiPkl->status_approval === 'rejected')
                                    <span
                                        class="inline-flex gap-1 items-center px-2.5 py-1 text-[11px] font-semibold text-red-800 bg-red-100 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="inline-flex gap-1 items-center px-2.5 py-1 text-[11px] font-semibold text-amber-800 bg-amber-100 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            @else
                                <span
                                    class="inline-flex px-2.5 py-1 text-[11px] font-semibold rounded-full text-slate-700 bg-slate-100">
                                    Belum Dinilai
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Nilai --}}
                    <div class="grid grid-cols-2 gap-3 pt-3 mt-3 border-t border-gray-100">
                        <div>
                            <p class="text-[11px] text-gray-400">Nilai Angka</p>
                            @if ($pkl->nilaiPkl)
                                <p class="font-semibold text-gray-800">
                                    {{ number_format($pkl->nilaiPkl->nilai_angka, 2) }}
                                </p>
                            @else
                                <p class="text-gray-400">-</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-[11px] text-gray-400">Nilai Huruf</p>
                            @if ($pkl->nilaiPkl)
                                <p class="font-semibold text-gray-800">
                                    {{ $pkl->nilaiPkl->nilai_huruf }}
                                </p>
                            @else
                                <p class="text-gray-400">-</p>
                            @endif
                        </div>
                    </div>

                    {{-- Aksi --}}
                    @if (!$pkl->nilaiPkl)
                        <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                            class="inline-flex justify-center items-center px-4 py-2.5 mt-4 w-full text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                            Input Nilai
                        </a>
                    @endif

                </div>
            @empty
                <div class="p-6 text-sm text-center text-gray-500 bg-white rounded-2xl border border-green-100">
                    Tidak ada mahasiswa yang perlu dinilai.
                </div>
            @endforelse
        </div>

        {{-- ============================= --}}
        {{-- DESKTOP VIEW — TABLE ( ≥ sm ) --}}
        {{-- ============================= --}}
        <div class="hidden overflow-hidden bg-white rounded-2xl border border-green-100 shadow sm:block">
            <table class="w-full text-sm text-left">
                <thead class="bg-green-100 text-slate-800">
                    <tr>
                        <th class="px-6 py-3">Mahasiswa</th>
                        <th class="px-6 py-3">Nilai Angka</th>
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

                            <td class="px-6 py-4 text-gray-700">
                                @if ($pkl->nilaiPkl)
                                    <span
                                        class="font-semibold">{{ number_format($pkl->nilaiPkl->nilai_angka, 2) }}</span>
                                    <span class="text-xs text-gray-500">({{ $pkl->nilaiPkl->nilai_huruf }})</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if ($pkl->nilaiPkl)
                                    @if ($pkl->nilaiPkl->status_approval === 'approved')
                                        <span
                                            class="inline-flex gap-1.5 items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Disetujui
                                        </span>
                                    @elseif($pkl->nilaiPkl->status_approval === 'rejected')
                                        <span
                                            class="inline-flex gap-1.5 items-center px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex gap-1.5 items-center px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Menunggu Approval
                                        </span>
                                    @endif
                                @else
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full text-slate-700 bg-slate-100">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if (!$pkl->nilaiPkl)
                                    <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                        class="inline-flex px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                        Input Nilai
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">
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
