<x-app-layout>
    <x-slot name="title">
        Mahasiswa Aktif - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-7xl min-h-[70vh] flex flex-col">

        @if ($mahasiswas->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Tidak ada mahasiswa dengan status PKL aktif.
                </p>
            </div>
        @else

            <div class="flex-1 overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max">
                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="p-3 text-left border">No</th>
                            <th class="p-3 text-left border">Nama</th>
                            <th class="p-3 text-left border">NIM</th>
                            <th class="p-3 text-left border">Prodi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach($mahasiswas as $mhs)
                        <tr class="transition hover:bg-green-50">
                            <td class="p-3 border">
                                {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-3 border">
                                {{ $mhs->nama }}
                            </td>
                            <td class="p-3 border">
                                {{ $mhs->nim }}
                            </td>
                            <td class="p-3 border">
                                {{ $mhs->prodi->nama ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-6">
                {{ $mahasiswas->links() }}
            </div>

        @endif

    </div>
</x-app-layout>