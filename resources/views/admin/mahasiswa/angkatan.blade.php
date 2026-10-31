<x-app-layout>
    <x-slot name="title">
        Daftar Angkatan - {{ $prodi->nama }} - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

        {{-- ================= BREADCRUMB ================= --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.mahasiswa.index') }}"
                class="font-medium text-green-600 hover:text-green-800 hover:underline">
                Mahasiswa
            </a>
            <span class="font-semibold text-gray-700">{{ $prodi->nama }}</span>
        </nav>

        {{-- ================= TABEL ANGKATAN ================= --}}
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-white bg-green-700">
                        <tr>
                            <th class="w-16 px-4 py-3 font-semibold text-center">No</th>
                            <th class="px-4 py-3 font-semibold">Angkatan</th>
                            <th class="w-48 px-4 py-3 font-semibold text-center">Jumlah Mahasiswa</th>
                            <th class="w-40 px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($angkatans as $index => $item)
                            <tr class="transition hover:bg-green-50">
                                <td class="px-4 py-3 text-center text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                Angkatan {{ $item->angkatan }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Mahasiswa yang terdaftar di tahun ini
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                        {{ $item->jumlah_mahasiswa }} Mahasiswa
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.mahasiswa.by-angkatan', ['prodiId' => $prodi->id, 'angkatan' => $item->angkatan]) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white transition bg-green-600 rounded-lg shadow-sm hover:bg-green-700 hover:shadow-md">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-sm">Belum ada data angkatan untuk prodi ini.</p>
                                        <p class="text-xs text-gray-400">
                                            Silakan lakukan sinkronisasi dari SIAKAD atau tambahkan data mahasiswa
                                            terlebih dahulu.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
