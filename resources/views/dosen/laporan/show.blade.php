<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

        {{-- Flash --}}
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

        <h2 class="mb-4 text-xl font-bold">Detail Laporan Akhir</h2>

        {{-- Info Mahasiswa --}}
        <div class="p-4 mb-4 bg-gray-100 rounded">
            <p><strong>Mahasiswa:</strong>
                {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
            </p>
            <p><strong>Status PKL:</strong> {{ ucfirst($pkl->status) }}</p>
        </div>

        {{-- File --}}
        <a href="{{ asset('storage/'.$laporan->path_file) }}"
           target="_blank"
           class="text-blue-600 underline">
           Lihat File PDF
        </a>

        {{-- Status Badge --}}
        <div class="mt-4">
            @if($laporan->status_approve === 'pending')
                <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-200 rounded">
                    Menunggu Review
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
        </div>

        {{-- Catatan Lama --}}
        @if($laporan->catatan_dosen)
            <div class="p-3 mt-4 text-sm text-red-800 bg-red-100 rounded">
                <strong>Catatan Sebelumnya:</strong><br>
                {{ $laporan->catatan_dosen }}
            </div>
        @endif

        {{-- Form Approve / Reject --}}
        @if($laporan->status_approve !== 'approved')

            <form method="POST"
                  action="{{ route('dosen.laporan.approve', $pkl->id) }}"
                  class="mt-6 space-y-4">
                @csrf

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-4 py-2 text-white bg-green-600 rounded">
                        Approve
                    </button>
                </div>
            </form>

            {{-- Reject --}}
            <form method="POST"
                  action="{{ route('dosen.laporan.reject', $pkl->id) }}"
                  class="mt-3 space-y-4">
                @csrf

                <textarea name="catatan_dosen"
                          required
                          placeholder="Alasan penolakan (wajib)"
                          class="w-full p-2 border rounded"></textarea>

                <button type="submit"
                        class="px-4 py-2 text-white bg-red-600 rounded">
                    Reject
                </button>
            </form>

        @endif

    </div>
</x-app-layout>
