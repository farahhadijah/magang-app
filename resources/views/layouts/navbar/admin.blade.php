<div class="space-y-1">

    {{-- ================= MANAJEMEN FORMULIR ================= --}}
    <a href="{{ route('admin.formulir.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('admin.formulir.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-file-lines"></i>
        <span>Manajemen Formulir</span>
    </a>

    {{-- ================= MANAJEMEN FAKULTAS ================= --}}
    <a href="{{ route('admin.fakultas.index') }}"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('admin.fakultas.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-building-columns"></i>
        <span>Manajemen Fakultas</span>
    </a>

    {{-- ================= MANAJEMEN PRODI ================= --}}
    <a href="{{ route('admin.prodi.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('admin.prodi.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-graduation-cap"></i>
        <span>Manajemen Prodi</span>
    </a>

    {{-- ================= MANAJEMEN MAHASISWA ================= --}}
    <a href="{{ route('admin.mahasiswa.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-user-graduate"></i>
        <span>Manajemen Mahasiswa</span>
    </a>

    <a href="{{ route('admin.dosen.index') }}"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('admin.dosen.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-chalkboard-user"></i>
        <span>Manajemen Dosen</span>
    </a>

    <a href="{{ route('admin.staff.index') }}"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('admin.staff.*') ? 'bg-green-800 text-amber-300' : '' }}">

        <i class="w-5 fa-solid fa-user-tie"></i>
        <span>Manajemen Staff</span>
    </a>

</div>