<x-app-layout>
    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="flex items-center gap-2 p-4 text-green-800 border border-green-200 bg-green-50 rounded-xl">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex items-center gap-2 p-4 text-yellow-800 border border-yellow-200 bg-yellow-50 rounded-xl">
                <i class="text-yellow-600 fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

    {{-- PESAN --}}
        <div class="p-4 mb-4 border-l-4 border-blue-500 rounded-lg bg-blue-50">
        <div class="flex items-start gap-3">
            
            {{-- Icon --}}
            <div class="mt-1 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-6 h-6" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="2">
                    <path stroke-linecap="round" 
                        stroke-linejoin="round" 
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
            </div>

            {{-- Text --}}
            <div class="text-sm text-blue-900">
                <p class="font-semibold">
                    Informasi Logbook
                </p>
                <p class="mt-1 leading-relaxed">
                    Silakan mengisi logbook sesuai kebutuhan selama kegiatan PKL berlangsung. 
                    Namun, mahasiswa diwajibkan memiliki minimal 
                    <span class="font-semibold">30 logbook berstatus <i>approved</i></span> 
                    sebagai syarat untuk mengajukan laporan akhir.
                </p>
            </div>

        </div>
    </div>

        {{-- Button Tambah --}}
        <div class="flex flex-col gap-3 md:flex-row md:justify-end md:items-center">

    <a href="{{ route('mahasiswa.logbook.create') }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
        <i class="fa-solid fa-plus"></i>
        Tambah Logbook
    </a>

    <div class="px-4 py-2 text-center bg-white border border-green-100 shadow rounded-xl md:text-left">
        <p class="text-sm text-gray-500">
            Total Logbook Dibuat
            <span class="font-bold text-green-700">
                {{ $logbooks->count() }}
            </span>
        </p>
    </div>
    @if(isset($tanggalKosong) && $tanggalKosong->count() > 0)

    <div class="p-4 border border-red-200 bg-red-50 rounded-xl">

        <div class="flex items-center gap-2 mb-2 text-red-800">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span class="font-semibold">
                Logbook Belum Diisi
            </span>
        </div>

        <div class="flex flex-wrap gap-2">

            @foreach($tanggalKosong as $tgl)

                <span class="px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                    {{ $tgl->format('d M Y') }}
                </span>

            @endforeach

        </div>

    </div>

    @endif

</div>

        {{-- Table --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">
            <table class="w-full text-sm">
                <thead class="text-green-900 bg-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Catatan Dosen</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-green-100">
                    @forelse($logbooks as $log)
                        <tr class="transition hover:bg-green-50">

                            {{-- Tanggal --}}
                            <td class="px-4 py-3">
                                {{ $log->tgl->format('d-m-Y') }}
                            </td>

                            {{-- Kegiatan --}}
                            <td class="px-4 py-3">
                                {{ $log->kegiatan }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if ($log->status_approve === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Disetujui
                                    </span>
                                @elseif ($log->status_approve === 'revisi')
                                    <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Catatan Dosen --}}
                            <td class="px-4 py-3">
                                @if ($log->catatan)
                                    <div class="text-sm text-red-700">
                                        {{ $log->catatan }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3">
                                @if (in_array($log->status_approve, ['pending', 'revisi']))
                                    <a href="{{ route('mahasiswa.logbook.edit', $log->id) }}"
                                    class="text-sm font-medium text-green-600 hover:underline">
                                        Edit
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Terkunci</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada logbook
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
