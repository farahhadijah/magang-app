<x-app-layout>
<x-slot name="title">
        Sertifikat Mahasiswa - MagangApp
</x-slot>

<div class="p-6">
    <div class="p-6 bg-white border shadow-sm rounded-xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-lg font-semibold text-gray-700">
                Daftar Mahasiswa Penerima Sertifikat PKL
            </h1>

            <span class="px-3 py-1 text-sm text-green-700 bg-green-100 rounded-lg">
                Total: {{ $data->count() }} Mahasiswa
            </span>
        </div>

        @if($data->isEmpty())
        <div class="p-4 text-yellow-800 border border-yellow-200 rounded-lg bg-yellow-50">
            Belum ada mahasiswa yang mendapatkan sertifikat dari mitra.
        </div>
        @else

        {{-- ================= DESKTOP TABLE ================= --}}
        <div class="hidden overflow-x-auto border rounded-lg md:block">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-gray-600 uppercase bg-green-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">NIM</th>
                        <th class="px-4 py-3">Tempat PKL</th>
                        <th class="px-4 py-3">Tanggal Pengajuan</th>
                        <th class="px-4 py-3 text-center">Sertifikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($data as $index => $item)
                    @php
                        // Perbaikan: Cek relasi dengan lebih aman
                        $pkl = $item->pkl ?? null;
                        $pengajuan = $pkl?->pengajuanPkl ?? null;
                        $mahasiswa = $pengajuan?->mahasiswa ?? null;
                        $tempat = $pengajuan?->tempatPkl ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium">
                            {{ $mahasiswa->nama ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $mahasiswa->nim ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $tempat->nama_tempat ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->file_sertifikat)
                            <button
                                onclick="openSertifikatModal('{{ asset('storage/'.$item->file_sertifikat) }}')"
                                class="px-3 py-1 text-xs text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                            >
                                Lihat
                            </button>
                            @else
                            <span class="text-gray-400">
                                Tidak ada file
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ================= MOBILE CARD ================= --}}
        <div class="grid gap-4 md:hidden">
            @foreach($data as $index => $item)
            @php
                $pkl = $item->pkl ?? null;
                $pengajuan = $pkl?->pengajuanPkl ?? null;
                $mahasiswa = $pengajuan?->mahasiswa ?? null;
                $tempat = $pengajuan?->tempatPkl ?? null;
            @endphp
            <div class="p-4 border rounded-lg shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $mahasiswa->nama ?? '-' }}
                    </span>
                    <span class="text-xs text-gray-500">
                        #{{ $index + 1 }}
                    </span>
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">NIM:</span>
                        {{ $mahasiswa->nim ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium">Tempat PKL:</span>
                        {{ $tempat->nama_tempat ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium">Tanggal:</span>
                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}
                    </div>
                </div>
                <div class="mt-3">
                    @if($item->file_sertifikat)
                    <button
                        onclick="openSertifikatModal('{{ asset('storage/'.$item->file_sertifikat) }}')"
                        class="w-full px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                    >
                        Lihat Sertifikat
                    </button>
                    @else
                    <span class="text-sm text-gray-400">
                        Tidak ada file
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- MODAL PREVIEW SERTIFIKAT --}}
<div id="modalSertifikat"
    class="fixed inset-0 z-[99999] items-center justify-center hidden bg-black bg-opacity-50">
    
    <div class="w-11/12 h-[90vh] bg-white rounded-xl shadow-lg relative flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h3 class="text-sm font-semibold text-gray-700">
                Preview Sertifikat
            </h3>
            <button onclick="closeSertifikatModal()" class="text-gray-500 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex-1 p-2">
            <iframe
                id="frameSertifikat"
                src="about:blank"
                class="w-full h-full border-0 rounded-b-xl"
                title="Preview Sertifikat"
            ></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openSertifikatModal(url) {
    const modal = document.getElementById('modalSertifikat');
    const frame = document.getElementById('frameSertifikat');

    if (!modal || !frame) return;

    frame.src = url;
    modal.classList.remove('hidden');
    // modal.classList.add('flex'); // Tidak perlu karena sudah ada di class default
}

function closeSertifikatModal() {
    const modal = document.getElementById('modalSertifikat');
    const frame = document.getElementById('frameSertifikat');

    if (!modal || !frame) return;

    frame.src = 'about:blank'; // Reset src
    modal.classList.add('hidden');
}

// Tutup modal jika klik di luar area modal
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalSertifikat');
    if (!modal) return;
    
    if (e.target === modal) {
        closeSertifikatModal();
    }
});

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSertifikatModal();
    }
});
</script>
@endpush

</x-app-layout>