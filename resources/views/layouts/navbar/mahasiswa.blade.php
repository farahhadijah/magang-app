@php
    $pengajuan = auth()->user()
        ->mahasiswa
        ?->pengajuanPkl()
        ->latest()
        ->first();
@endphp
@php
    $pkl = $pengajuan?->pkl;
    // Use the single authoritative check
    $bolehUploadLaporan = $pkl?->isSiapUploadLaporan();
@endphp
<div class="space-y-1">
    {{-- ================= AJUKAN PKL ================= --}}
    @if(!$pengajuan)
        <a
            href="{{ route('mahasiswa.pengajuan.create') }}"
            class="
                flex items-center gap-3
                px-4 py-2.5
                rounded-lg
                transition
                hover:bg-green-800
                {{ request()->routeIs('mahasiswa.pengajuan.create') ? 'bg-green-800 text-amber-300' : '' }}
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
            {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-green-800 text-amber-300' : '' }}
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
                {{ request()->routeIs('mahasiswa.laporan.*') ? 'bg-green-800 text-amber-300' : '' }}
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
                {{ request()->routeIs('mahasiswa.pengajuan.status') ? 'bg-green-800 text-amber-300' : '' }}
            "
        >
            <i class="w-5 fa-solid fa-circle-info"></i>
            Status PKL
        </a>
    @endif
</div>
