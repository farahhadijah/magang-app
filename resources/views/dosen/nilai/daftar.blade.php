<x-app-layout>
    <x-slot name="title">
        Daftar Nilai - MagangApp
    </x-slot>

    <div class="max-w-6xl px-4 py-6 mx-auto space-y-6 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-green-700">
                Daftar Nilai PKL Mahasiswa
            </h2>
            <p class="text-sm text-gray-500">
                Riwayat nilai mahasiswa yang telah menyelesaikan PKL
            </p>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="p-4 text-green-800 border border-green-200 bg-green-50 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table Card --}}
        <!-- Responsive Table / Card -->
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-2xl">

            <!-- ===== MOBILE PAGINATION ATAS ===== -->
            <div class="p-4 border-b md:hidden">
                {{ $pkls->links() }}
            </div>

            <!-- ===== DESKTOP TABLE ===== -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-sm text-left">
                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">NIM</th>
                            <th class="px-6 py-3">Nilai</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3">Tanggal Input</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-green-100">
                        @forelse($pkls as $index => $pkl)
                            <tr class="transition hover:bg-green-50">

                                <td class="px-6 py-4">
                                    {{ $pkls->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        {{ $pkl->nilaiPkl->nilai }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                    Belum ada nilai yang diinput.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ===== MOBILE CARD ===== -->
            <div class="p-4 space-y-4 md:hidden">
                @forelse($pkls as $index => $pkl)

                    <div class="p-4 bg-white border rounded-xl shadow-sm space-y-3">

                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                            </h3>
                            <span class="text-xs text-gray-500">
                                #{{ $pkls->firstItem() + $index }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="space-y-1 text-xs text-gray-600">
                            <p><span class="text-gray-500">NIM:</span> {{ $pkl->pengajuanPkl->mahasiswa->nim }}</p>
                            <p>
                                <span class="text-gray-500">Nilai:</span>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                    {{ $pkl->nilaiPkl->nilai }}
                                </span>
                            </p>
                            <p>
                                <span class="text-gray-500">Tanggal:</span>
                                {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                            </p>
                        </div>

                        <!-- Keterangan -->
                        <div class="text-xs">
                            <span class="text-gray-500">Keterangan:</span><br>
                            <span class="text-gray-700">
                                {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                            </span>
                        </div>

                    </div>

                @empty
                    <div class="py-10 text-center text-gray-500">
                        Belum ada nilai yang diinput.
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