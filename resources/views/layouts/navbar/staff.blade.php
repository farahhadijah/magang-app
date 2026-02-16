<div class="space-y-1">
    {{-- VERIFIKASI PKL --}}
    <a
        href="{{ route('staff.pengajuan.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.pengajuan.index') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-circle-check"></i>
        Verifikasi PKL
    </a>

    {{-- HISTORI DITOLAK --}}
    <a
        href="{{ route('staff.pengajuan.histori_ditolak') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.pengajuan.histori_ditolak') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-circle-xmark"></i>
        Histori Ditolak
    </a>
    <a
        href="{{ route('staff.mitra.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.mitra.index') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-circle-check"></i>
        Manajemen Mitra
    </a>
</div>
