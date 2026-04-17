<x-app-layout>
    <x-slot name="title">
        Status Pengajuan PKL - MagangApp
    </x-slot>

    <div class="min-h-screen px-0 py-8 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 via-white to-emerald-50">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- JUMLAH DOKUMEN INVALID --}}
        @if ($jumlahInvalid > 0)
            <div class="relative overflow-hidden border-l-4 border-red-500 shadow-md bg-gradient-to-r from-red-50 to-rose-50 rounded-xl">
                <div class="absolute top-0 right-0 w-24 h-24 translate-x-8 -translate-y-8 bg-red-200 rounded-full opacity-20"></div>

                <div class="relative p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                            <i class="text-xl text-red-600 fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-red-800">
                                Ada {{ $jumlahInvalid }} dokumen yang perlu diperbaiki
                            </p>
                            <p class="text-sm text-red-700">
                                Silakan upload ulang dokumen yang ditandai invalid.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= BELUM ADA PENGAJUAN ================= --}}
        @if (!$pengajuan)
            <div class="relative overflow-hidden text-center border shadow-lg bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-amber-200">
                <div class="absolute top-0 right-0 w-32 h-32 translate-x-16 -translate-y-16 rounded-full bg-amber-200 opacity-20"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 -translate-x-12 translate-y-12 bg-orange-200 rounded-full opacity-20"></div>
                <div class="relative p-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-full shadow-lg bg-gradient-to-br from-amber-500 to-orange-600">
                        <i class="text-3xl text-white fa-regular fa-file-lines"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-800">
                        Belum Ada Pengajuan PKL
                    </h3>
                    <p class="mt-2 text-amber-700">
                        Kamu belum mengajukan PKL. Silakan ajukan terlebih dahulu.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('mahasiswa.pengajuan.create') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white transition-all duration-200 shadow-md bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 hover:shadow-lg">
                            <i class="fa-solid fa-paper-plane"></i>
                            Ajukan PKL
                        </a>
                    </div>
                </div>
            </div>
        @else

        {{-- ================= DITOLAK TU ================= --}}
        @if ($pengajuan->status === 'ditolak_tu')
            <div class="relative overflow-hidden border-l-4 border-red-500 shadow-md bg-gradient-to-r from-red-50 to-rose-50 rounded-xl">
                <div class="absolute top-0 right-0 w-24 h-24 translate-x-8 -translate-y-8 bg-red-200 rounded-full opacity-20"></div>
                <div class="relative p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                <i class="text-xl text-red-600 fa-solid fa-circle-exclamation"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800">
                                Pengajuan PKL ditolak oleh TU
                            </p>
                            <p class="mt-1 text-sm text-red-700">
                                <span class="font-medium">Alasan:</span>
                                <span class="italic">{{ $pengajuan->catatan_tu ?? 'Tidak ada catatan.' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= DITOLAK KAPRODI ================= --}}
        @if ($pengajuan->status === 'ditolak_kaprodi')
            <div class="relative overflow-hidden border-l-4 border-red-500 shadow-md bg-gradient-to-r from-red-50 to-rose-50 rounded-xl">
                <div class="absolute top-0 right-0 w-24 h-24 translate-x-8 -translate-y-8 bg-red-200 rounded-full opacity-20"></div>
                <div class="relative p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                <i class="text-xl text-red-600 fa-solid fa-circle-exclamation"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800">
                                Pengajuan PKL ditolak oleh Kaprodi
                            </p>
                            <p class="mt-1 text-sm text-red-700">
                                <span class="font-medium">Alasan:</span>
                                <span class="italic">{{ $pengajuan->catatan_kaprodi ?? 'Tidak ada catatan.' }}</span>
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('mahasiswa.pengajuan.create') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700">
                                    <i class="fa-solid fa-rotate-right"></i>
                                    Ajukan Ulang PKL
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= TIMELINE ================= --}}
        @php
            $status = $pengajuan->status;

            $timeline = [
                'pengajuan' => [
                    'label' => 'Pengajuan',
                    'icon' => 'fa-file-alt',
                    'active' => true,
                ],
                'verifikasi_tu' => [
                    'label' => 'Verifikasi TU',
                    'icon' => 'fa-check-double',
                    'active' => in_array($status, [
                        'diverifikasi_tu',
                        'pending_kaprodi',
                        'disetujui'
                    ]),
                ],
                'kaprodi' => [
                    'label' => 'Persetujuan Kaprodi',
                    'icon' => 'fa-user-graduate',
                    'active' => in_array($status, [
                        'pending_kaprodi',
                        'disetujui'
                    ]),
                ],
                'selesai' => [
                    'label' => 'Selesai',
                    'icon' => 'fa-flag-checkered',
                    'active' => $status === 'disetujui',
                ],
            ];
        @endphp

        <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600">
                        <i class="text-sm text-white fa-solid fa-timeline"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Timeline Pengajuan PKL
                    </h3>
                </div>
            </div>

            <div class="p-6">
                <div class="relative flex justify-between">
                    <div class="absolute left-0 right-0 h-1 bg-gray-200 top-6"></div>
                    <div class="absolute left-0 h-1 transition-all duration-500 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 top-6"
                         style="width: {{ (collect($timeline)->where('active', true)->count() / count($timeline)) * 100 }}%">
                    </div>

                    @foreach ($timeline as $step)
                        <div class="relative z-10 flex flex-col items-center w-1/4">
                            <div class="
                                w-12 h-12 flex items-center justify-center rounded-full font-semibold transition-all duration-300
                                {{ $step['active']
                                    ? 'bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-200'
                                    : 'bg-gray-200 text-gray-500'
                                }}">
                                <i class="fa-solid {{ $step['icon'] }} text-lg"></i>
                            </div>
                            <span class="mt-2 text-xs font-medium text-center
                                {{ $step['active'] ? 'text-green-700' : 'text-gray-400' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ================= STATUS CARD ================= --}}
        <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
            <div class="p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-12 h-12 shadow-md bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <i class="text-xl text-white fa-solid fa-info-circle"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Status Pengajuan
                            </h3>
                            <p class="text-sm text-gray-500">
                                Tanggal Pengajuan: 
                                <strong class="text-gray-700">
                                    {{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}
                                </strong>
                            </p>
                        </div>
                    </div>

                    @php
                        $badge = match($pengajuan->status) {
                            'pending_tu'       => 'bg-amber-100 text-amber-800 border-amber-200',
                            'pending_kaprodi'  => 'bg-blue-100 text-blue-800 border-blue-200',
                            'disetujui'        => 'bg-green-100 text-green-800 border-green-200',
                            'ditolak_tu'       => 'bg-red-100 text-red-800 border-red-200',
                            default            => 'bg-gray-100 text-gray-800 border-gray-200',
                        };
                        
                        $icon = match($pengajuan->status) {
                            'pending_tu'       => 'fa-clock',
                            'pending_kaprodi'  => 'fa-hourglass-half',
                            'disetujui'        => 'fa-circle-check',
                            'ditolak_tu'       => 'fa-circle-xmark',
                            default            => 'fa-question-circle',
                        };
                        
                        $labelStatus = match($pengajuan->status) {
                            'pending_tu'      => 'Menunggu Verifikasi TU',
                            'pending_kaprodi' => 'Menunggu Persetujuan Kaprodi',
                            'disetujui'       => 'Disetujui',
                            'ditolak_tu'      => 'Ditolak TU',
                            default           => ucfirst($pengajuan->status),
                        };
                    @endphp

                    <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full border {{ $badge }}">
                        <i class="fa-solid {{ $icon }}"></i>
                        {{ $labelStatus }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ================= DATA TEMPAT ================= --}}
        <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600">
                        <i class="text-sm text-white fa-solid fa-building"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Data Tempat PKL
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs font-medium tracking-wider text-gray-500 uppercase">Nama Tempat</p>
                        <p class="mt-1 text-base font-semibold text-gray-800">{{ optional($pengajuan->tempatPkl)->nama_tempat ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs font-medium tracking-wider text-gray-500 uppercase">Jenis Instansi</p>
                        <p class="mt-1 text-base font-semibold text-gray-800">{{ optional($pengajuan->tempatPkl)->jenis_tempat ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl sm:col-span-2">
                        <p class="text-xs font-medium tracking-wider text-gray-500 uppercase">No Telepon</p>
                        <p class="mt-1 text-base font-semibold text-gray-800">{{ optional($pengajuan->tempatPkl)->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600">
                        <i class="text-sm text-white fa-solid fa-file-pdf"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Dokumen Pengajuan
                    </h3>
                </div>
            </div>
            <div class="p-6">
                @php
                    $khsList = $pengajuan->dokumenPengajuan->where('jenis_dokumen', 'KHS');
                    $lainnya = $pengajuan->dokumenPengajuan->where('jenis_dokumen', '!=', 'KHS');
                @endphp

                {{-- ================= KHS MULTIPLE ================= --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="text-green-600 fa-solid fa-book-open"></i>
                        <h4 class="font-semibold text-gray-800">
                            KHS Semester 1 - Terakhir
                        </h4>
                        <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                            {{ $khsList->count() }} file
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($khsList as $index => $doc)
                            <div class="p-4 transition-all duration-200 group bg-gray-50 rounded-xl hover:bg-gray-100">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                                            <i class="text-lg text-red-500 fa-regular fa-file-pdf"></i>
                                        </div>
                                        <div>
                                            <a href="{{ asset('storage/' . $doc->path_file) }}" target="_blank"
                                               class="font-medium text-green-700 transition hover:text-green-800 hover:underline">
                                                KHS File {{ $index + 1 }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-0.5">Klik untuk melihat file</p>
                                        </div>
                                    </div>

                                    @php
                                        $badgeColor = match($doc->status_verifikasi) {
                                            'valid'   => 'bg-green-100 text-green-800',
                                            'invalid' => 'bg-red-100 text-red-800',
                                            default   => 'bg-amber-100 text-amber-800',
                                        };
                                        $badgeIcon = match($doc->status_verifikasi) {
                                            'valid'   => 'fa-check-circle',
                                            'invalid' => 'fa-times-circle',
                                            default   => 'fa-clock',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                        <i class="fa-solid {{ $badgeIcon }}"></i>
                                        {{ ucfirst($doc->status_verifikasi) }}
                                    </span>
                                </div>

                                @if ($doc->status_verifikasi === 'invalid' && $doc->catatan)
                                    <div class="p-3 mt-3 border-l-2 border-red-400 rounded-lg bg-red-50">
                                        <p class="text-sm text-red-700">
                                            <i class="fa-solid fa-message"></i>
                                            <strong class="ml-1">Catatan TU:</strong>
                                            <span class="ml-1 italic">{{ $doc->catatan }}</span>
                                        </p>
                                    </div>
                                @endif

                                @if ($pengajuan->status === 'ditolak_tu' && $doc->status_verifikasi === 'invalid')
                                    <form method="POST"
                                        action="{{ route('mahasiswa.pengajuan.dokumen.upload-ulang', $doc->id) }}"
                                        enctype="multipart/form-data"
                                        class="pt-3 mt-3 border-t border-gray-200">
                                        @csrf
                                        <div class="flex flex-col gap-3 sm:flex-row">
                                            <div class="flex-1">
                                                <input type="file"
                                                    name="dokumen"
                                                    required
                                                    accept=".pdf,.doc,.docx"
                                                    class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                            </div>
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700">
                                                <i class="fa-solid fa-upload"></i>
                                                Upload Ulang
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="p-6 text-center bg-gray-50 rounded-xl">
                                <i class="mb-2 text-3xl text-gray-400 fa-regular fa-folder-open"></i>
                                <p class="text-sm text-gray-500">Tidak ada KHS diunggah.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ================= DOKUMEN LAINNYA ================= --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="text-green-600 fa-solid fa-folder-open"></i>
                        <h4 class="font-semibold text-gray-800">
                            Dokumen Lainnya
                        </h4>
                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                            {{ $lainnya->count() }} file
                        </span>
                    </div>

                    <div class="space-y-3">
                        @foreach ($lainnya as $doc)
                            <div class="p-4 transition-all duration-200 group bg-gray-50 rounded-xl hover:bg-gray-100">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                                            @php
                                                $docIcon = match($doc->jenis_dokumen) {
                                                    'Pembayaran' => 'fa-receipt',
                                                    'Studi Tour' => 'fa-ticket',
                                                    'Form PKN' => 'fa-file-alt',
                                                    default => 'fa-file',
                                                };
                                            @endphp
                                            <i class="fa-solid {{ $docIcon }} text-green-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <a href="{{ asset('storage/' . $doc->path_file) }}" target="_blank"
                                               class="font-medium text-green-700 transition hover:text-green-800 hover:underline">
                                                {{ $doc->jenis_dokumen }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-0.5">Klik untuk melihat file</p>
                                        </div>
                                    </div>

                                    @php
                                        $badgeColor = match($doc->status_verifikasi) {
                                            'valid'   => 'bg-green-100 text-green-800',
                                            'invalid' => 'bg-red-100 text-red-800',
                                            default   => 'bg-amber-100 text-amber-800',
                                        };
                                        $badgeIcon = match($doc->status_verifikasi) {
                                            'valid'   => 'fa-check-circle',
                                            'invalid' => 'fa-times-circle',
                                            default   => 'fa-clock',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                        <i class="fa-solid {{ $badgeIcon }}"></i>
                                        {{ ucfirst($doc->status_verifikasi) }}
                                    </span>
                                </div>

                                @if ($doc->status_verifikasi === 'invalid' && $doc->catatan)
                                    <div class="p-3 mt-3 border-l-2 border-red-400 rounded-lg bg-red-50">
                                        <p class="text-sm text-red-700">
                                            <i class="fa-solid fa-message"></i>
                                            <strong class="ml-1">Catatan TU:</strong>
                                            <span class="ml-1 italic">{{ $doc->catatan }}</span>
                                        </p>
                                    </div>
                                @endif

                                @if ($pengajuan->status === 'ditolak_tu' && $doc->status_verifikasi === 'invalid')
                                    <form method="POST"
                                        action="{{ route('mahasiswa.pengajuan.dokumen.upload-ulang', $doc->id) }}"
                                        enctype="multipart/form-data"
                                        class="pt-3 mt-3 border-t border-gray-200">
                                        @csrf
                                        <div class="flex flex-col gap-3 sm:flex-row">
                                            <div class="flex-1">
                                                <input type="file"
                                                    name="dokumen"
                                                    required
                                                    accept=".pdf,.doc,.docx,.jpg,.png"
                                                    class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                            </div>
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 rounded-lg shadow-sm bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700">
                                                <i class="fa-solid fa-upload"></i>
                                                Upload Ulang
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= SURAT PENGANTAR ================= --}}
        @if($pengajuan->pkl && $pengajuan->pkl->suratPengantar)
            <div class="relative overflow-hidden border border-green-200 shadow-lg bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                <div class="absolute top-0 right-0 w-32 h-32 translate-x-16 -translate-y-16 bg-green-200 rounded-full opacity-30"></div>
                <div class="relative p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-12 h-12 shadow-md bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                                <i class="text-xl text-white fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-green-800">
                                    Surat Pengantar PKL
                                </h4>
                                <p class="text-sm text-green-600">Surat pengantar resmi untuk instansi tempat PKL</p>
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.surat-pengantar.download', $pengajuan->pkl->suratPengantar->id) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-download"></i>
                            Download Surat Pengantar
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= INFO PKL (SETELAH DISETUJUI) ================= --}}
        @if ($pengajuan->pkl)
            <div class="border border-blue-200 shadow-md bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                            <i class="text-white fa-solid fa-chalkboard-user"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Informasi PKL
                        </h3>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="p-4 bg-white/60 rounded-xl backdrop-blur-sm">
                            <p class="text-xs font-medium tracking-wider text-blue-600 uppercase">Dosen Pembimbing</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">{{ optional($pengajuan->pkl->dosen)->nama ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-white/60 rounded-xl backdrop-blur-sm">
                            <p class="text-xs font-medium tracking-wider text-blue-600 uppercase">Status PKL</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">{{ ucfirst($pengajuan->pkl->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @endif
    </div>
</x-app-layout>