<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

        <h2 class="mb-6 text-2xl font-bold text-green-900">
            Nilai PKL Saya
        </h2>

        @if(!$pkl || !$pkl->nilaiPkl)
            <div class="p-4 text-yellow-800 bg-yellow-100 rounded">
                Nilai PKL belum tersedia.
            </div>
        @else
            <div class="p-6 space-y-4 bg-white rounded shadow">

                <div>
                    <span class="text-gray-600">Nilai:</span>
                    <span class="text-2xl font-bold text-green-700">
                        {{ $pkl->nilaiPkl->nilai }}
                    </span>
                </div>

                <div>
                    <span class="text-gray-600">Keterangan:</span>
                    <p class="mt-1">
                        {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                    </p>
                </div>

                <div>
                    <span class="text-gray-600">Tanggal Input:</span>
                    <span>
                        {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                    </span>
                </div>

                <div class="pt-4">
                    <a href="{{ route('mahasiswa.sertifikat.dummy', $pkl->id) }}"
                       class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Generate Sertifikat PKL
                    </a>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
