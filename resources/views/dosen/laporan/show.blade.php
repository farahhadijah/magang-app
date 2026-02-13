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

        <h2 class="mb-4 text-xl font-bold">Detail Laporan</h2>

        <a href="{{ asset('storage/'.$laporan->path_file) }}"
           target="_blank"
           class="text-blue-600 underline">
           Lihat File PDF
        </a>

        <div class="mt-6">
            <p>Status: <strong>{{ $laporan->status_approve }}</strong></p>
        </div>

        @if($laporan->status_approve !== 'approved')
            <form method="POST"
                  action="{{ route('dosen.laporan.approve', $pkl->id) }}"
                  class="mt-6 space-y-4">
                @csrf

                <textarea name="catatan_dosen"
                          placeholder="Catatan (opsional)"
                          class="w-full p-2 border rounded"></textarea>

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-600 rounded">
                    Approve Laporan
                </button>
            </form>
        @endif

    </div>
</x-app-layout>
