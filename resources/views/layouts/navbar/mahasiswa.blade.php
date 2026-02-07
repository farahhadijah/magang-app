<div class="space-y-1">

    {{-- Ajukan PKL --}}
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


    {{-- Logbook --}}
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


    {{-- Status PKL --}}
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

</div>
