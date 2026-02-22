<x-app-layout>
    <x-slot name="title">
        Mahasiswa Belum Pkl- MagangApp
    </x-slot>
<div class="p-6">
    {{-- Card --}}
    <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

        @if($mahasiswas->count() > 0)

        <div class="overflow-x-auto border border-green-200">
            <table class="min-w-full text-sm">
                <thead class="bg-green-100 text-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">NIM</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Prodi</th>
                        <th class="px-4 py-3 text-center">Status Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    @foreach($mahasiswas as $index => $mhs)
                    <tr class="transition hover:bg-green-50">
                        <td class="px-4 py-3">
                            {{ $mahasiswas->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $mhs->nim }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $mhs->nama }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $mhs->prodi->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $last = $mhs->pengajuanPkl->sortByDesc('created_at')->first();
                            @endphp

                            @if($last)
                                <span class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">
                                    {{ str_replace('_', ' ', $last->status) }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">
                                    Belum Pernah Mengajukan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $mahasiswas->links() }}
        </div>

        @else

        {{-- Empty State --}}
        <div class="p-10 text-center">
            <div class="mb-4 text-5xl text-green-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-700">
                Semua Mahasiswa Sudah Mengajukan PKL 
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Tidak ada mahasiswa yang belum mengajukan.
            </p>
        </div>

        @endif

    </div>
</div>
</x-app-layout>