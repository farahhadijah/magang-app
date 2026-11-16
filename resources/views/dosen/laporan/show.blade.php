<x-app-layout>
    <x-slot name="title">
        Detail Laporan - MagangApp
    </x-slot>

    <div x-data="informasiModal" class="py-6 mx-auto space-y-6 max-w-5xl">
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

            <div>
                <p class="flex gap-2 items-center mb-2 text-sm font-semibold tracking-wide text-green-800 uppercase">
                    <i class="w-5 fa-solid fa-file-pdf"></i>
                    File Laporan
                </p>

                <button type="button"
                    @click="openModal(
                        @js(asset('storage/' . $laporan->path_file)),
                        'Laporan Akhir - {{ addslashes($pkl->pengajuanPkl->mahasiswa->nama ?? 'Mahasiswa') }}'
                    )"
                    class="inline-flex gap-2 items-center font-medium text-green-700 underline hover:text-green-900">
                    <i class="fa-solid fa-eye"></i>
                    Lihat File PDF
                </button>

                <p class="mt-2 text-xs text-gray-500">
                    Format laporan: PDF
                </p>
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

        {{-- ================= MODAL PREVIEW PDF ================= --}}
        <template x-if="isOpen">
            <div x-cloak @keydown.escape.window="closeModal()" @click.self="closeModal()"
                class="flex fixed inset-0 z-[9999999999] justify-center items-center p-4 bg-black/70">

                <div
                    class="relative flex flex-col w-full max-w-6xl max-h-[92vh] overflow-hidden bg-white shadow-2xl rounded-2xl">

                    {{-- Header Modal --}}
                    <div class="flex justify-between items-center px-5 py-4 border-b border-gray-200">

                        <div class="flex gap-3 items-center min-w-0">

                            <div
                                class="flex flex-shrink-0 justify-center items-center w-10 h-10 bg-green-100 rounded-lg">
                                <i class="text-green-600 fa-solid fa-file-pdf"></i>
                            </div>

                            <div class="min-w-0">

                                <h3 class="font-semibold text-gray-800 truncate" x-text="title"></h3>

                                <p class="text-xs text-gray-500">
                                    Preview Laporan Akhir PDF
                                </p>

                            </div>

                        </div>


                        <button type="button" @click="closeModal()"
                            class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-gray-500 bg-gray-100 rounded-lg transition hover:text-red-600 hover:bg-red-50"
                            title="Tutup">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                    </div>


                    {{-- Isi Preview PDF --}}
                    <div class="overflow-auto flex-1 p-3 min-h-0 bg-gray-100">

                        <iframe :src="viewerSrc()" title="Preview PDF Laporan Akhir"
                            class="w-full h-[78vh] bg-white border border-gray-200 rounded-lg" frameborder="0"></iframe>

                    </div>


                    {{-- Footer Modal --}}
                    <div class="flex gap-3 justify-between items-center px-5 py-3 bg-gray-50 border-t border-gray-200">

                        <a :href="fileUrl" target="_blank" rel="noopener noreferrer"
                            class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-100">
                            <i class="fa-solid fa-up-right-from-square"></i>
                            Buka File
                        </a>

                        <button type="button" @click="closeModal()"
                            class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                            <i class="fa-solid fa-xmark"></i>
                            Tutup
                        </button>

                    </div>

                </div>

            </div>
        </template>

    </div>
</x-app-layout>
