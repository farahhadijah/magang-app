<x-app-layout>
    <x-slot name="title">
        Detail Pengajuan PKL - Sibolang
    </x-slot>

    <div x-data="pdfViewer()" class="py-4 space-y-5 sm:py-6 sm:space-y-6">
        
        {{-- ================= INFORMASI MAHASISWA ================= --}}
        <div class="p-5 overflow-hidden border border-green-200 bg-gradient-to-r from-green-50 to-white rounded-xl sm:p-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                <h3 class="text-base font-semibold text-green-900 sm:text-lg">
                    Informasi Mahasiswa
                </h3>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->mahasiswa->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">NIM</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->mahasiswa->nim }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Semester</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->semester }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Instansi / Perusahaan</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->tempatPkl->nama_tempat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jenis Instansi</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->tempatPkl->jenis_tempat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">No. Telepon</p>
                    <p class="font-medium text-gray-800">{{ $pengajuan->tempatPkl->no_hp }}</p>
                </div>
            </div>
        </div>

        {{-- ================= DOKUMEN VERIFIKASI ================= --}}
        @php
            // Only show documents that are not KHS
            $dokumenNonKHS = $pengajuan->dokumenPengajuan->where('jenis_dokumen', '!=', 'KHS')->sortBy(function ($dokumen) {
                return match ($dokumen->jenis_dokumen) {
                    'Pembayaran' => 1,
                    'KRS' => 2,
                    'StudiTour' => 3,
                    default => 99,
                };
            });
        @endphp

        @if($dokumenNonKHS->count())
            <div class="p-5 overflow-hidden bg-white border border-green-200 rounded-xl sm:p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                    <h3 class="text-base font-semibold text-green-900 sm:text-lg">
                        Verifikasi Dokumen Mahasiswa
                    </h3>
                </div>

                {{-- ================= DOKUMEN PENDUKUNG ================= --}}
                <div class="space-y-3 sm:space-y-4">
                    @foreach ($dokumenNonKHS as $dokumen)
                        <div id="dokumen-{{ $dokumen->id }}" 
                            class="p-4 transition-all rounded-xl border
                                {{ $dokumen->isValid() ? 'border-green-200 bg-green-50/50'
                                : ($dokumen->isInvalid() ? 'border-red-200 bg-red-50/50'
                                : 'border-amber-200 bg-amber-50/50') }}">
                            
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-3">
                                        <p class="text-sm font-semibold text-gray-800 sm:text-base">
                                            {{ $dokumen->jenis_dokumen }}
                                        </p>
                                        <span class="inline-block w-fit px-2 py-0.5 text-xs rounded-full
                                            {{ $dokumen->statusBadge()['class'] }}">
                                            {{ $dokumen->statusBadge()['text'] }}
                                        </span>
                                    </div>
                                    @if($dokumen->isInvalid() && $dokumen->catatan)
                                        <div class="mt-2 text-xs text-red-600 sm:text-sm">
                                            <span class="font-medium">Catatan:</span> {{ $dokumen->catatan }}
                                        </div>
                                    @endif
                                </div>

                                <button
                                    @click="openModal(@js(asset('storage/'.$dokumen->path_file)))"
                                    class="inline-flex items-center justify-center gap-1 px-3 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 sm:w-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Dokumen
                                </button>
                            </div>

                            {{-- Action Buttons for Pending Documents --}}
                            @if ($dokumen->isPending())
                                <div class="flex flex-col gap-3 pt-3 mt-3 border-t sm:flex-row sm:items-center sm:justify-end sm:gap-3">
                                    <form method="POST" action="{{ route('staff.dokumen.valid', $dokumen->id) }}" class="w-full sm:w-auto">
                                        @csrf
                                        <button class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 sm:w-auto sm:px-5">
                                            ✓ Valid
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('staff.dokumen.invalid', $dokumen->id) }}" class="flex flex-col w-full gap-2 sm:flex-row sm:gap-2 sm:w-auto">
                                        @csrf
                                        <input type="text"
                                               name="catatan"
                                               required
                                               placeholder="Catatan penolakan"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:w-64">
                                        <button class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 sm:w-auto sm:px-5">
                                            ✗ Invalid
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-8 text-center bg-white border border-gray-200 rounded-xl">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Belum ada dokumen yang diunggah.</p>
            </div>
        @endif

        {{-- ================= KEPUTUSAN FINAL ================= --}}
        <div class="p-5 overflow-hidden bg-white border border-gray-200 rounded-xl sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
                <h4 class="text-base font-semibold text-gray-800 sm:text-lg">
                    Keputusan Verifikasi Administrasi
                </h4>
            </div>

            @if ($pengajuan->bisaDisetujuiTu())
                <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg">
                    <strong>✓ Semua dokumen telah valid.</strong> Silakan selesaikan verifikasi dan kirim ke Kaprodi.
                </div>
                <form method="POST" action="{{ route('staff.pengajuan.approve', $pengajuan->id) }}">
                    @csrf
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Selesaikan & Kirim ke Kaprodi
                    </button>
                </form>

            @elseif ($pengajuan->bisaDikembalikanKeMahasiswa())
                <div class="p-4 mb-4 text-sm rounded-lg text-amber-800 bg-amber-100">
                    <strong>⚠️ Ada dokumen yang perlu diperbaiki.</strong> Berikan catatan untuk mahasiswa.
                </div>
                <form method="POST" action="{{ route('staff.pengajuan.reject', $pengajuan->id) }}" class="space-y-3">
                    @csrf
                    <textarea name="catatan"
                              required
                              rows="3"
                              placeholder="Catatan untuk mahasiswa (wajib diisi)&#10;Contoh: File pembayaran kurang jelas, silakan upload ulang dengan kualitas yang lebih baik."
                              class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"></textarea>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kembalikan ke Mahasiswa
                    </button>
                </form>

            @elseif ($pengajuan->sudahDiverifikasiTu())
                <div class="flex items-center gap-3 p-4 text-green-800 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">✔ Verifikasi administrasi telah selesai. Pengajuan telah diteruskan ke Kaprodi.</span>
                </div>

            @else
                <div class="flex items-center gap-3 p-4 rounded-lg text-amber-800 bg-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-sm">Selesaikan verifikasi seluruh dokumen terlebih dahulu sebelum melanjutkan.</span>
                </div>
            @endif
        </div>

        {{-- MODAL PREVIEW PDF --}}
        <div
            x-cloak
            x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click.self="closeModal()"
            @keydown.escape.window="closeModal()"
        >
            <div class="relative w-11/12 h-[90vh] bg-white rounded-2xl shadow-2xl">
                <button @click="closeModal()"
                        class="absolute z-10 flex items-center justify-center w-8 h-8 text-white bg-red-600 rounded-full -top-3 -right-3 hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                    ✕
                </button>
                <iframe :src="fileUrl" class="w-full h-full rounded-2xl" frameborder="0"></iframe>
            </div>
        </div>
    </div>

</x-app-layout>