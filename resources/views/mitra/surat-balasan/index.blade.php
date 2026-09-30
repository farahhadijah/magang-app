<x-app-layout>
    <x-slot name="title">
        Surat Balasan - MagangApp
    </x-slot>

    <div x-data="pdfViewer" class="py-6">
        <div class="px-0 mx-auto space-y-5 max-w-7xl sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="px-1 py-3 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Validation Error --}}
            @if ($errors->any())
                <div class="px-1 py-3 bg-red-50 rounded-lg border border-red-200">
                    <p class="font-medium text-red-700">
                        ❌ Surat balasan gagal diunggah.
                    </p>

                    <ul class="pl-5 mt-2 text-sm list-disc text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Daftar Mahasiswa --}}
            <div class="overflow-hidden bg-white rounded-xl ring-1 ring-gray-200 shadow-sm">

                {{-- Header Daftar --}}
                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Mahasiswa PKL
                        </h3>
                        <p class="text-xs text-gray-500">
                            Daftar mahasiswa yang sedang melaksanakan PKL di instansi Anda.
                        </p>
                    </div>

                    <span class="px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                        Total: {{ $pkls->total() }}
                    </span>
                </div>

                @forelse ($pkls as $pkl)
                    @php
                        $mahasiswa = $pkl->pengajuanPkl?->mahasiswa;
                        $suratBalasan = $pkl->suratBalasan;
                    @endphp

                    <div class="px-4 py-3 border-b border-gray-100 transition last:border-b-0 hover:bg-gray-50/60">

                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                            {{-- Informasi Mahasiswa --}}
                            <div class="min-w-0">

                                <div class="flex flex-wrap gap-2 items-center">
                                    <h4 class="text-sm font-semibold text-gray-800 truncate">
                                        {{ $mahasiswa?->nama ?? 'Nama mahasiswa tidak tersedia' }}
                                    </h4>

                                    @if ($suratBalasan)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 text-[11px] font-medium text-green-700 bg-green-100 rounded-full">
                                            Sudah Diupload
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 text-[11px] font-medium text-amber-700 bg-amber-100 rounded-full">
                                            Belum Diupload
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-y-1 gap-x-4 mt-1 text-xs text-gray-500">
                                    <p>
                                        <span class="font-medium text-gray-600">NIM:</span>
                                        {{ $mahasiswa?->nim ?? '-' }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-gray-600">Periode:</span>
                                        {{ $pkl->tgl_mulai?->format('d M Y') ?? '-' }}
                                        –
                                        {{ $pkl->tgl_selesai?->format('d M Y') ?? 'Belum selesai' }}
                                    </p>
                                </div>

                            </div>

                            {{-- Aksi --}}
                            <div class="flex flex-wrap gap-2 items-center">

                                @if ($suratBalasan)
                                    {{-- Lihat Surat (Modal Preview) --}}
                                    <button type="button" @click="openModal(@js(asset('storage/' . $suratBalasan->path_file)))"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50">
                                        <i class="mr-1.5 fa-regular fa-file-pdf"></i>
                                        Lihat Surat
                                    </button>

                                    {{-- Upload Ulang --}}
                                    <form action="{{ route('mitra.surat-balasan.store', $pkl) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <label
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg transition cursor-pointer hover:bg-blue-700">
                                            <i class="mr-1.5 fa-solid fa-rotate"></i>
                                            Upload Ulang

                                            <input type="file" name="surat_balasan" accept=".pdf,application/pdf"
                                                class="hidden" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                @else
                                    {{-- Upload Pertama --}}
                                    <form action="{{ route('mitra.surat-balasan.store', $pkl) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <label
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg transition cursor-pointer hover:bg-blue-700">
                                            <i class="mr-1.5 fa-solid fa-upload"></i>
                                            Upload Surat

                                            <input type="file" name="surat_balasan" accept=".pdf,application/pdf"
                                                class="hidden" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">
                        <div class="flex justify-center items-center mx-auto w-10 h-10 bg-gray-100 rounded-full">
                            <i class="text-gray-400 fa-regular fa-folder-open"></i>
                        </div>

                        <h3 class="mt-3 text-sm font-semibold text-gray-800">
                            Belum ada mahasiswa PKL
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Belum terdapat mahasiswa yang sedang melaksanakan PKL di instansi Anda.
                        </p>
                    </div>
                @endforelse

                @if ($pkls->hasPages())
                    <div class="px-4 py-3 bg-white border-t border-gray-200">
                        {{ $pkls->links() }}
                    </div>
                @endif

            </div>

            {{-- Informasi --}}
            <div class="px-4 py-3 bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex gap-2.5">
                    <i class="mt-0.5 text-blue-600 fa-solid fa-circle-info"></i>

                    <div class="text-xs text-blue-800">
                        <p class="font-medium">
                            Informasi Surat Balasan
                        </p>

                        <p class="mt-0.5">
                            Surat balasan instansi harus diunggah dalam format PDF.
                            Jika terdapat kesalahan pada dokumen, Anda dapat melakukan
                            upload ulang dan dokumen sebelumnya akan diperbarui.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL PREVIEW PDF (Alpine Component pdfViewer) --}}
        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="closeModal()" @keydown.escape.window="closeModal()">
            <div
                class="relative flex flex-col w-full max-w-5xl h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl">
                {{-- Header --}}
                <div class="flex justify-between items-center px-4 py-2.5 bg-white border-b border-gray-200">
                    <div class="flex gap-2.5 items-center">
                        <div class="flex justify-center items-center w-7 h-7 text-blue-600 bg-blue-50 rounded-lg">
                            <i class="text-sm fa-solid fa-file-pdf"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">
                                Preview Surat Balasan Instansi
                            </h3>
                            <p class="text-[11px] text-gray-500">
                                Format Dokumen: PDF
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2 items-center">
                        <a :href="fileUrl" target="_blank"
                            class="inline-flex items-center px-2.5 py-1 text-[11px] font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                            title="Buka di tab baru">
                            <i class="mr-1.5 fa-solid fa-arrow-up-right-from-square"></i>
                            Tab Baru
                        </a>

                        <button type="button" @click="closeModal()"
                            class="flex justify-center items-center w-7 h-7 text-gray-500 bg-gray-100 rounded-full transition hover:bg-red-100 hover:text-red-600"
                            aria-label="Tutup">
                            ✕
                        </button>
                    </div>
                </div>

                {{-- Preview Iframe --}}
                <div class="flex-1 p-2 min-h-0 bg-gray-100 sm:p-3">
                    <iframe :src="fileUrl" class="w-full h-full bg-white rounded-xl shadow-inner"
                        frameborder="0"></iframe>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
