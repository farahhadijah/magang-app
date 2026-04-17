<x-app-layout>

    <x-slot name="title">
        Akun Mitra - MagangApp
    </x-slot>

    <div class="container px-0 py-6 mx-auto">

    <h1 class="mb-0 text-xl font-bold md:text-2xl text-slate-900">
        Manajemen Akun Mitra
    </h1>

    <!-- Search -->
    <form method="GET" action="{{ route('staff.manajemen-mitra.index') }}" class="mb-4">
        <div class="flex flex-col gap-2 sm:flex-row">
            <input 
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari tempat PKL / jabatan..."
                class="w-full px-3 py-2 border rounded sm:w-64 focus:outline-none focus:ring focus:ring-green-200"
            >

            <button 
                type="submit"
                class="w-full px-4 py-2 text-white bg-green-600 rounded sm:w-auto hover:bg-green-700"
            >
                Cari
            </button>
        </div>
    </form>


    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-x-auto bg-white rounded shadow md:block">
        <table class="min-w-full border border-green-100">

            <thead class="bg-green-100 text-slate-900">
                <tr>
                    <th class="p-3 text-left">Tempat PKL</th>
                    <th class="p-3 text-left">Jenis</th>
                    <th class="p-3 text-left">No HP</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($mitras as $mitra)
                <tr class="border-t text-slate-800">
                    <td class="p-3">{{ $mitra->tempatPkl->nama_tempat }}</td>
                    <td class="p-3">{{ $mitra->tempatPkl->jenis_tempat }}</td>
                    <td class="p-3">{{ $mitra->no_hp }}</td>
                    <td class="p-3">
                        <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                           class="px-3 py-1 text-white bg-green-600 rounded hover:bg-green-700">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        Data mitra tidak ditemukan
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>


    <!-- MOBILE CARD - MINIMALIS VERSION -->
    <div class="space-y-2 md:hidden">
        @forelse($mitras as $mitra)
            <div class="p-3 bg-white border rounded-lg">

                {{-- Header: Tempat PKL --}}
                <div class="pb-2 mb-2 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-slate-900">
                        {{ $mitra->tempatPkl->nama_tempat }}
                    </h3>
                    <p class="text-xs text-gray-500">
                        {{ $mitra->tempatPkl->jenis_tempat }}
                    </p>
                </div>

                {{-- Body: Informasi ringkas dengan layout horizontal --}}
                <div class="space-y-1.5 mb-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">No HP</span>
                        <span class="text-sm font-medium text-gray-700">{{ $mitra->no_hp }}</span>
                    </div>
                </div>

                {{-- Action Button --}}
                <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                   class="block w-full py-2 text-sm font-medium text-center text-green-600 transition border border-green-600 rounded hover:bg-green-50">
                    Lihat Detail
                </a>

            </div>
        @empty
            <div class="p-4 text-center text-gray-500 bg-white border rounded">
                Data mitra tidak ditemukan
            </div>
        @endforelse
    </div>


    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $mitras->links() }}
    </div>

</div>

</x-app-layout>