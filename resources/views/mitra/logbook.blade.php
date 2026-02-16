<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Logbook Mahasiswa
        </h2>
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        <!-- Info Mahasiswa -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="text-lg font-semibold">
                {{ $pkl->mahasiswa->user->name ?? '-' }}
            </h3>

            <p class="text-gray-600">
                NIM: {{ $pkl->mahasiswa->nim ?? '-' }}
            </p>

            <p class="text-gray-600">
                Periode:
                {{ $pkl->tanggal_mulai }} - {{ $pkl->tanggal_selesai }}
            </p>
        </div>

        <!-- Daftar Logbook -->
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold">
                Daftar Kegiatan
            </h3>

            @if($pkl->logbooks->isEmpty())
                <div class="text-center text-gray-500">
                    Belum ada logbook yang diisi.
                </div>
            @else

                <div class="space-y-4">
                    @foreach($pkl->logbooks as $logbook)
                        <div class="p-4 border rounded-lg bg-gray-50">
                            
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold text-gray-800">
                                    {{ $logbook->tanggal }}
                                </div>

                                <div>
                                    @if($logbook->status == 'approved')
                                        <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded">
                                            Disetujui Dosen
                                        </span>
                                    @elseif($logbook->status == 'rejected')
                                        <span class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded">
                                            Ditolak Dosen
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs text-yellow-700 bg-yellow-100 rounded">
                                            Menunggu Review
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-gray-700 whitespace-pre-line">
                                {{ $logbook->kegiatan }}
                            </div>

                            @if($logbook->catatan_dosen)
                                <div class="p-3 mt-3 text-sm text-blue-800 border-l-4 border-blue-400 bg-blue-50">
                                    <strong>Catatan Dosen:</strong><br>
                                    {{ $logbook->catatan_dosen }}
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

            @endif
        </div>

    </div>
</x-app-layout>
