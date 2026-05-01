<div class="space-y-1">

    {{-- TITLE --}}
    <div class="px-4 pt-4 mt-6 text-xs text-gray-300 uppercase border-t border-green-700">
        Fakultas
    </div>

    {{-- LOOP FAKULTAS --}}
    @foreach($fakultas as $f)
        <a href="{{ route('pimpinan.prodi', $f->id) }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
           {{ request()->is('pimpinan/fakultas/'.$f->id) ? 'bg-green-800 text-amber-300' : '' }}">
            
            <i class="w-5 fa-solid fa-building"></i>
            {{ $f->nama }}
        </a>
    @endforeach

</div>