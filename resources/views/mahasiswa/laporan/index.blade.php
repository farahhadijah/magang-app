<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="p-3 mb-4 text-yellow-800 bg-yellow-100 rounded">
                {{ session('warning') }}
            </div>
        @endif
        <h2 class="mb-4 text-xl font-bold">Laporan Akhir PKL</h2>
        {{-- PROGRES LOGBOOK --}}
        <div class="p-4 mb-4 border rounded bg-gray-50">
            <p class="text-sm">
                Total Logbook:
                <strong>{{ $pkl->totalLogbook() }}</strong> / 30
            </p>
            <p class="text-sm">
                Logbook Disetujui:
                <strong>{{ $pkl->totalLogbookApproved() }}</strong>
            </p>
        </div>
        {{-- JIKA BELUM ADA LAPORAN --}}
        @if(!$laporan)
            @if($pkl->isSiapUploadLaporan())
                <a href="{{ route('mahasiswa.laporan.create') }}"
                   class="px-4 py-2 text-white bg-green-600 rounded">
                    Upload Laporan
                </a>
            @else
                <div class="p-3 text-yellow-800 bg-yellow-100 rounded">
                    Belum memenuhi syarat upload laporan akhir.
                </div>
            @endif
        @else
            <div class="p-4 space-y-2 border rounded">
                {{-- STATUS BADGE --}}
                <p>
                    Status:
                    @if($laporan->status_approve === 'pending')
                        <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-200 rounded">
                            Menunggu Persetujuan
                        </span>
                    @elseif($laporan->status_approve === 'approved')
                        <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded">
                            Disetujui
                        </span>
                    @elseif($laporan->status_approve === 'rejected')
                        <span class="px-2 py-1 text-xs text-red-800 bg-red-200 rounded">
                            Ditolak
                        </span>
                    @endif
                </p>
                {{-- FILE --}}
                <a href="{{ asset('storage/'.$laporan->path_file) }}"
                   target="_blank"
                   class="text-blue-600 underline">
                   Lihat File
                </a>
                {{-- CATATAN DOSEN --}}
                @if($laporan->catatan_dosen)
                    <div class="p-3 mt-2 text-sm text-red-800 bg-red-100 rounded">
                        <strong>Catatan Dosen:</strong><br>
                        {{ $laporan->catatan_dosen }}
                    </div>
                @endif
                {{-- UPLOAD ULANG --}}
                @if($laporan->status_approve !== 'approved')
                    <div class="mt-4">
                        <a href="{{ route('mahasiswa.laporan.create') }}"
                           class="px-4 py-2 text-white bg-yellow-500 rounded">
                            Upload Ulang
                        </a>
                    </div>
                @endif
                </div>
        @endif
    </div>
</x-app-layout>
