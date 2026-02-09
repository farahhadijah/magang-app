<x-app-layout>
    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))
        <div class="flex items-center gap-2 p-4 text-green-800 border border-green-200 rounded-xl bg-green-50">
            <i class="text-green-600 fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- ================= BUTTON TAMBAH ================= --}}
        <div class="flex justify-end">
            <a href="{{ route('mahasiswa.logbook.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                <i class="fa-solid fa-plus"></i>
                Tambah Logbook
            </a>
        </div>

        {{-- ================= TABLE ================= --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">
            <table class="w-full text-sm">
                {{-- Head --}}
                <thead class="text-green-800 bg-green-50">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-left">Kegiatan</th>
                        <th class="px-4 py-3 font-semibold text-left">Status</th>
                        <th class="px-4 py-3 font-semibold text-left">Catatan Dosen</th>
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody class="divide-y divide-green-100">
                    @forelse($logbooks as $log)
                        <tr class="transition hover:bg-green-50">
                            <td class="px-4 py-3">{{ $log->tgl->format('d-m-Y') }}</td>
                            <td class="px-4 py-3">{{ $log->kegiatan }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $log->status_approve == 'pending' ? 'text-amber-800 bg-amber-100' : 'text-green-800 bg-green-100' }}">
                                    {{ ucfirst($log->status_approve) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($log->catatan)
                                    <div class="text-sm text-red-800">
                                        {{ $log->catatan }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-600">Belum ada logbook</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
