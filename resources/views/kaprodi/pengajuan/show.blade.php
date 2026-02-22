<x-app-layout>
    <x-slot name="title">
        Detail Pengajuan - MagangApp
    </x-slot>

    <div class="py-6 space-y-6">
        {{-- Informasi Mahasiswa --}}
        <div class="p-6 space-y-2 border border-green-200 rounded-lg shadow-sm bg-green-50">
            <p><strong class="text-green-800">Nama:</strong> {{ $pengajuan->mahasiswa->nama ?? '-' }}</p>
            <p><strong class="text-green-800">NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
            <p><strong class="text-green-800">Instansi:</strong> {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</p>
            <p><strong class="text-green-800">Jenis Instansi:</strong> {{ $pengajuan->tempatPkl->jenis_tempat ?? '-' }}</p>
        </div>
        @php
            $lokasi = $pengajuan->tempatPkl->lokasi_maps ?? null;
        @endphp

        @if($lokasi)
            <div class="p-5 mt-6 border border-green-200 shadow-sm bg-green-50 rounded-xl">

                <div class="flex items-center mb-3 space-x-2">
                    {{-- Icon Location --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z" />
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <h3 class="text-lg font-semibold text-green-800">
                        Lokasi Tempat PKL
                    </h3>
                </div>

                <p class="mb-4 text-sm text-green-700">
                    Pastikan lokasi instansi layak dan sesuai dengan program studi sebelum menyetujui pengajuan.
                </p>

                {{-- Tombol Buka Google Maps --}}
                <a href="https://www.google.com/maps?q={{ urlencode($lokasi) }}"
                target="_blank"
                class="inline-flex items-center px-4 py-2 space-x-2 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">

                    {{-- Icon external link --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 3h7m0 0v7m0-7L10 14" />
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 10v11h11" />
                    </svg>

                    <span>Buka di Google Maps</span>
                </a>

                {{-- Embed Map --}}
                <div class="mt-5 overflow-hidden border rounded-lg">
                    <iframe
                        width="100%"
                        height="250"
                        style="border:0"
                        loading="lazy"
                        allowfullscreen
                        src="https://www.google.com/maps?q={{ urlencode($lokasi) }}&output=embed">
                    </iframe>
                </div>

            </div>
        @else
            <div class="p-4 mt-6 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50">
                Lokasi PKL belum tersedia.
            </div>
        @endif


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

            <div class="p-6 space-y-6 bg-white border border-gray-200 rounded-lg shadow-sm">

                {{-- APPROVE FORM --}}
                <form method="POST"
                    action="{{ route('kaprodi.pengajuan.approve', $pengajuan->id) }}"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Pilih Dosen Pembimbing
                        </label>

                        <select name="id_dosen"
                                required
                                class="w-full p-2 border rounded-md">
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosenList as $d)
                                <option value="{{ $d->dosen->id }}">
                                    {{ $d->dosen?->nama ?? $d->username }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_dosen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="px-6 py-2 font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Setujui & Aktifkan PKL
                    </button>
                </form>

                <hr>

                {{-- REJECT FORM --}}
                <form method="POST"
                    action="{{ route('kaprodi.pengajuan.reject', $pengajuan->id) }}"
                    class="space-y-3">
                    @csrf

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">
                            Catatan Penolakan
                        </label>

                        <textarea name="catatan"
                                required
                                rows="3"
                                placeholder="Wajib diisi jika menolak..."
                                class="w-full p-2 border rounded-md"></textarea>

                        @error('catatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="px-6 py-2 font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Tolak Pengajuan
                    </button>
                </form>

            </div>

        @else
            <div class="p-4 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50">
                Pengajuan ini sudah diproses dan tidak dapat diverifikasi kembali.
            </div>
        @endif
</x-app-layout>
