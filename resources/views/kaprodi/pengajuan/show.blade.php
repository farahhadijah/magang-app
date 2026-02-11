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
        {{-- Riwayat Verifikasi TU --}}
        @php
            $verifikasiTu = $pengajuan->verifikasi
                ->where('level', 'tu')
                ->where('status', 'approved')
                ->first();
        @endphp

        @if($verifikasiTu)
            <div class="p-4 border rounded bg-blue-50">
                <p><strong>Verifikasi TU:</strong></p>
                <p>Oleh: {{ $verifikasiTu->user?->getNama() ?? '-' }}</p>
                <p>Tanggal: {{ $verifikasiTu->tgl_verifikasi }}</p>
            </div>
        @endif


        {{-- Aksi Verifikasi Kaprodi --}}
        @if ($pengajuan->bisaDiverifikasiKaprodi())
            <form method="POST" action="{{ route('kaprodi.pengajuan.approve', $pengajuan->id) }}">
                @csrf
                <div class="space-y-4">
                    {{-- Pilih Dosen Pembimbing --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Pilih Dosen Pembimbing</label>
                        <select name="id_dosen" required class="p-2 border rounded-md">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosenList as $d)
                                <option value="{{ $d->dosen->id }}">
                                    {{ $d->dosen?->nama ?? $d->username }}
                                </option>
                            @endforeach
                        </select>


                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex gap-4">
                        <button type="submit"
                                class="px-6 py-2 font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Setujui & Aktifkan PKL
                        </button>

                        <a href="{{ route('kaprodi.pengajuan.index') }}"
                           class="inline-block px-6 py-2 font-medium text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        @else
            <div class="p-4 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50">
                Pengajuan ini sudah diproses dan tidak dapat diverifikasi kembali.
            </div>
        @endif
    </div>
</x-app-layout>
