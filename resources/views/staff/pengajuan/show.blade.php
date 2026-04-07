<x-app-layout>
    <x-slot name="title">
        Detail Pengajuan PKL - MagangApp
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ================= INFORMASI MAHASISWA ================= --}}
        <div class="p-6 space-y-2 border border-green-200 rounded-lg bg-green-50">
            <h3 class="mb-2 text-lg font-semibold text-green-900">
                Informasi Mahasiswa
            </h3>
            <p><strong>Nama:</strong> {{ $pengajuan->mahasiswa->nama }}</p>
            <p><strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim }}</p>
            <p><strong>Instansi:</strong> {{ $pengajuan->tempatPkl->nama_tempat }}</p>
            <p><strong>Jenis Instansi:</strong> {{ $pengajuan->tempatPkl->jenis_tempat }}</p>
            <p><strong>No HP:</strong> {{ $pengajuan->tempatPkl->no_hp }}</p>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        @php
            $dokumenTerurut = $pengajuan->dokumenPengajuan->sortBy(function($d) {
                return match($d->jenis_dokumen) {
                    'KHS' => 1,
                    'Pembayaran' => 2,
                    'StudiTour' => 3,
                    'FormPKN' => 4,
                    'KRSRemedial' => 5,
                    default => 99,
                };
            });

            $khs = $dokumenTerurut->where('jenis_dokumen', 'KHS');
            $lainnya = $dokumenTerurut->where('jenis_dokumen', '!=', 'KHS');
        @endphp

        <div class="p-6 bg-white border border-green-200 rounded-lg shadow-sm">
            <h3 class="mb-6 text-lg font-semibold text-green-900">
                Verifikasi Dokumen Mahasiswa
            </h3>

            {{-- ================= KHS GRID ================= --}}
            @if ($khs->count())
                <div class="mb-8">
                    <h4 class="mb-4 font-semibold text-gray-800">
                        Kartu Hasil Studi (KHS)
                    </h4>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($khs as $dokumen)
                            <div id="dokumen-{{ $dokumen->id }}" class="p-4 border rounded-lg
                                {{ $dokumen->isValid() ? 'border-green-300 bg-green-50'
                                : ($dokumen->isInvalid() ? 'border-red-300 bg-red-50'
                                : 'border-amber-300 bg-amber-50') }}">

                                <p class="mb-2 font-semibold text-gray-800">
                                    File KHS #{{ $loop->iteration }}
                                </p>

                                <span class="inline-block px-2 py-0.5 mb-3 text-xs rounded
                                    {{ $dokumen->statusBadge()['class'] }}">
                                    {{ $dokumen->statusBadge()['text'] }}
                                </span>

                                <button
                                    onclick="openModal('{{ asset('storage/'.$dokumen->path_file) }}')"
                                    class="block mb-3 text-sm font-medium text-green-700 hover:text-green-900">
                                    Lihat Dokumen
                                </button>

                                @if ($dokumen->isInvalid())
                                    <div class="p-2 mb-3 text-xs text-red-700 bg-red-100 border border-red-200 rounded">
                                        {{ $dokumen->catatan }}
                                    </div>
                                @endif

                                @if ($dokumen->isPending())
                                  <div class="flex flex-col gap-2 sm:flex-row sm:gap-2">
                                      <form method="POST"
                                            action="{{ route('staff.dokumen.valid', $dokumen->id) }}"
                                            class="w-full sm:w-auto">
                                          @csrf
                                          <button class="w-full px-3 py-2 text-sm text-white bg-green-600 rounded sm:px-3 sm:py-1 sm:text-xs">
                                              Valid
                                          </button>
                                      </form>

                                      <form method="POST"
                                            action="{{ route('staff.dokumen.invalid', $dokumen->id) }}"
                                            class="flex flex-col gap-2 sm:flex-row sm:gap-1 w-full sm:w-auto">
                                          @csrf
                                          <input type="text"
                                                 name="catatan"
                                                 required
                                                 placeholder="Catatan"
                                                 class="w-full px-2 py-2 text-sm border rounded sm:w-24 sm:px-1 sm:py-1 sm:text-xs">
                                          <button class="w-full px-3 py-2 text-sm text-white bg-red-600 rounded sm:w-auto sm:px-3 sm:py-1 sm:text-xs">
                                              Invalid
                                          </button>
                                      </form>
                                  </div>
                              @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ================= DOKUMEN LAIN ================= --}}
            @foreach ($lainnya as $dokumen)
            <div id="dokumen-{{ $dokumen->id }}" class="p-3 mb-3 border rounded-lg sm:p-4 sm:mb-4
                {{ $dokumen->isValid() ? 'border-green-300 bg-green-50'
                : ($dokumen->isInvalid() ? 'border-red-300 bg-red-50'
                : 'border-amber-300 bg-amber-50') }}">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 sm:text-base">
                            {{ $dokumen->jenis_dokumen }}
                        </p>
                        <span class="inline-block px-2 py-0.5 mt-1 text-xs rounded sm:mt-0
                            {{ $dokumen->statusBadge()['class'] }}">
                            {{ $dokumen->statusBadge()['text'] }}
                        </span>
                    </div>

                    <button
                        onclick="openModal('{{ asset('storage/'.$dokumen->path_file) }}')"
                        class="w-full py-2 text-sm font-medium text-center text-green-700 md:border-none border-green-300 rounded sm:w-auto sm:py-0 sm:border-0 hover:text-green-900">
                        Lihat Dokumen
                    </button>
                </div>

                @if ($dokumen->isInvalid())
                    <div class="p-2 mt-3 text-xs text-red-700 bg-red-100 border border-red-200 rounded sm:text-sm sm:mt-2">
                        <strong>Catatan:</strong> {{ $dokumen->catatan }}
                    </div>
                @endif

                @if ($dokumen->isPending())
                    <div class="flex flex-col gap-3 pt-3 mt-3 border-t sm:flex-row sm:gap-3">
                        <form method="POST"
                              action="{{ route('staff.dokumen.valid', $dokumen->id) }}"
                              class="w-full sm:w-auto">
                            @csrf
                            <button class="w-full px-3 py-2 text-sm text-white bg-green-600 rounded sm:px-4 sm:py-1.5 sm:w-auto">
                                Tandai Valid
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('staff.dokumen.invalid', $dokumen->id) }}"
                              class="flex flex-col gap-2 w-full sm:flex-row sm:gap-2 sm:w-auto">
                            @csrf
                            <input type="text"
                                   name="catatan"
                                   required
                                   placeholder="Catatan wajib"
                                   class="w-full px-2 py-2 text-sm border rounded sm:px-2 sm:py-1 sm:w-auto">
                            <button class="w-full px-3 py-2 text-sm text-white bg-red-600 rounded sm:px-4 sm:py-1.5 sm:w-auto">
                                Tandai Invalid
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach

        </div>

        {{-- ================= KEPUTUSAN FINAL TU ================= --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <h4 class="mb-3 font-semibold text-gray-800">
                Keputusan Verifikasi Administrasi
            </h4>

            @if ($pengajuan->bisaDisetujuiTu())
                <form method="POST" action="{{ route('staff.pengajuan.approve', $pengajuan->id) }}">
                    @csrf
                    <button class="px-4 py-2 text-white bg-green-700 rounded">
                        Selesaikan & Kirim ke Kaprodi
                    </button>
                </form>

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

            @elseif ($pengajuan->sudahDiverifikasiTu())
                <p class="text-sm italic text-gray-500">
                    ✔ Verifikasi administrasi telah selesai.
                </p>

            @else
                <p class="text-sm text-amber-700">
                    Selesaikan verifikasi seluruh dokumen terlebih dahulu.
                </p>
            @endif
        </div>

    </div>

    {{-- ================= MODAL PREVIEW PDF ================= --}}
    <div id="pdfModal"
         class="fixed inset-0 z-[999999] flex items-center justify-center hidden bg-black bg-opacity-60">
        <div class="relative w-11/12 h-[90vh] bg-white rounded-lg shadow-lg">
            <button onclick="closeModal()"
                    class="absolute z-10 w-10 h-10 text-2xl text-white bg-red-600 rounded-full -top-4 -right-4">
                ✕
            </button>
            <iframe id="pdfFrame"
                    src=""
                    class="w-full h-full rounded-lg"
                    frameborder="0">
            </iframe>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    window.openModal = function(url) {
        document.getElementById('pdfFrame').src = url;
        document.getElementById('pdfModal').classList.remove('hidden');
    }

    window.closeModal = function() {
        document.getElementById('pdfFrame').src = "";
        document.getElementById('pdfModal').classList.add('hidden');
    }

    document.getElementById('pdfModal')
        .addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeModal();
            }
        });

});
</script>
@endpush

</x-app-layout>