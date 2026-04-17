<x-app-layout>
    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>

    <div class="max-w-6xl px-0 py-6 mx-auto space-y-4">

        {{-- NOTIFIKASI (DIRINGKAS) --}}
        @if (session('success') || session('warning'))
            <div class="p-3 text-sm border rounded-lg 
                {{ session('success') ? 'bg-green-50 border-green-200 text-green-800' : '' }}
                {{ session('warning') ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : '' }}">
                
                {{ session('success') ?? session('warning') }}
            </div>
        @endif


        {{-- HEADER RINGKAS --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            <div>
                <h1 class="text-lg font-semibold text-slate-800">
                    Logbook PKL
                </h1>
                <p class="text-xs text-gray-500">
                    Minimal 30 logbook <span class="font-semibold">approved</span> untuk laporan akhir
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">

                <a href="{{ route('mahasiswa.logbook.create') }}"
                   class="w-full sm:w-auto px-4 py-2 text-sm text-white text-center bg-green-600 rounded-lg hover:bg-green-700">
                    + Tambah
                </a>

                <div class="px-3 py-2 text-sm text-center bg-white border rounded-lg">
                    <span class="text-gray-500">Total:</span>
                    <span class="font-bold text-green-700">
                        {{ $logbooks->total() }}
                    </span>
                </div>

            </div>

        </div>
        {{-- Pagination links --}}
        <div class="mt-4 px-4">
            <div class="flex justify-end">
                {!! $logbooks->links() !!}
            </div>
        </div>


        {{-- TANGGAL KOSONG (COLLAPSIBLE BIAR GA BERISIK) --}}
        @if(isset($tanggalKosong) && $tanggalKosong->count() > 0)
        <details class="p-3 border border-red-200 rounded-lg bg-red-50">
            <summary class="text-sm font-semibold text-red-700 cursor-pointer">
                Ada {{ $tanggalKosong->count() }} hari belum diisi
            </summary>

            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($tanggalKosong as $tgl)
                    <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">
                        {{ $tgl->format('d M Y') }}
                    </span>
                @endforeach
            </div>
        </details>
        @endif


        {{-- TABLE --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

            {{-- DESKTOP --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-green-900 bg-green-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Kegiatan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-green-100">
                        @forelse($logbooks as $log)
                        <tr class="hover:bg-green-50">

                            <td class="px-4 py-3">
                                {{ $log->tgl->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $log->kegiatan }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($log->status_approve === 'approved')
                                    <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded">
                                        Disetujui
                                    </span>
                                @elseif ($log->status_approve === 'revisi')
                                    <span class="px-2 py-1 text-xs text-red-800 bg-red-100 rounded">
                                        Revisi
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs text-amber-800 bg-amber-100 rounded">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                {{ $log->catatan ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                @if (in_array($log->status_approve, ['pending', 'revisi']))
                                    <a href="{{ route('mahasiswa.logbook.edit', $log->id) }}"
                                       class="text-green-600 hover:underline text-sm">
                                        Edit
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Terkunci</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                Belum ada logbook
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- MOBILE CARD --}}
            <div class="p-4 space-y-4 md:hidden">
                @foreach($logbooks as $log)
                <div class="p-4 border rounded-lg">

                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-medium">
                            {{ $log->tgl->format('d-m-Y') }}
                        </p>

                        @if ($log->status_approve === 'approved')
                            <span class="text-xs text-green-700">✔</span>
                        @elseif ($log->status_approve === 'revisi')
                            <span class="text-xs text-red-700">!</span>
                        @else
                            <span class="text-xs text-amber-700">...</span>
                        @endif
                    </div>

                    <p class="text-sm mb-2">
                        {{ $log->kegiatan }}
                    </p>

                    <p class="text-xs text-gray-500 mb-2">
                        {{ $log->catatan ?? 'Tidak ada catatan' }}
                    </p>

                    @if (in_array($log->status_approve, ['pending', 'revisi']))
                        <a href="{{ route('mahasiswa.logbook.edit', $log->id) }}"
                           class="block w-full text-center text-sm text-white bg-green-600 rounded py-2">
                            Edit
                        </a>
                    @endif

                </div>
                @endforeach
            </div>

        </div>

    </div>
</x-app-layout>