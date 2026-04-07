<x-app-layout>
    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>

    <div class="max-w-6xl py-5 mx-auto space-y-4">

        {{-- Info Mahasiswa --}}
        <div class="p-4 transition border border-green-200 shadow bg-green-50 rounded-xl hover:shadow-md">
            <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-green-900">
                        {{ $pkl->mahasiswa->nama ?? $pkl->mahasiswa->user->getNama() ?? '-' }}
                    </h3>
                    <p class="text-sm text-green-700">
                        NIM: {{ $pkl->mahasiswa->nim ?? '-' }}
                    </p>
                </div>

                <div class="mt-2 text-sm text-green-800 md:mt-0">
                    Periode:
                    <span class="font-medium">
                        {{ $pkl->tgl_mulai ? \Carbon\Carbon::parse($pkl->tgl_mulai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                        -
                        {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Daftar Logbook --}}
        <div class="p-4 bg-white border border-green-200 shadow rounded-xl">

            <h3 class="mb-3 text-lg font-semibold text-green-900">
                Daftar Kegiatan
            </h3>

            @if($pkl->logbooks->isEmpty())
                <div class="py-6 text-sm text-center text-gray-500">
                    Belum ada logbook yang diisi.
                </div>
            @else

                <div class="space-y-3">

                    @foreach($pkl->logbooks as $logbook)
                        <div class="p-4 transition border border-green-100 rounded-lg bg-green-50 hover:bg-green-100">

                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-2">

                                <div class="text-sm font-semibold text-green-800">
                                    {{ $logbook->tgl ? \Carbon\Carbon::parse($logbook->tgl, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </div>

                                <div>
                                    @if($logbook->status_approve == 'approved')
                                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                                            ✔ Disetujui
                                        </span>
                                    @elseif(in_array($logbook->status_approve, ['rejected','revisi']))
                                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">
                                            ✖ Ditolak
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">
                                            ⏳ Menunggu
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Isi kegiatan --}}
                            <div class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                                {{ $logbook->kegiatan }}
                            </div>

                            {{-- Catatan --}}
                            @if($logbook->catatan)
                                <div class="p-3 mt-3 text-sm text-green-900 bg-green-100 border-l-4 border-green-500 rounded">
                                    <strong>Catatan Dosen:</strong><br>
                                    {{ $logbook->catatan }}
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

            @endif

        </div>

    </div>
</x-app-layout>