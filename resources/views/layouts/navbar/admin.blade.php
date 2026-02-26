<div class="space-y-1">

    {{-- ================= MANAJEMEN FORMULIR ================= --}}
    <a href="{{ route('admin.formulir.index') }}"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition hover:bg-green-800
    {{ request()->routeIs('admin.formulir.*') ? 'bg-green-800 text-amber-300' : '' }}">
        
        <i class="w-5 fa-solid fa-file-lines"></i>
        <span>Manajemen Formulir</span>
    </a>

</div>
