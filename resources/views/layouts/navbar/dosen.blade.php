<div class="space-y-1">

    {{-- ================= DAFTAR MAHASISWA BIMBINGAN ================= --}}
    <a href="{{ route('dosen.mahasiswa.bimbingan') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('dosen.mahasiswa.bimbingan') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="fa-solid fa-users"></i>
        <span>Mahasiswa Bimbingan</span>
    </a>

    {{-- ================= REVIEW LOGBOOK ================= --}}
    <a href="{{ route('dosen.logbook.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('dosen.logbook.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="fa-solid fa-book"></i>
        <span>Review Logbook</span>
    </a>
    
     {{-- ================= REVIEW LAPORAN AKHIR ================= --}}
    <a href="{{ route('dosen.laporan.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('dosen.laporan.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="fa-solid fa-file-lines"></i>
        <span>Review Laporan</span>
    </a>

    {{-- ================= PENILAIAN PKL ================= --}}
    <a href="{{ route('dosen.nilai.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('dosen.nilai.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="fa-solid fa-clipboard-check"></i>
        <span>Penilaian PKL</span>
    </a>

</div>
