<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-700">
            Mahasiswa Selesai PKL & Sudah Dinilai
        </h2>
    </x-slot>

    <div class="px-6 py-6">
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

            @if($pkls->count())

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="w-16 px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-left">NIM</th>
                            <th class="w-32 px-4 py-3 text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-green-100">

                        @foreach($pkls as $pkl)
                        <tr class="transition hover:bg-green-50">
                            <td class="px-4 py-3">
                                {{ $pkls->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($pkl->pengajuan->mahasiswa)->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ optional($pkl->pengajuan->mahasiswa)->nim ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                    {{ number_format(optional($pkl->nilaiPkl)->nilai, 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $pkls->links() }}
            </div>

            @else

            {{-- Empty State --}}
            <div class="p-10 text-center">
                <div class="mb-4 text-5xl text-green-600">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-700">
                    Belum Ada Mahasiswa yang Dinilai
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Mahasiswa yang selesai PKL dan sudah dinilai akan muncul di sini.
                </p>
            </div>

            @endif

        </div>
    </div>
</x-app-layout>