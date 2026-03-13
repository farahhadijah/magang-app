<x-app-layout>
    <x-slot name="title">
        Download - MagangApp
    </x-slot>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-green-800">
            Download Formulir Magang
        </h2>
        <p class="mt-2 text-gray-600">
            Silakan unduh formulir pengajuan magang dan dokumen pendukung yang diperlukan.
        </p>
    </div>

    <div class="overflow-hidden bg-white border border-green-100 shadow-md rounded-xl">
        <table class="min-w-full">
            <thead class="text-white bg-green-700">
                <tr>
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-left">Nama Formulir</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formulir as $index => $f)
                    <tr class="transition border-b hover:bg-green-50">
                        <td class="p-4 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="p-4">
                            {{ $f->nama }}
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('mahasiswa.formulir.download',$f->id) }}"
                               class="text-green-700 transition hover:text-green-600">
                                <i class="mr-1 fa-solid fa-download"></i>
                                Download
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">
                            Tidak ada formulir tersedia saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>