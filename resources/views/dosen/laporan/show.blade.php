<x-app-layout>
    <x-slot name="title">
        Detail Laporan - MagangApp
    </x-slot>
    @php $pdfSrc = isset($laporan) ? asset('storage/'.$laporan->path_file) : ''; @endphp
    <div x-data="togglePdf(@js($pdfSrc))" class="py-6 mx-auto space-y-6 max-w-5xl">
        {{-- Flash Message --}}
        @if (session('success'))
            <div class="flex gap-2 items-center p-4 text-green-800 bg-green-50 rounded-xl border border-green-200">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="flex gap-2 items-center p-4 text-yellow-800 bg-yellow-50 rounded-xl border border-yellow-200">
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-green-700">
                Detail Laporan Akhir
            </h2>
            <p class="text-sm text-gray-500">
                Review laporan akhir mahasiswa
            </p>
        </div>
        {{-- Card Info Mahasiswa --}}
        <div class="p-6 space-y-3 bg-white rounded-2xl border border-green-100 shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">Mahasiswa</p>
                    <p class="font-semibold text-gray-800">
                        {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status PKL</p>
                    @if ($pkl->status === 'aktif')
                        <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">
                            Aktif
                        </span>
                    @elseif($pkl->status === 'menunggu_laporan')
                        <span class="px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full">
                            Menunggu Laporan
                        </span>
                    @elseif($pkl->status === 'selesai')
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                            Selesai
                        </span>
                    @endif
                </div>
            </div>
            {{-- Status Laporan --}}
            <div>
                <p class="mb-1 text-sm text-gray-500">Status Laporan</p>
                @if ($laporan->status_approve === 'pending')
                    <span class="px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full">
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
            </div>

        </div>

        {{-- Card File Preview --}}
        <div class="p-6 space-y-4 bg-white rounded-2xl border border-green-100 shadow">

            <h3 class="font-semibold text-green-700">
                File Laporan
            </h3>

            <button type="button" @click="toggle()"
                class="inline-flex gap-2 items-center text-sm font-medium text-green-600 hover:text-green-800 hover:underline"
                x-text="visible ? 'Sembunyikan File PDF' : 'Lihat File PDF'">
            </button>

            <div x-show="visible" x-cloak class="mt-4">
                <iframe :src="src" class="w-full h-[600px] border rounded-xl"></iframe>
            </div>

        </div>

        {{-- Catatan Lama --}}
        @if ($laporan->catatan_dosen)
            <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="mb-1 text-sm font-semibold text-red-700">
                    Catatan Sebelumnya
                </p>
                <p class="text-sm text-red-800">
                    {{ $laporan->catatan_dosen }}
                </p>
            </div>
        @endif

        {{-- Form Aksi --}}
        @if ($laporan->status_approve !== 'approved')
            <div class="p-6 space-y-4 bg-white rounded-2xl border border-green-100 shadow">

                <h3 class="font-semibold text-green-700">
                    Aksi Review
                </h3>

                {{-- Approve --}}
                <form method="POST" action="{{ route('dosen.laporan.approve', $pkl->id) }}">
                    @csrf

                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                        Approve
                    </button>
                </form>

                {{-- Reject --}}
                <form method="POST" action="{{ route('dosen.laporan.reject', $pkl->id) }}" class="space-y-3">
                    @csrf

                    <textarea name="catatan_dosen" required placeholder="Alasan penolakan (wajib)"
                        class="p-3 w-full text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-400 focus:outline-none"></textarea>

                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-red-600 rounded-lg transition hover:bg-red-700">
                        Reject
                    </button>
                </form>

            </div>
        @endif

    </div>
</x-app-layout>
