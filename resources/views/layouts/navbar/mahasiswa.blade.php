@php
    $pengajuan = auth()->user()
        ->mahasiswa
        ?->pengajuanPkl()
        ->latest()
        ->first();

    /*
    |--------------------------------------------------------------------------
    | SIMULASI CEK NILAI D / E
    |--------------------------------------------------------------------------
    | Nanti diganti dari API SIAKAD asli
    |
    */

    $nilaiRemedial = app(\App\Services\SimulasiSiakadService::class)
        ->getNilaiRemedial(auth()->user()->mahasiswa);

    $punyaNilaiDE = count($nilaiRemedial) > 0;
@endphp

@php
    $pkl = $pengajuan?->pkl;

    // Use the single authoritative check
    $bolehUploadLaporan = $pkl?->isSiapUploadLaporan();

    $penilaianMitra = $pkl?->penilaianMitra;
@endphp

<div class="space-y-1">

    {{-- ================= REMEDIAL ================= --}}
    @if($punyaNilaiDE)

        <a
            href="{{ route('mahasiswa.remedial.index') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.remedial.*')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-triangle-exclamation"></i>

            Remedial
        </a>

    @endif

    {{-- ================= AJUKAN PKL ================= --}}
    @if(!$punyaNilaiDE)

        @if(!$pengajuan)

            <a
                href="{{ route('mahasiswa.pengajuan.create') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5
                    rounded-lg
                    transition
                    hover:bg-green-800
                    {{ request()->routeIs('mahasiswa.pengajuan.create')
                        ? 'bg-green-800 text-amber-300'
                        : '' }}
                "
            >
                <i class="w-5 fa-solid fa-file-circle-plus"></i>

                Ajukan PKL
            </a>

        @else

            <div
                class="
                    flex items-center gap-3
                    px-4 py-2.5
                    rounded-lg
                    opacity-60
                    cursor-not-allowed
                "
            >
                <i class="w-5 fa-solid fa-file-circle-plus"></i>

                PKL Sudah Diajukan
            </div>

        @endif

    @else

        <div
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                opacity-60
                cursor-not-allowed
            "
        >
            <i class="w-5 fa-solid fa-lock"></i>

            Ajukan PKL Terkunci
        </div>

    @endif

    {{-- ================= STATUS PKL ================= --}}
    @if($pengajuan)

        <a
            href="{{ route('mahasiswa.pengajuan.status') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.pengajuan.status')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-circle-info"></i>

            Status PKL
        </a>

    @endif

    {{-- ================= LOGBOOK ================= --}}
    @if($pkl && $pkl->status !== 'selesai')

        <a
            href="{{ route('mahasiswa.logbook.index') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.logbook.*')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-book"></i>

            Logbook
        </a>

    @else

        <div
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                opacity-60
                cursor-not-allowed
            "
        >
            <i class="w-5 fa-solid fa-book"></i>

            Logbook
        </div>

    @endif

    {{-- ================= TUGAS DARI MITRA ================= --}}
    @if($pkl && $pkl->status !== 'selesai')

        <a
            href="{{ route('mahasiswa.tugas') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.tugas')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-list-check"></i>

            Tugas
        </a>

    @else

        <div
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                opacity-60
                cursor-not-allowed
            "
        >
            <i class="w-5 fa-solid fa-list-check"></i>

            Tugas
        </div>

    @endif

    {{-- ================= LAPORAN AKHIR ================= --}}
    @if($pkl)

        @if($bolehUploadLaporan)

            <a
                href="{{ route('mahasiswa.laporan.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5
                    rounded-lg
                    transition
                    hover:bg-green-800
                    {{ request()->routeIs('mahasiswa.laporan.*')
                        ? 'bg-green-800 text-amber-300'
                        : '' }}
                "
            >
                <i class="w-5 fa-solid fa-file-lines"></i>

                Laporan Akhir
            </a>

        @else

            <div
                class="
                    flex items-center gap-3
                    px-4 py-2.5
                    rounded-lg
                    opacity-60
                    cursor-not-allowed
                "
            >
                <i class="w-5 fa-solid fa-file-lines"></i>

                Laporan Akhir
            </div>

        @endif

    @endif

    {{-- ================= PENILAIAN MITRA ================= --}}
    @if($penilaianMitra)

        <a
            href="{{ route('mahasiswa.penilaianMitra.index') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.penilaianMitra.*')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-star"></i>

            Penilaian Mitra
        </a>

    @else

        <div
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                opacity-60
                cursor-not-allowed
            "
        >
            <i class="w-5 fa-solid fa-star"></i>

            Penilaian Mitra
        </div>

    @endif

    {{-- ================= NILAI PKL ================= --}}
    @if($pkl && $pkl->status === 'selesai')

        <a
            href="{{ route('mahasiswa.penilaianMitra.index') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.nilai.*')
                    ? 'bg-green-800 text-amber-300'
                    : '' }}
            "
        >
            <i class="w-5 fa-solid fa-clipboard-check"></i>

            Nilai PKL
        </a>

    @else

        <div
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                opacity-60
                cursor-not-allowed
            "
        >
            <i class="w-5 fa-solid fa-clipboard-check"></i>

            Nilai PKL
        </div>

    @endif

</div>