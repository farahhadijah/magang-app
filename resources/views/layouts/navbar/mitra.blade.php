<div class="space-y-1">

    <a href="{{ route('mitra.mahasiswa') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.mahasiswa') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-users"></i>
        Mahasiswa
    </a>

    <a href="{{ route('mitra.tugas.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.tugas.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-list-check"></i>
        Tugas Mahasiswa
    </a>

    <a href="{{ route('mitra.logbook.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.logbook.index') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-book"></i>
        Logbook
    </a>

    <a href="{{ route('mitra.penilaian') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.penilaian*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-file-signature"></i>
        Penilaian PKL
    </a>

    <a href="{{ route('mitra.surat-balasan.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.surat-balasan*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-envelope-open-text"></i>
        Surat Balasan Instansi
    </a>

    <a href="{{ route('mitra.resume.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.resume.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-file-contract"></i>
        Resume PKL
    </a>

</div>
