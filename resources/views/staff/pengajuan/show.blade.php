<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Detail Pengajuan PKL
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- INFORMASI MAHASISWA --}}
        <div class="p-6 space-y-2 border border-green-200 rounded-lg bg-green-50">
            <p><strong>Nama:</strong> {{ $pengajuan->mahasiswa->nama }}</p>
            <p><strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim }}</p>
            <p><strong>Instansi:</strong> {{ $pengajuan->tempatPkl->nama_tempat }}</p>
            <p><strong>Jenis:</strong> {{ $pengajuan->tempatPkl->jenis_tempat }}</p>
            <p><strong>No HP:</strong> {{ $pengajuan->tempatPkl->no_hp }}</p>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <div class="p-6 bg-white border border-green-200 rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-green-900">
                Verifikasi Dokumen
            </h3>

            @forelse ($pengajuan->dokumenPengajuan as $dokumen)
                <div id="dokumen-{{ $dokumen->id }}"
                class="p-4 mb-4 border rounded-lg
                    {{ $dokumen->isValid() ? 'border-green-300 bg-green-50'
                    : ($dokumen->isInvalid() ? 'border-red-300 bg-red-50'
                    : 'border-amber-300 bg-amber-50') }}">

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $dokumen->jenis_dokumen }}
                            </p>
                            <span class="inline-block px-2 py-0.5 text-xs rounded
                                {{ $dokumen->statusBadge()['class'] }}">
                                {{ $dokumen->statusBadge()['text'] }}
                            </span>
                        </div>

                        <a href="{{ asset('storage/'.$dokumen->path_file) }}"
                           target="_blank"
                           class="text-sm font-medium text-green-700 hover:text-green-900">
                            Lihat Dokumen
                        </a>
                    </div>

                    @if ($dokumen->isInvalid())
                        <div class="p-2 mt-2 text-sm text-red-700 bg-red-100 border border-red-200 rounded">
                            <strong>Catatan:</strong> {{ $dokumen->catatan }}
                        </div>
                    @endif

                    {{-- AKSI VERIFIKASI PER DOKUMEN --}}
                    @if ($dokumen->isPending())
                        <div class="flex gap-3 pt-3 mt-3 border-t">

                            <form method="POST"
                                  action="{{ route('staff.dokumen.valid', $dokumen->id) }}">
                                @csrf
                                <button id="tombol" class="px-4 py-1.5 text-sm text-white bg-green-600 rounded">
                                    Valid
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('staff.dokumen.invalid', $dokumen->id) }}"
                                  class="flex gap-2">
                                @csrf
                                <input type="text"
                                       name="catatan"
                                       required
                                       placeholder="Catatan wajib"
                                       class="px-2 py-1 text-sm border rounded">
                                <button class="px-4 py-1.5 text-sm text-white bg-red-600 rounded">
                                    Invalid
                                </button>
                            </form>

                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">
                    Tidak ada dokumen yang diunggah.
                </p>
            @endforelse
        </div>

        {{-- ================= AKSI FINAL TU ================= --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            @if ($pengajuan->bisaDisetujuiTu())
                <form method="POST" action="{{ route('staff.pengajuan.approve', $pengajuan->id) }}">
                    @csrf
                    <button class="px-3 py-2 text-white bg-green-800 rounded-sm">
                        Selesaikan Verifikasi Administrasi
                    </button>
                </form>

            @elseif ($pengajuan->sudahDiverifikasiTu())
                <span class="text-sm italic text-gray-500">
                    ✔ Verifikasi administrasi telah selesai
                </span>

            @elseif ($pengajuan->bisaDikembalikanKeMahasiswa())
                <form method="POST"
                    action="{{ route('staff.pengajuan.reject', $pengajuan->id) }}"
                    class="space-y-3">
                    @csrf
                    <textarea name="catatan"
                            required
                            rows="3"
                            placeholder="Catatan untuk mahasiswa (wajib)"
                            class="w-full p-2 text-sm border rounded"></textarea>
                    <button class="px-6 py-2 text-white bg-red-600 rounded">
                        Kembalikan ke Mahasiswa
                    </button>
                </form>

            @else
                <p class="text-sm text-gray-600">
                    Verifikasi belum selesai.
                </p>
            @endif
        </div>

        <a href="{{ route('staff.pengajuan.index') }}"
           class="inline-block text-green-700">← Kembali</a>

    </div>
</x-app-layout>
