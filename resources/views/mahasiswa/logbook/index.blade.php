<x-app-layout>
    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="flex items-center gap-2 p-4 text-green-800 border border-green-200 bg-green-50 rounded-xl">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex items-center gap-2 p-4 text-yellow-800 border border-yellow-200 bg-yellow-50 rounded-xl">
                <i class="text-yellow-600 fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        {{-- Button Tambah --}}
        <div class="flex justify-end">
            @if(!empty($hasToday))
                <button disabled
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed"
                    title="Anda sudah membuat logbook untuk hari ini">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Logbook
                </button>
            @else
                <a href="{{ route('mahasiswa.logbook.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Logbook
                </a>
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">
            <table class="w-full text-sm">
                <thead class="text-green-900 bg-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Catatan Dosen</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-green-100">
                    @forelse($logbooks as $log)
                        <tr class="transition hover:bg-green-50">

                            {{-- Tanggal --}}
                            <td class="px-4 py-3">
                                {{ $log->tgl->format('d-m-Y') }}
                            </td>

                            {{-- Kegiatan --}}
                            <td class="px-4 py-3">
                                {{ $log->kegiatan }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if ($log->status_approve === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Catatan Dosen --}}
                            <td class="px-4 py-3">
                                @if ($log->catatan)
                                    <div class="text-sm text-red-700">
                                        {{ $log->catatan }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3">
                                @if ($log->status_approve === 'pending')
                                    <a href="{{ route('mahasiswa.logbook.edit', $log->id) }}"
                                       class="text-sm font-medium text-green-600 hover:underline">
                                        Edit
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Terkunci</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada logbook
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
