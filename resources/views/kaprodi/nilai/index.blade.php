<x-app-layout>
    <x-slot name="title">
        Nilai Mahasiswa - MagangApp
    </x-slot>

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

            @if($pkls->count())

            <!-- Wrapper -->
            <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

                <!-- ===== MOBILE PAGINATION ATAS ===== -->
                <div class="p-3 border-b md:hidden">
                    {{ $pkls->links() }}
                </div>

                <!-- ===== DESKTOP TABLE ===== -->
                <div class="hidden overflow-x-auto md:block">
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

                <!-- ===== MOBILE CARD ===== -->
                <div class="p-4 space-y-4 md:hidden">
                    @foreach($pkls as $pkl)

                    <div class="p-4 bg-white border rounded-xl shadow-sm space-y-3">

                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ optional($pkl->pengajuan->mahasiswa)->nama ?? '-' }}
                            </h3>
                            <span class="text-xs text-gray-500">
                                #{{ $pkls->firstItem() + $loop->index }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="space-y-1 text-xs text-gray-600">
                            <p>
                                <span class="text-gray-500">NIM:</span>
                                {{ optional($pkl->pengajuan->mahasiswa)->nim ?? '-' }}
                            </p>
                        </div>

                        <!-- Nilai -->
                        <div>
                            <span class="text-xs text-gray-500">Nilai:</span><br>
                            <span class="inline-block px-3 py-1 mt-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                {{ number_format(optional($pkl->nilaiPkl)->nilai, 2) }}
                            </span>
                        </div>

                    </div>

                    @endforeach
                </div>

                <!-- ===== DESKTOP PAGINATION ===== -->
                <div class="hidden px-4 py-3 border-t bg-gray-50 md:block">
                    {{ $pkls->links() }}
                </div>

            </div>

            @else

            {{-- Empty State --}}
            <div class="p-6 text-center sm:p-10">
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