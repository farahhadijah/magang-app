<div class="space-y-1">

    {{-- ================= VERIFIKASI PKL ================= --}}
    <a href="{{ route('kaprodi.pengajuan.index') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('kaprodi.pengajuan.index') || request()->routeIs('kaprodi.pengajuan.show') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-circle-check"></i>
        Verifikasi PKL
    </a>

    {{-- ================= HISTORI DITOLAK ================= --}}
    <a href="{{ route('kaprodi.pengajuan.histori') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
       {{ request()->routeIs('kaprodi.pengajuan.histori') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-clock-rotate-left"></i>
        Histori
    </a>
    {{-- ================= MAHASISWA PKL ================= --}}
    <a href="{{ route('kaprodi.mahasiswa.index') }}"
        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
        {{ request()->routeIs('kaprodi.mahasiswa.index') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-user-graduate"></i>
        Mahasiswa PKL
    </a>
{{-- ================= MAHASASISWA BELUM PKL ================= --}}
    <a href="{{ route('kaprodi.mahasiswa.belum') }}"
    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('kaprodi.mahasiswa.belum') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-user-clock"></i>
        Mahasiswa Belum Mengajukan
    </a>

    {{-- ================= SERTIFIKAT PKL ================= --}}
    <a href="{{ route('kaprodi.sertifikat.index') }}"
    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('kaprodi.sertifikat.*') ? 'bg-green-800 text-amber-300' : '' }}">
        <i class="w-5 fa-solid fa-certificate"></i>
        Sertifikat PKL
    </a>
    
</div>
