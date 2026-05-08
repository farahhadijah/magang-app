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


    {{-- HISTORI --}}
    <a
        href="{{ route('staff.pengajuan.histori') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.pengajuan.histori') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-clock-rotate-left"></i>
        Histori
    </a>


    {{-- AKUN MITRA (Generate akun) --}}
    <a
        href="{{ route('staff.mitra.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.mitra.*') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-user-plus"></i>
        Akun Mitra
    </a>


    {{-- DATA MITRA (Manajemen mitra baru) --}}
    <a
        href="{{ route('staff.manajemen-mitra.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.manajemen-mitra.*') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-handshake"></i>
        Data Mitra
    </a>

    {{-- SURAT PENGANTAR PKL (BARU) --}}
    <a
        href="{{ route('staff.surat.index') }}"
        class="
            flex items-center gap-3
            px-4 py-2.5
            rounded-lg
            transition
            hover:bg-green-800
            {{ request()->routeIs('staff.surat.*') ? 'bg-green-800 text-amber-300' : '' }}
        "
    >
        <i class="w-5 fa-solid fa-file-signature"></i>
        Surat Pengantar PKL
    </a>

</div>