<div class="space-y-1">

	<a href="{{ route('mitra.mahasiswa') }}"
	   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.mahasiswa') ? 'bg-green-800 text-amber-300' : '' }}">
		<i class="w-5 fa-solid fa-users"></i>
		Mahasiswa
	</a>

	<a href="{{ route('mitra.logbook.index') }}"
	   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.logbook.index') ? 'bg-green-800 text-amber-300' : '' }}">
		<i class="w-5 fa-solid fa-book"></i>
		Logbook
	</a>

	<a href="{{ route('mitra.tugas.index') }}"
	   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition hover:bg-green-800
	   {{ request()->routeIs('mitra.tugas.*') ? 'bg-green-800 text-amber-300' : '' }}">
		<i class="w-5 fa-solid fa-list-check"></i>
		Tugas Mahasiswa
	</a>

</div>