<div class="space-y-1">
    {{-- ================= VERIFIKASI PKL ================= --}}
    <a
        href="{{ route('staff.pengajuan.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.pengajuan.*') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-circle-check"></i>
        Verifikasi PKL
    </a>
    <a
        href="{{ route('staff.pengajuan.histori_ditolak') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.pengajuan.*') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-circle-check"></i>
        Histori Ditolak
    </a>


    <a href="{{ route('staff.pengajuan.histori_ditolak') }}"
   class="px-4 py-2 rounded-lg text-red-700 hover:bg-red-100">
    Histori Ditolak TU
</a>


</div>
