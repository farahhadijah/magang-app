<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

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

        @if(!$laporan)
            <a href="{{ route('mahasiswa.laporan.create') }}"
               class="px-4 py-2 text-white bg-green-600 rounded">
                Upload Laporan
            </a>
        @else
            <div class="p-4 border rounded">
                <p>Status: <strong>{{ $laporan->status_approve }}</strong></p>

                <a href="{{ asset('storage/'.$laporan->path_file) }}"
                   target="_blank"
                   class="text-blue-600 underline">
                   Lihat File
                </a>

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
