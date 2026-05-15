<x-app-layout>
    <x-slot name="title">
        Detail Pengajuan - MagangApp
    </x-slot>
    
    @php
        $lokasi = $pengajuan->tempatPkl->lokasi_maps ?? null;
        $dosenForSearch = $dosenList->map(fn ($d) => [
            'nama' => $d->nama,
            'keahlian' => $d->keahlian ?? '',
        ])->values();
    @endphp

    <div
        x-data="kaprodiPengajuanShow(@js($lokasi), @js($dosenForSearch))"
        x-init="initMap()"
    >
        <div class="py-6 space-y-6">
            {{-- Informasi Mahasiswa --}}
            <div class="p-6 space-y-2 border border-green-200 rounded-lg shadow-sm bg-green-50">
                <p class="text-gray-700"><strong class="text-green-800">Nama:</strong> {{ $pengajuan->mahasiswa->nama ?? '-' }}</p>
                <p class="text-gray-700"><strong class="text-green-800">NIM:</strong> {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                <p class="text-gray-700"><strong class="text-green-800">Instansi:</strong> {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</p>
                <p class="text-gray-700"><strong class="text-green-800">Jenis Instansi:</strong> {{ $pengajuan->tempatPkl->jenis_tempat ?? '-' }}</p>
            </div>
            
            @if($jarak)
                <div class="p-3 mb-3 text-sm text-green-800 border border-green-200 rounded bg-green-50">
                    Jarak dari Kampus :
                    <strong>{{ number_format($jarak,2) }} KM</strong>
                </div>
            @endif
            
            <div class="p-4 mt-4 border border-green-200 rounded-lg bg-green-50">
                <h4 class="mb-2 font-semibold text-green-800">Riwayat Tempat PKL</h4>
                <p class="mb-2 text-xs text-gray-600">
                    Per prodi Anda · angkatan {{ $pengajuan->mahasiswa->angkatan ?? '-' }} · nama tempat sama · hanya PKL status aktif
                </p>
                @if($jumlahRiwayat > 0)
                    <p class="text-sm text-green-800">
                        ✔ Tempat ini sudah pernah digunakan oleh
                        <strong>{{ $jumlahRiwayat }}</strong> mahasiswa.
                    </p>
                    <p class="text-sm text-green-800">
                        ✔ Terakhir digunakan:
                        <strong>{{ $terakhirDigunakan->format('d M Y') }}</strong>
                    </p>
                @else
                    <p class="text-sm text-gray-700">Belum pernah ada mahasiswa PKL di tempat ini.</p>
                @endif
            </div>
            
            @if($lokasi)
                <div class="p-5 mt-6 border border-green-200 shadow-sm bg-green-50 rounded-xl">
                    <div class="flex items-center mb-3 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-green-800">Lokasi Tempat PKL</h3>
                    </div>
                    <p class="mb-4 text-sm text-green-700">Lokasi instansi tempat PKL mahasiswa.</p>
                    <div id="mapKaprodi" class="w-full h-[300px] border rounded-lg"></div>
                    <a href="{{ $lokasi }}" target="_blank" class="inline-block px-4 py-2 mt-3 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Buka di Google Maps
                    </a>
                </div>
            @else
                <div class="p-4 mt-6 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50">
                    Lokasi PKL belum tersedia.
                </div>
            @endif

            {{-- Dokumen --}}
            <div class="relative z-50 p-6 bg-white border border-green-200 rounded-lg shadow-sm">
                <h3 class="mb-3 text-lg font-semibold text-green-900">Dokumen Pengajuan</h3>
                @if ($pengajuan->dokumenPengajuan->count())
                    <ul class="space-y-2 text-sm">
                        @foreach ($pengajuan->dokumenPengajuan as $dokumen)
                            <li>
                                <button type="button" @click="openModal('{{ asset('storage/' . $dokumen->path_file) }}')" class="flex items-center gap-2 text-green-700 hover:text-green-900">
                                    <i class="fa-solid fa-file-pdf"></i> {{ $dokumen->jenis_dokumen }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">Tidak ada dokumen terunggah.</p>
                @endif
            </div>
            
            {{-- Riwayat Verifikasi TU --}}
            @php
                $verifikasiTu = $pengajuan->verifikasi->where('level', 'tu')->where('status', 'approved')->first();
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
                    <form method="POST" action="{{ route('kaprodi.pengajuan.approve', $pengajuan->id) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Pilih Dosen Pembimbing
                            </label>

                            {{-- SEARCH --}}
                            <div class="mb-4">
                                <input type="text" 
                                    x-model="searchDosen"
                                    placeholder="Cari dosen (nama atau keahlian)..."
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>

                            {{-- LIST DOSEN --}}
                            <div id="dosenContainer" class="overflow-y-auto max-h-96">
                                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3">

                                    @forelse($dosenList as $d)
                                        <label
                                            x-show="matchesDosen(@js($d->nama), @js($d->keahlian ?? ''))"
                                            class="flex items-start p-3 transition border rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-300"
                                        >
                                            
                                            <input type="radio"
                                                name="id_dosen"
                                                value="{{ $d->id }}"
                                                class="mt-1 mr-3"
                                                required>
                                            
                                            <div class="flex-1">
                                                {{-- NAMA --}}
                                                <p class="text-sm font-semibold text-gray-800 dosen-nama">
                                                    {{ $d->nama }}
                                                </p>

                                                {{-- KEAHLIAN --}}
                                                @if($d->keahlian)
                                                    <p class="text-xs text-green-600 dosen-keahlian">
                                                        {{ Str::limit($d->keahlian, 50) }}
                                                    </p>
                                                @endif

                                                {{-- JUMLAH BIMBINGAN --}}
                                                <p class="mt-1 text-xs text-blue-600">
                                                    {{ $d->total_bimbingan }} mahasiswa aktif
                                                </p>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="p-6 text-center text-gray-500">
                                            Tidak ada dosen tersedia
                                        </div>
                                    @endforelse

                                </div>

                                {{-- EMPTY SEARCH --}}
                                <div x-show="dosenSearchEmpty" x-cloak class="p-6 text-center text-gray-500">
                                    Tidak ditemukan dosen
                                </div>
                            </div>

                            @error('id_dosen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="px-6 py-2 font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Setujui & Aktifkan PKL
                        </button>
                    </form>

                    <hr>

                    {{-- REJECT FORM --}}
                    <form method="POST" action="{{ route('kaprodi.pengajuan.reject', $pengajuan->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Catatan Penolakan</label>
                            <textarea name="catatan" required rows="3" placeholder="Wajib diisi jika menolak..." class="w-full p-2 border rounded-md"></textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="px-6 py-2 font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>
            @else
                <div class="p-4 text-sm text-gray-700 border border-gray-300 rounded-lg bg-gray-50">
                    Pengajuan ini sudah diproses dan tidak dapat diverifikasi kembali.
                </div>
            @endif
            
            {{-- MODAL PDF --}}
            <div x-show="isOpen" x-transition class="fixed inset-0 z-[99999] flex items-center justify-center bg-black bg-opacity-60" style="display: none;">
                <div class="relative z-50 w-11/12 bg-white rounded-lg shadow-lg h-[90vh]">
                    <button @click="closeModal()" class="absolute z-10 w-10 h-10 text-xl text-white bg-red-600 rounded-full -top-4 -right-4">
                        ✕
                    </button>
                    <iframe :src="fileUrl" class="w-full h-full rounded-lg" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>