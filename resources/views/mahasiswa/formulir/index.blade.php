<x-app-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-green-800">
            Download Formulir Magang
        </h2>
        <p class="text-gray-600 mt-2">
            Silakan unduh formulir pengajuan magang dan dokumen pendukung yang diperlukan.
        </p>
    </div>

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-green-100">
        <table class="min-w-full">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-left">Nama Formulir</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formulir as $index => $f)
                    <tr class="border-b hover:bg-green-50 transition">
                        <td class="p-4 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="p-4">
                            {{ $f->nama }}
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('mahasiswa.formulir.download',$f->id) }}"
                               class="hover:text-green-600 transition text-green-700">
                                <i class="fa-solid fa-download mr-1"></i>
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