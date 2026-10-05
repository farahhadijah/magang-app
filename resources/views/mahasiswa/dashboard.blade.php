<x-app-layout>
    <x-slot name="title">
        Dashboard Mahasiswa - MagangApp
    </x-slot>

    <div x-data="pdfViewer" class="py-6 rounded-md bg-gradient-to-b from-white from-[8%] via-green-200 to-green-500">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section with Green Theme -->
            <div
                class="overflow-hidden mb-8 bg-gradient-to-br from-green-600 via-green-500 to-emerald-500 rounded-2xl shadow-lg">
                <div class="overflow-hidden relative px-8 py-10">
                    <!-- Decorative Elements -->
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full opacity-10 translate-x-32 -translate-y-32">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full opacity-10 -translate-x-24 translate-y-24">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h1 class="ml-4 text-3xl font-bold text-white">
                                Sistem Informasi PKL
                            </h1>
                        </div>
                        <p class="text-lg text-green-50">
                            Pantau progres PKL kamu secara realtime dan kelola kegiatan dengan mudah
                        </p>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-6 sm:grid-cols-4">
                            <div class="p-3 bg-white bg-opacity-10 rounded-lg">
                                <p class="text-xs text-green-100">Status PKL</p>
                                <p class="text-sm font-semibold text-white">
                                    @if (!$pengajuan || !$pengajuan->pkl)
                                        Belum Mulai
                                    @else
                                        {{ $pengajuan->pkl->status == 'selesai' ? 'Selesai' : 'Aktif' }}
                                    @endif
                                </p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-10 rounded-lg">
                                <p class="text-xs text-green-100">Total Tugas</p>
                                <p class="text-sm font-semibold text-white">{{ $tugasList->count() }}</p>
                            </div>
                            <div class="p-3 bg-white bg-opacity-10 rounded-lg">
                                <p class="text-xs text-green-100">Logbook</p>
                                <p class="text-sm font-semibold text-white">{{ $logbookTotal ?? 0 }} entries</p>
                            </div>
                            @if (!$pengajuan || !$pengajuan->pkl || $pengajuan->pkl->status !== 'selesai')
                                <div class="p-3 bg-white bg-opacity-10 rounded-lg">
                                    <p class="text-xs text-green-100">Hari PKL</p>
                                    <p class="text-sm font-semibold text-white">Hari ke-{{ $hariPkl ?? 0 }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($pengajuan && $pengajuan->pesan_surat)
                <div class="p-4 mb-4 bg-green-50 rounded-xl border border-green-200">
                    <div class="flex gap-3 items-start">
                        <div class="mt-1 text-green-600">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-green-700">
                                Informasi Surat Pengantar
                            </h3>
                            <p class="mt-1 text-sm text-green-700">
                                {{ $pengajuan->pesan_surat }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($notifikasiPenolakan)
                <div
                    class="overflow-hidden relative mb-6 rounded-2xl border border-red-200 shadow-lg backdrop-blur-sm transition-all duration-300 bg-white/50 hover:shadow-red-100/50">
                    <!-- Decorative elements -->
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-400 to-rose-400 rounded-full opacity-10 blur-2xl">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-red-300 to-pink-300 rounded-full opacity-10 blur-xl">
                    </div>

                    <!-- Progress bar indicator -->
                    <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-red-500 to-rose-500 rounded-full"
                        style="width: 100%;"></div>

                    <div class="relative p-5">
                        <div class="flex gap-4 items-start">
                            <!-- Icon with modern design -->
                            <div class="flex-shrink-0">
                                <div
                                    class="flex justify-center items-center w-12 h-12 bg-gradient-to-br from-red-500 to-rose-500 rounded-2xl shadow-lg shadow-red-200">
                                    <i class="text-xl text-white fa-solid fa-circle-exclamation"></i>
                                </div>
                            </div>

                            <div class="flex-1">
                                <!-- Title with modern typography -->
                                <p class="text-base font-semibold text-gray-900">
                                    @if ($notifikasiPenolakan['tipe'] === 'tu')
                                        Pengajuan PKL ditolak oleh TU
                                    @else
                                        Pengajuan PKL ditolak oleh Kaprodi
                                    @endif
                                </p>

                                <!-- Message with better spacing -->
                                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                                    <span class="font-medium text-gray-700">Alasan:</span>
                                    <span class="text-gray-600">
                                        {{ $notifikasiPenolakan['pesan'] ?? 'Tidak ada catatan.' }}
                                    </span>
                                </p>

                                <!-- Action button with modern styling -->
                                <div class="mt-4">
                                    <a href="{{ route('mahasiswa.pengajuan.status') }}"
                                        class="inline-flex gap-2 items-center text-sm font-medium text-red-600 transition-colors duration-200 hover:text-red-700 group">
                                        <span>Lihat detail pengajuan</span>
                                        <i
                                            class="text-xs transition-transform duration-200 fa-solid fa-arrow-right group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ================= TIMELINE ================= --}}
            @if ($pengajuan)
                @php
                    $pengajuanStatus = $pengajuan->status;
                    $pklStatus = $pengajuan->pkl?->status;
                    $steps = [
                        'Pengajuan' => true,
                        'Verifikasi TU' => in_array($pengajuanStatus, [
                            'diverifikasi_tu',
                            'pending_kaprodi',
                            'disetujui',
                        ]),
                        'Persetujuan Kaprodi' => in_array($pengajuanStatus, ['pending_kaprodi', 'disetujui']),
                        'PKL Berjalan' => $pklStatus === 'aktif' || $pklStatus === 'selesai',
                        'Selesai' => $pklStatus === 'selesai',
                    ];

                    $currentStep = 0;
                    foreach ($steps as $active) {
                        if ($active) {
                            $currentStep++;
                        } else {
                            break;
                        }
                    }
                @endphp

                <div class="p-6 mb-8 rounded-xl border border-green-100 shadow-sm bg-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-green-800">
                            <span class="flex items-center">
                                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Timeline PKL
                            </span </h3>
                            <span class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                Tahap {{ $currentStep }} dari 5
                            </span>
                    </div>

                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div class="absolute right-0 left-0 top-4 h-2 bg-amber-300 rounded-full"></div>

                        <!-- Active Progress -->
                        <div class="absolute left-0 top-4 h-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full transition-all duration-500"
                            style="width: {{ ($currentStep / 5) * 100 }}%">
                        </div>

                        <!-- Steps -->
                        <div class="flex relative justify-between">
                            @foreach ($steps as $label => $active)
                                <div class="flex relative z-10 flex-col items-center">
                                    <div
                                        class="
                                        w-10 h-10 flex items-center justify-center rounded-full font-semibold text-sm
                                        transition-all duration-300
                                        {{ $active
                                            ? 'bg-green-600 text-white shadow-lg shadow-green-200'
                                            : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                                        @if ($active && $loop->index < $currentStep)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </div>
                                    <span
                                        class="mt-2 text-xs font-medium text-center
                                        {{ $active ? 'text-green-700' : 'text-gray-400' }}">
                                        {{ $label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Cards with Enhanced Green Theme -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                {{-- STATUS PKL --}}
                <div
                    class="overflow-hidden relative rounded-xl border border-green-100 shadow-sm transition-all duration-300 bg-white/30 group hover:shadow-md">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full opacity-50">
                    </div>
                    <div class="relative p-6">
                        <div class="flex justify-between items-center mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Status</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Status PKL</p>
                        <div class="mt-2">
                            @if (!$pengajuan)
                                <span
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Belum Mengajukan
                                </span>
                            @else
                                @php
                                    $badge = match ($pengajuan->status) {
                                        'pending_tu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'diverifikasi_tu' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending_kaprodi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'disetujui' => 'bg-green-50 text-green-700 border-green-200',
                                        'ditolak_tu', 'ditolak_kaprodi' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                    $icon = match ($pengajuan->status) {
                                        'pending_tu' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'diverifikasi_tu' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'pending_kaprodi' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'disetujui' => 'M5 13l4 4L19 7',
                                        'ditolak_tu', 'ditolak_kaprodi' => 'M6 18L18 6M6 6l12 12',
                                        default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    };
                                    $label = match ($pengajuan->status) {
                                        'pending_tu' => 'Menunggu Verifikasi TU',
                                        'diverifikasi_tu' => 'Terverifikasi Administrasi',
                                        'pending_kaprodi' => 'Menunggu Persetujuan Kaprodi',
                                        'disetujui' => 'Disetujui Kaprodi',
                                        'ditolak_tu' => 'Ditolak TU',
                                        'ditolak_kaprodi' => 'Ditolak Kaprodi',
                                        default => ucfirst($pengajuan->status),
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-lg {{ $badge }}">
                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $icon }}" />
                                    </svg>
                                    {{ $label }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TEMPAT PKL --}}
                <div
                    class="overflow-hidden relative rounded-xl border border-green-100 shadow-sm transition-all duration-300 bg-white/30 group hover:shadow-md">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full opacity-50">
                    </div>
                    <div class="relative p-6">
                        <div class="flex justify-between items-center mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Lokasi</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Tempat PKL</p>
                        <h3 class="text-xl font-bold text-gray-600">
                            {{ optional($pengajuan?->tempatPkl)->nama_tempat ?? '-' }}
                        </h3>
                        @if ($pengajuan?->tempatPkl?->alamat)
                            <p class="mt-2 text-xs text-gray-500">{{ Str::limit($pengajuan->tempatPkl->alamat, 50) }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- DOSEN PEMBIMBING --}}
                <div
                    class="overflow-hidden relative rounded-xl border border-green-100 shadow-sm transition-all duration-300 bg-white/30 group hover:shadow-md">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full opacity-50">
                    </div>
                    <div class="relative p-6">
                        <div class="flex justify-between items-center mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Pembimbing</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Dosen Pembimbing</p>
                        <h3 class="text-xl font-bold text-gray-600">
                            {{ optional($pengajuan?->pkl?->dosen)->nama ?? '-' }}
                        </h3>
                        @if ($pengajuan?->pkl?->dosen?->email)
                            <p class="mt-2 text-xs text-gray-500 truncate" title="{{ $pengajuan->pkl->dosen->email }}">{{ $pengajuan->pkl->dosen->email }}</p>
                        @endif
                    </div>
                </div>

                {{-- SURAT BALASAN INSTANSI --}}
                <div
                    class="overflow-hidden relative rounded-xl border border-green-100 shadow-sm transition-all duration-300 bg-white/30 group hover:shadow-md">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full opacity-50">
                    </div>
                    <div class="relative p-6 flex flex-col justify-between h-full">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Dokumen</span>
                            </div>
                            <p class="mb-1 text-sm text-gray-500">Surat Balasan</p>
                            <h3 class="text-base font-bold text-gray-700">
                                Instansi Mitra
                            </h3>

                            <div class="mt-2">
                                @if ($pengajuan?->pkl?->suratBalasan)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100/80 rounded-full border border-green-200">
                                        <i class="mr-1.5 fa-solid fa-circle-check text-green-600"></i>
                                        Sudah Diunggah
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100/80 rounded-full border border-amber-200">
                                        <i class="mr-1.5 fa-solid fa-clock text-amber-600"></i>
                                        Belum Diunggah
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-green-100/60">
                            @if ($pengajuan?->pkl?->suratBalasan)
                                <button type="button"
                                    @click="openModal(@js(asset('storage/' . $pengajuan->pkl->suratBalasan->path_file)))"
                                    class="inline-flex justify-center items-center gap-1.5 px-3 py-2 w-full text-xs font-medium text-white bg-green-600 rounded-lg shadow-sm transition hover:bg-green-700">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Lihat Surat Balasan
                                </button>
                            @else
                                <p class="text-xs text-gray-400 italic">
                                    Menunggu unggahan dari mitra
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= LOGBOOK & TUGAS GRID ================= --}}
            <div class="grid grid-cols-1 gap-8 mt-5 lg:grid-cols-2">
                {{-- LOGBOOK PROGRESS --}}
                @if ($pengajuan && $pengajuan->pkl)
                    <div
                        class="overflow-hidden bg-white/30 border border-green-100 shadow-sm rounded-xl {{ $isPklSelesai ? 'hidden' : '' }}">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-green-800">
                                    <span class="flex items-center">
                                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Progress Logbook
                                    </span>
                                </h3>
                                <a href="{{ !$isPklSelesai ? route('mahasiswa.logbook.index') : '#' }}"
                                    class="text-sm font-medium 
                                    {{ $isPklSelesai
                                        ? 'text-gray-400 cursor-not-allowed pointer-events-none'
                                        : 'text-green-600 hover:text-green-700' }}">
                                    Lihat Semua →
                                </a>
                            </div>

                            @if (!$isPklSelesai)
                                <!-- Progress Circle -->
                                <div class="flex justify-center items-center mb-6">
                                    <div class="relative w-32 h-32">
                                        @php
                                            $progress =
                                                $targetHari > 0 ? min(($logbookTotal ?? 0) / $targetHari, 1) : 0;

                                            $circumference = 351.86;
                                        @endphp
                                        <svg class="w-32 h-32 transform -rotate-90">
                                            <circle class="text-gray-200" stroke-width="8" stroke="currentColor"
                                                fill="transparent" r="56" cx="64" cy="64" />
                                            <circle class="text-green-500 transition-all duration-1000"
                                                stroke-width="8" stroke="currentColor" fill="transparent" r="56"
                                                cx="64" cy="64" stroke-dasharray="351.86"
                                                stroke-dashoffset="{{ $circumference - $circumference * $progress }}"
                                                stroke-linecap="round" />
                                        </svg>
                                        <div class="flex absolute inset-0 flex-col justify-center items-center">
                                            <span
                                                class="text-2xl font-bold text-gray-600">{{ $logbookTotal ?? 0 }}</span>
                                            <span class="text-xs text-gray-500">Logbook</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- 🔥 Mode PKL Selesai (tanpa circle) -->
                                <div class="flex flex-col justify-center items-center mb-6">
                                    <span class="text-3xl font-bold text-gray-600">{{ $logbookTotal }}</span>
                                    <span class="text-sm text-gray-500">Total Logbook</span>
                                </div>
                            @endif

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-4 mt-4 text-center">
                                <div class="p-3 rounded-lg bg-green-50/40">
                                    <p class="text-xs text-gray-500">Hari PKL</p>
                                    <p class="text-xl font-bold text-gray-600">{{ $hariPkl }}</p>
                                </div>
                                <div class="p-3 rounded-lg bg-green-50/40">
                                    <p class="text-xs text-gray-500">Terisi</p>
                                    <p class="text-xl font-bold text-green-600">{{ $logbookTotal }}</p>
                                </div>
                                <div
                                    class="p-3 {{ $logbookKosong > 0 ? 'bg-red-50/40' : 'bg-green-50/40' }} rounded-lg">
                                    <p class="text-xs text-gray-500">Kosong</p>
                                    <p
                                        class="text-xl font-bold {{ $logbookKosong > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $logbookKosong }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TUGAS TERBARU --}}
                @if ($tugasList->isNotEmpty())
                    <div class="overflow-hidden rounded-xl border border-green-100 shadow-sm bg-white/20">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-green-800">
                                    <span class="flex items-center">
                                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Tugas Terbaru
                                    </span>
                                </h3>
                                <a href="{{ route('mahasiswa.tugas') }}"
                                    class="text-sm font-medium text-green-600 hover:text-green-700">
                                    Lihat Semua →
                                </a>
                            </div>

                            @php
                                $deadline = \Carbon\Carbon::parse($tugas->deadline);
                                $now = \Carbon\Carbon::now();
                                $isOverdue = $deadline->isPast() && !$submit;
                                $daysLeft = $now->diffInDays($deadline, false);
                            @endphp

                            <div class="p-4 {{ $isOverdue ? 'bg-red-50' : 'bg-green-50' }} rounded-lg bg-white/40">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 {{ $isOverdue ? 'bg-red-200' : 'bg-green-200' }} rounded-lg">
                                            <svg class="w-6 h-6 {{ $isOverdue ? 'text-red-600' : 'text-green-600' }}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <h4 class="text-base font-semibold text-gray-600">
                                            {{ $tugas->judul }}
                                        </h4>

                                        @if ($tugas->deskripsi)
                                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                                {{ $tugas->deskripsi }}
                                            </p>
                                        @endif

                                        <div class="flex items-center mt-3">
                                            <svg class="mr-1 w-4 h-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                Deadline: {{ $deadline->format('d M Y H:i') }}
                                                @if ($isOverdue)
                                                    <span class="ml-2 text-xs">(Terlewat)</span>
                                                @elseif($daysLeft > 0)
                                                    <span class="ml-2 text-xs text-gray-400">(Sisa
                                                        {{ floor($daysLeft) }} hari)</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center mt-4">
                                            <div>
                                                @if (!$submit)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                                        <svg class="mr-1 w-3 h-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Belum dikumpulkan
                                                    </span>
                                                @elseif($submit->revisi)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                                        <svg class="mr-1 w-3 h-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        Perlu Revisi
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                        <svg class="mr-1 w-3 h-3" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Sudah dikumpulkan
                                                    </span>
                                                @endif
                                            </div>

                                            <a href="{{ route('mahasiswa.tugas.show', $tugas->id) }}"
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-green-600 rounded-lg transition-colors hover:bg-green-700">
                                                Detail
                                                <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL PREVIEW PDF (Alpine Component pdfViewer) --}}
        <div
            x-cloak
            x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="closeModal()"
            @keydown.escape.window="closeModal()"
        >
            <div
                class="relative flex flex-col w-full max-w-5xl h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-3.5 bg-white border-b border-gray-200">
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center w-8 h-8 text-green-600 bg-green-50 rounded-lg">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Preview Surat Balasan Instansi
                            </h3>
                            <p class="text-xs text-gray-500">
                                Format Dokumen: PDF
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a
                            :href="fileUrl"
                            target="_blank"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition"
                            title="Buka di tab baru"
                        >
                            <i class="mr-1.5 fa-solid fa-arrow-up-right-from-square"></i>
                            Tab Baru
                        </a>

                        <button
                            type="button"
                            @click="closeModal()"
                            class="flex items-center justify-center text-gray-500 transition bg-gray-100 rounded-full w-8 h-8 hover:bg-red-100 hover:text-red-600"
                            aria-label="Tutup"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                {{-- Preview Iframe --}}
                <div class="flex-1 min-h-0 p-2 sm:p-3 bg-gray-100">
                    <iframe
                        :src="fileUrl"
                        class="w-full h-full bg-white rounded-xl shadow-inner"
                        frameborder="0"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
