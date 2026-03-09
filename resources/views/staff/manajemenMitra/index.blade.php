<x-app-layout>

<x-slot name="title">
    Akun Mitra - MagangApp
</x-slot>

<div class="container px-4 py-6 mx-auto">

    <h1 class="mb-4 text-2xl font-bold text-slate-900">
        Manajemen Akun Mitra
    </h1>

    <!-- Search -->
    <form method="GET" action="{{ route('staff.mitra.index') }}" class="mb-4">
        <div class="flex gap-2">
            <input 
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari tempat PKL..."
                class="w-64 px-3 py-2 border rounded"
            >

            <button 
                type="submit"
                class="px-4 py-2 text-white bg-green-600 rounded"
            >
                Cari
            </button>
        </div>
    </form>


    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">

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

                    <td class="p-3">
                        {{ $mitra->nama_tempat }}
                    </td>

                    <td class="p-3">
                        {{ $mitra->jenis_tempat }}
                    </td>

                    <td class="p-3">
                        {{ $mitra->jabatan }}
                    </td>

                    <td class="p-3">
                        {{ $mitra->no_hp }}
                    </td>

                    <td class="p-3">

                        <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                            class="px-3 py-1 text-white bg-green-600 rounded"
                            > Detail
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


    <!-- Pagination -->
    <div class="mt-4">
        {{ $mitras->links() }}
    </div>

</div>

</x-app-layout>