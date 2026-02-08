<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">Detail Pengajuan PKL</h2>
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Informasi Mahasiswa --}}
        <div class="p-6 space-y-2 border border-green-200 rounded-lg shadow-sm bg-green-50">
            <p><strong class="text-green-800">Nama:</strong> {{ $pengajuan->mahasiswa->nama ?? '-' }}</p>
            <p><strong class="text-green-800">NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
            <p><strong class="text-green-800">Instansi:</strong> {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</p>
            <p><strong class="text-green-800">Jenis Instansi:</strong> {{ $pengajuan->tempatPkl->jenis_tempat ?? '-' }}</p>
        </div>

        {{-- Dokumen --}}
        <div class="p-6 bg-white border border-green-200 rounded-lg shadow-sm">
            <h3 class="mb-3 text-lg font-semibold text-green-900">Dokumen Pengajuan</h3>

            @if ($pengajuan->dokumenPengajuan->count())
                <ul class="space-y-2 text-sm">
                    @foreach ($pengajuan->dokumenPengajuan as $dokumen)
                        <li>
                            <a href="{{ asset('storage/' . $dokumen->path_file) }}" target="_blank"
                               class="flex items-center gap-2 text-green-700 hover:text-green-900">
                                <i class="fa-solid fa-file-pdf"></i> {{ $dokumen->jenis_dokumen }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Tidak ada dokumen terunggah.</p>
            @endif
        </div>

        {{-- Aksi Verifikasi --}}
        @if ($pengajuan->bisaDiverifikasiKaprodi())
            <div class="flex flex-col gap-4 md:flex-row">
                {{-- APPROVE --}}
                <form method="POST" action="{{ route('kaprodi.pengajuan.approve', $pengajuan->id) }}"
                    onsubmit="return confirm('Yakin ingin MENYETUJUI pengajuan PKL ini?')">
                    @csrf
                    <button class="w-full px-6 py-2 font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Setujui
                    </button>
                </form>

                {{-- REJECT --}}
                <form method="POST" action="{{ route('kaprodi.pengajuan.reject', $pengajuan->id) }}"
                    class="flex flex-col w-full gap-2 md:w-auto"
                    onsubmit="return confirm('Yakin ingin MENOLAK pengajuan PKL ini?')">
                    @csrf
                    <textarea name="catatan" rows="3" placeholder="Catatan penolakan (wajib diisi)"
                            class="p-2 border rounded-md border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400"
                            required></textarea>
                    <button class="px-6 py-2 font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700">
                        Tolak
                    </button>
                </form>
            </div>
        @else
            <div class="p-4 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50">
                Pengajuan ini sudah diproses dan tidak dapat diverifikasi kembali.
            </div>
        @endif



        <a href="{{ route('kaprodi.pengajuan.index') }}" class="inline-block font-medium text-green-700 hover:text-green-900">
            ← Kembali
        </a>
    </div>
</x-app-layout>
