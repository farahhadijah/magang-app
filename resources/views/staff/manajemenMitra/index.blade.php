    <x-app-layout>

    <x-slot name="title">
        Akun Mitra - MagangApp
    </x-slot>

    <div class="container px-4 py-6 mx-auto">

    <h1 class="mb-4 text-xl font-bold md:text-2xl text-slate-900">
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
                class="w-full sm:w-64 px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-green-200"
            >

            <button 
                type="submit"
                class="w-full sm:w-auto px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700"
            >
                Cari
            </button>
        </div>
    </form>


    <!-- DESKTOP TABLE -->
    <div class="hidden md:block overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border border-green-100">

            <thead class="bg-green-100 text-slate-900">
                <tr>
                    <th class="p-3 text-left">Tempat PKL</th>
                    <th class="p-3 text-left">Jenis</th>
                    <th class="p-3 text-left">Jabatan</th>
                    <th class="p-3 text-left">No HP</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($mitras as $mitra)
                <tr class="border-t text-slate-800">
                    <td class="p-3">{{ $mitra->tempatPkl->nama_tempat }}</td>
                    <td class="p-3">{{ $mitra->tempatPkl->jenis_tempat }}</td>
                    <td class="p-3">{{ $mitra->jabatan }}</td>
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


    <!-- MOBILE CARD -->
    <div class="space-y-4 md:hidden">
        @forelse($mitras as $mitra)
            <div class="p-4 bg-white border rounded-lg shadow-sm">

                <div class="mb-2">
                    <p class="text-sm text-gray-500">Tempat PKL</p>
                    <p class="font-semibold text-slate-900">
                        {{ $mitra->tempatPkl->nama_tempat }}
                    </p>
                </div>

                <div class="mb-2">
                    <p class="text-sm text-gray-500">Jenis</p>
                    <p>{{ $mitra->tempatPkl->jenis_tempat }}</p>
                </div>

                <div class="mb-2">
                    <p class="text-sm text-gray-500">Jabatan</p>
                    <p>{{ $mitra->jabatan }}</p>
                </div>

                <div class="mb-3">
                    <p class="text-sm text-gray-500">No HP</p>
                    <p>{{ $mitra->no_hp }}</p>
                </div>

                <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                   class="block w-full text-center px-3 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Detail
                </a>

            </div>
        @empty
            <div class="p-4 text-center text-gray-500 bg-white border rounded">
                Data mitra tidak ditemukan
            </div>
        @endforelse
    </div>


    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        {{ $mitras->links() }}
    </div>

</div>

    </x-app-layout>