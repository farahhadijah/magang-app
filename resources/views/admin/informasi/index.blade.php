<x-app-layout>

    <x-slot name="title">
        Informasi - MagangApp
    </x-slot>

    <div class="p-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Informasi
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola flowchart, format laporan, dan manual book Sibolang.
                </p>
            </div>

            <a href="{{ route('admin.informasi.create') }}"
                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                + Tambah Informasi
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="px-4 py-3 mb-5 text-sm text-green-700 bg-green-50 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Error --}}
        @if ($errors->any())
            <div class="px-4 py-3 mb-5 text-sm text-red-700 bg-red-50 rounded-lg border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                #
                            </th>

                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                Informasi
                            </th>

                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                Jenis
                            </th>

                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                Ditampilkan Untuk
                            </th>

                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                Status
                            </th>

                            <th class="px-5 py-3 font-semibold text-left text-gray-600">
                                File
                            </th>

                            <th class="px-5 py-3 font-semibold text-center text-gray-600">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($informasi as $item)
                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-4 text-gray-500">
                                    {{ $informasi->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-800">
                                        {{ $item->judul }}
                                    </div>

                                    @if ($item->deskripsi)
                                        <div class="mt-1 max-w-xs text-xs text-gray-500">
                                            {{ Str::limit($item->deskripsi, 80) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $jenisLabel = [
                                            'flowchart' => 'Flowchart PKL',
                                            'format_laporan' => 'Format Laporan',
                                            'manual_book' => 'Manual Book',
                                        ];
                                    @endphp

                                    <span class="text-gray-700">
                                        {{ $jenisLabel[$item->jenis] ?? $item->jenis }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $roleLabel = [
                                            'admin' => 'Admin',
                                            'mahasiswa' => 'Mahasiswa',
                                            'dosen' => 'Dosen',
                                            'staff_tu' => 'Tata Usaha',
                                            'mitra' => 'Mitra',
                                            'pimpinan' => 'Pimpinan',
                                        ];
                                    @endphp

                                    <span class="text-gray-700">
                                        {{ $roleLabel[$item->target_role] ?? $item->target_role }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($item->is_active)
                                        <span
                                            class="inline-flex px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                        class="font-medium text-green-600 hover:text-green-800">
                                        Lihat File
                                    </a>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex gap-2 justify-center items-center">

                                        <a href="{{ route('admin.informasi.edit', $item) }}"
                                            class="px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.informasi.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada informasi.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($informasi->hasPages())
                <div class="px-5 py-4 border-t border-gray-200">
                    {{ $informasi->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
