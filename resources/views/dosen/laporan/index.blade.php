<x-app-layout>
    <x-slot name="title">
        Laporan Akhir - MagangApp
    </x-slot>

    <div class="max-w-6xl px-4 py-6 mx-auto space-y-6 sm:px-6 lg:px-8">

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
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-green-700">
                Laporan Akhir Mahasiswa
            </h2>
            <p class="text-sm text-gray-500">
                Daftar laporan akhir mahasiswa bimbingan
            </p>
        </div>

        {{-- Table --}}
        <!-- Responsive Table / Card -->
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-2xl">

            <!-- ===== MOBILE PAGINATION (ATAS) ===== -->
            <div class="p-4 border-b md:hidden">
                {{ $pkls->links() }}
            </div>

            <!-- ===== DESKTOP TABLE ===== -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-600 bg-green-100">
                        <tr>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Status PKL</th>
                            <th class="px-6 py-3">Status Laporan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($pkls as $pkl)
                            @php
                                $laporan = $pkl->laporanAkhir;
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($pkl->status) }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($laporan)
                                        @if($laporan->status_approve === 'pending')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                                Menunggu Review
                                            </span>
                                        @elseif($laporan->status_approve === 'approved')
                                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                Disetujui
                                            </span>
                                        @elseif($laporan->status_approve === 'rejected')
                                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                                Ditolak
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">
                                            Belum Upload
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($laporan)
                                        <a href="{{ route('dosen.laporan.show', $pkl->id) }}"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                            Detail
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada laporan akhir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ===== MOBILE CARD ===== -->
            <div class="p-4 space-y-4 md:hidden">
                @forelse($pkls as $pkl)
                    @php
                        $laporan = $pkl->laporanAkhir;
                    @endphp

                    <div class="p-4 bg-white border rounded-xl shadow-sm space-y-3">

                        <!-- Nama -->
                        <h3 class="text-sm font-semibold text-gray-800">
                            {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                        </h3>

                        <!-- Status PKL -->
                        <div class="text-xs">
                            <span class="text-gray-500">Status PKL:</span><br>
                            <span class="font-medium text-gray-800">
                                {{ ucfirst($pkl->status) }}
                            </span>
                        </div>

                        <!-- Status Laporan -->
                        <div class="text-xs">
                            <span class="text-gray-500">Status Laporan:</span><br>

                            @if($laporan)
                                @if($laporan->status_approve === 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        Menunggu Review
                                    </span>
                                @elseif($laporan->status_approve === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Disetujui
                                    </span>
                                @elseif($laporan->status_approve === 'rejected')
                                    <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Ditolak
                                    </span>
                                @endif
                            @else
                                <span class="px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">
                                    Belum Upload
                                </span>
                            @endif
                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            @if($laporan)
                                <a href="{{ route('dosen.laporan.show', $pkl->id) }}"
                                class="block w-full px-4 py-2 text-sm text-center text-white bg-green-600 rounded-lg shadow hover:bg-green-700 transition">
                                    Lihat Detail
                                </a>
                            @else
                                <span class="block text-sm text-center text-gray-400">Tidak tersedia</span>
                            @endif
                        </div>

                    </div>

                @empty
                    <div class="py-10 text-center text-gray-500">
                        Belum ada laporan akhir.
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Pagination --}}
        <div class="hidden md:block">
            {{ $pkls->links() }}
        </div>

    </div>
</x-app-layout>