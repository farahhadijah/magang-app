<x-app-layout>
    <x-slot name="title">
        Dashboard Mahasiswa - MagangApp
    </x-slot>

    <div class="py-6 rounded-md bg-gradient-to-b from-white from-[8%] via-green-200 to-green-500">
        @if ($notifikasiPenolakan)
            <div class="relative overflow-hidden backdrop-blur-sm bg-white/90 border border-red-200 shadow-lg rounded-2xl mb-6 transition-all duration-300 hover:shadow-red-100/50">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-400 to-rose-400 rounded-full blur-2xl opacity-10"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-red-300 to-pink-300 rounded-full blur-xl opacity-10"></div>
                
                <!-- Progress bar indicator -->
                <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-red-500 to-rose-500 rounded-full" style="width: 100%;"></div>

                <div class="relative p-5">
                    <div class="flex items-start gap-4">
                        <!-- Icon with modern design -->
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-rose-500 rounded-2xl shadow-lg shadow-red-200">
                                <i class="text-white text-xl fa-solid fa-circle-exclamation"></i>
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
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                <span class="font-medium text-gray-700">Alasan:</span>
                                <span class="text-gray-600">
                                    {{ $notifikasiPenolakan['pesan'] ?? 'Tidak ada catatan.' }}
                                </span>
                            </p>

                            <!-- Action button with modern styling -->
                            <div class="mt-4">
                                <a href="{{ route('mahasiswa.pengajuan.status') }}"
                                class="inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors duration-200 group">
                                    <span>Lihat detail pengajuan</span>
                                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section with Green Theme -->
            <div class="mb-8 overflow-hidden shadow-lg bg-gradient-to-br from-green-600 via-green-500 to-emerald-500 rounded-2xl">
                <div class="relative px-8 py-10 overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 translate-x-32 -translate-y-32 bg-white rounded-full opacity-10"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 -translate-x-24 translate-y-24 bg-white rounded-full opacity-10"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                            <div class="p-3 bg-white rounded-lg bg-opacity-10">
                                <p class="text-xs text-green-100">Status PKL</p>
                                <p class="text-sm font-semibold text-white">
                                    @if(!$pengajuan || !$pengajuan->pkl)
                                        Belum Mulai
                                    @else
                                        {{ $pengajuan->pkl->status == 'selesai' ? 'Selesai' : 'Aktif' }}
                                    @endif
                                </p>
                            </div>
                            <div class="p-3 bg-white rounded-lg bg-opacity-10">
                                <p class="text-xs text-green-100">Total Tugas</p>
                                <p class="text-sm font-semibold text-white">{{ $tugasList->count() }}</p>
                            </div>
                            <div class="p-3 bg-white rounded-lg bg-opacity-10">
                                <p class="text-xs text-green-100">Logbook</p>
                                <p class="text-sm font-semibold text-white">{{ $logbookTotal ?? 0 }} entries</p>
                            </div>
                            @if(!$pengajuan || !$pengajuan->pkl || $pengajuan->pkl->status !== 'selesai')
                            <div class="p-3 bg-white rounded-lg bg-opacity-10">
                                <p class="text-xs text-green-100">Hari PKL</p>
                                <p class="text-sm font-semibold text-white">Hari ke-{{ $hariPkl ?? 0 }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

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
                            'disetujui'
                        ]),
                        'Persetujuan Kaprodi' => in_array($pengajuanStatus, [
                            'pending_kaprodi',
                            'disetujui'
                        ]),
                        'PKL Berjalan' => $pklStatus === 'aktif' || $pklStatus === 'selesai',
                        'Selesai' => $pklStatus === 'selesai',
                    ];
                    
                    $currentStep = 0;
                    foreach($steps as $active) {
                        if($active) $currentStep++;
                        else break;
                    }
                @endphp
                
                <div class="p-6 mb-8 border border-green-100 shadow-sm bg-white/20 rounded-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-green-800">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Timeline PKL
                            </span
                        </h3>
                        <span class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                            Tahap {{ $currentStep }} dari 5
                        </span>
                    </div>
                    
                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div class="absolute left-0 right-0 h-2 rounded-full bg-amber-300 top-4"></div>
                        
                        <!-- Active Progress -->
                        <div class="absolute left-0 h-2 transition-all duration-500 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 top-4"
                             style="width: {{ ($currentStep / 5) * 100 }}%">
                        </div>
                        
                        <!-- Steps -->
                        <div class="relative flex justify-between">
                            @foreach ($steps as $label => $active)
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="
                                        w-10 h-10 flex items-center justify-center rounded-full font-semibold text-sm
                                        transition-all duration-300
                                        {{ $active
                                            ? 'bg-green-600 text-white shadow-lg shadow-green-200'
                                            : 'bg-white text-gray-400 border-2 border-gray-200'
                                        }}">
                                        @if($active && $loop->index < $currentStep)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </div>
                                    <span class="mt-2 text-xs font-medium text-center
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
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                {{-- STATUS PKL --}}
                <div class="relative overflow-hidden transition-all duration-300 border border-green-100 shadow-sm bg-white/30 group rounded-xl hover:shadow-md">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-50 bg-gradient-to-bl from-green-50 to-transparent"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Status</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Status PKL</p>
                        <div class="mt-2">
                            @if (!$pengajuan)
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum Mengajukan
                                </span>
                            @else
                                @php
                                    $badge = match($pengajuan->status) {
                                        'pending_tu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'diverifikasi_tu' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending_kaprodi' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'disetujui' => 'bg-green-50 text-green-700 border-green-200',
                                        'ditolak_tu', 'ditolak_kaprodi' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                    $icon = match($pengajuan->status) {
                                        'pending_tu' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'diverifikasi_tu' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'pending_kaprodi' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'disetujui' => 'M5 13l4 4L19 7',
                                        'ditolak_tu', 'ditolak_kaprodi' => 'M6 18L18 6M6 6l12 12',
                                        default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    };
                                    $label = match($pengajuan->status) {
                                        'pending_tu' => 'Menunggu Verifikasi TU',
                                        'diverifikasi_tu' => 'Terverifikasi Administrasi',
                                        'pending_kaprodi' => 'Menunggu Persetujuan Kaprodi',
                                        'disetujui' => 'Disetujui Kaprodi',
                                        'ditolak_tu' => 'Ditolak TU',
                                        'ditolak_kaprodi' => 'Ditolak Kaprodi',
                                        default => ucfirst($pengajuan->status),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-lg {{ $badge }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                                    </svg>
                                    {{ $label }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TEMPAT PKL --}}
                <div class="relative overflow-hidden transition-all duration-300 border border-green-100 shadow-sm bg-white/30 group rounded-xl hover:shadow-md">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-50 bg-gradient-to-bl from-green-50 to-transparent"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Lokasi</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Tempat PKL</p>
                        <h3 class="text-xl font-bold text-gray-600">
                            {{ optional($pengajuan?->tempatPkl)->nama_tempat ?? '-' }}
                        </h3>
                        @if($pengajuan?->tempatPkl?->alamat)
                            <p class="mt-2 text-xs text-gray-500">{{ Str::limit($pengajuan->tempatPkl->alamat, 50) }}</p>
                        @endif
                    </div>
                </div>

                {{-- DOSEN PEMBIMBING --}}
                <div class="relative overflow-hidden transition-all duration-300 border border-green-100 shadow-sm bg-white/30 group rounded-xl hover:shadow-md">
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full opacity-50 bg-gradient-to-bl from-green-50 to-transparent"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium tracking-wider text-green-600 uppercase">Pembimbing</span>
                        </div>
                        <p class="mb-1 text-sm text-gray-500">Dosen Pembimbing</p>
                        <h3 class="text-xl font-bold text-gray-600">
                            {{ optional($pengajuan?->pkl?->dosen)->nama ?? '-' }}
                        </h3>
                        @if($pengajuan?->pkl?->dosen?->email)
                            <p class="mt-2 text-xs text-gray-500">{{ $pengajuan->pkl->dosen->email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ================= LOGBOOK & TUGAS GRID ================= --}}
            <div class="grid grid-cols-1 gap-8 mt-5 lg:grid-cols-2">
                {{-- LOGBOOK PROGRESS --}}
                @if($pengajuan && $pengajuan->pkl)
                    <div class="overflow-hidden bg-white/30 border border-green-100 shadow-sm rounded-xl {{ $isPklSelesai ? 'hidden' : '' }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-green-800">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
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
    
                            @if(!$isPklSelesai)
                                <!-- Progress Circle -->
                                <div class="flex items-center justify-center mb-6">
                                    <div class="relative w-32 h-32">
                                        @php
                                            $progress = ($targetHari > 0) 
                                                ? min(($logbookTotal ?? 0) / $targetHari, 1) 
                                                : 0;

                                            $circumference = 351.86;
                                        @endphp
                                        <svg class="w-32 h-32 transform -rotate-90">
                                            <circle
                                                class="text-gray-200"
                                                stroke-width="8"
                                                stroke="currentColor"
                                                fill="transparent"
                                                r="56"
                                                cx="64"
                                                cy="64"
                                            />
                                            <circle
                                                class="text-green-500 transition-all duration-1000"
                                                stroke-width="8"
                                                stroke="currentColor"
                                                fill="transparent"
                                                r="56"
                                                cx="64"
                                                cy="64"
                                                stroke-dasharray="351.86"
                                                stroke-dashoffset="{{ $circumference - ($circumference * $progress) }}"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <span class="text-2xl font-bold text-gray-600">{{ $logbookTotal ?? 0 }}</span>
                                            <span class="text-xs text-gray-500">Logbook</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- 🔥 Mode PKL Selesai (tanpa circle) -->
                                <div class="flex flex-col items-center justify-center mb-6">
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
                                <div class="p-3 {{ $logbookKosong > 0 ? 'bg-red-50/40' : 'bg-green-50/40' }} rounded-lg">
                                    <p class="text-xs text-gray-500">Kosong</p>
                                    <p class="text-xl font-bold {{ $logbookKosong > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $logbookKosong }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
    
                {{-- TUGAS TERBARU --}}
                @if($tugasList->isNotEmpty())
                    <div class="overflow-hidden border border-green-100 shadow-sm bg-white/20 rounded-xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-green-800">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Tugas Terbaru
                                    </span>
                                </h3>
                                <a href="{{ route('mahasiswa.tugas') }}" class="text-sm font-medium text-green-600 hover:text-green-700">
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
                                            <svg class="w-6 h-6 {{ $isOverdue ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <h4 class="text-base font-semibold text-gray-600">
                                            {{ $tugas->judul }}
                                        </h4>
                                        
                                        @if($tugas->deskripsi)
                                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                                {{ $tugas->deskripsi }}
                                            </p>
                                        @endif
    
                                        <div class="flex items-center mt-3">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                Deadline: {{ $deadline->format('d M Y H:i') }}
                                                @if($isOverdue)
                                                    <span class="ml-2 text-xs">(Terlewat)</span>
                                                @elseif($daysLeft > 0)
                                                    <span class="ml-2 text-xs text-gray-400">(Sisa {{ floor($daysLeft) }} hari)</span>
                                                @endif
                                            </span>
                                        </div>
    
                                        <div class="flex items-center justify-between mt-4">
                                            <div>
                                                @if(!$submit)
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Belum dikumpulkan
                                                    </span>
                                                @elseif($submit->revisi)
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                        Perlu Revisi
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Sudah dikumpulkan
                                                    </span>
                                                @endif
                                            </div>
    
                                            <a href="{{ route('mahasiswa.tugas.show', $tugas->id) }}" 
                                               class="inline-flex items-center px-3 py-1 text-xs font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                                Detail
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
    </div>
</x-app-layout>