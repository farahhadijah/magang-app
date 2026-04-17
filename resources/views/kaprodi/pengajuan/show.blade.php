<x-app-layout>
    <x-slot name="title">
        Detail Pengajuan - MagangApp
    </x-slot>
    
    <div x-data="pdfViewer()">
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
            
            @php
                $lokasi = $pengajuan->tempatPkl->lokasi_maps ?? null;
            @endphp

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

                            {{-- SEARCH BOX --}}
                            <div class="mb-4">
                                <input type="text" 
                                       id="searchDosenInput" 
                                       placeholder="Cari dosen (nama, keahlian, atau prodi)..." 
                                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>

                            {{-- PILIHAN PRODI (TABS) --}}
                            <div class="mb-4 border-b border-gray-200">
                                <nav class="flex flex-wrap gap-1 -mb-px">
                                    @foreach($dosenGrouped as $prodiId => $dosens)
                                        <button type="button"
                                            onclick="showDosen({{ $prodiId }})"
                                            class="tab-prodi px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200
                                                   {{ $loop->first ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-green-600 hover:text-white' }}"
                                            data-prodi="{{ $prodiId }}">
                                            {{ $dosens->first()->prodi->nama }}
                                            @if($prodiId == auth()->user()->dosen->prodi_id)
                                                <span class="ml-1 text-xs">(Anda)</span>
                                            @endif
                                            <span class="ml-1 text-xs bg-white bg-opacity-30 rounded-full px-1.5 py-0.5">
                                                {{ $dosens->count() }}
                                            </span>
                                        </button>
                                    @endforeach
                                </nav>
                            </div>

                            {{-- LIST DOSEN PER PRODI --}}
                            <div id="dosenContainer" class="max-h-96 overflow-y-auto">
                                @foreach($dosenGrouped as $prodiId => $dosens)
                                    <div id="prodi-{{ $prodiId }}" class="space-y-3 {{ $loop->first ? '' : 'hidden' }}">
                                        <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-4">
                                            @foreach($dosens as $d)
                                                <label class="flex items-start p-3 transition border rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-300">
                                                    <input type="radio"
                                                        name="id_dosen"
                                                        value="{{ $d->id }}"
                                                        class="mt-1 mr-3"
                                                        required>
                                                    
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-800"><p class="text-sm ml-1">{{ $d->nama }}</p></div>
                                                        @if($d->keahlian)
                                                            <div class="mt-1 text-xs text-green-600">
                                                                <span class="font-semibold"></span> {{ Str::limit($d->keahlian, 50) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- EMPTY STATE --}}
                                <div id="emptyState" class="hidden p-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="mt-2">Tidak ada dosen yang sesuai dengan pencarian</p>
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

    <script>
        function pdfViewer() {
            return {
                isOpen: false,
                fileUrl: '',
                openModal(url) {
                    this.fileUrl = url;
                    this.isOpen = true;
                },
                closeModal() {
                    this.fileUrl = '';
                    this.isOpen = false;
                }
            }
        }

        // Fungsi untuk menampilkan dosen berdasarkan prodi yang dipilih
        function showDosen(prodiId) {
            // Update class tabs
            document.querySelectorAll('.tab-prodi').forEach(tab => {
                tab.classList.remove('bg-green-600', 'text-white');
                tab.classList.add('bg-gray-100', 'text-gray-600');
            });
            
            const activeTab = document.querySelector(`.tab-prodi[data-prodi="${prodiId}"]`);
            if (activeTab) {
                activeTab.classList.remove('bg-gray-100', 'text-gray-600');
                activeTab.classList.add('bg-green-600', 'text-white');
            }
            
            // Sembunyikan semua container dosen
            document.querySelectorAll('[id^="prodi-"]').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Tampilkan yang dipilih
            const selectedProdi = document.getElementById('prodi-' + prodiId);
            if (selectedProdi) {
                selectedProdi.classList.remove('hidden');
            }
            
            // Reset search input
            const searchInput = document.getElementById('searchDosenInput');
            if (searchInput) {
                searchInput.value = '';
                filterDosen('');
            }
        }

        // Fungsi filter dosen berdasarkan pencarian
        function filterDosen(keyword) {
            const allDosenLabels = document.querySelectorAll('#dosenContainer label');
            let hasVisible = false;
            
            allDosenLabels.forEach(label => {
                const dosenName = label.querySelector('.font-medium')?.innerText.toLowerCase() || '';
                const dosenKeahlian = label.querySelector('.text-green-600')?.innerText.toLowerCase() || '';
                const dosenProdi = label.querySelector('.text-gray-500')?.innerText.toLowerCase() || '';
                const searchLower = keyword.toLowerCase();
                
                const isMatch = dosenName.includes(searchLower) || 
                               dosenKeahlian.includes(searchLower) || 
                               dosenProdi.includes(searchLower);
                
                if (isMatch) {
                    label.style.display = '';
                    hasVisible = true;
                } else {
                    label.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            if (emptyState) {
                if (!hasVisible && keyword.length > 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        // Event listener untuk search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchDosenInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    filterDosen(e.target.value);
                });
            }
            
            // Inisialisasi map
            let lokasi = @json($lokasi);
            if (lokasi) {
                let lat = null;
                let lon = null;
                
                let qMatch = lokasi.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
                if(qMatch){
                    lat = parseFloat(qMatch[1]);
                    lon = parseFloat(qMatch[2]);
                }
                
                if(!lat){
                    let atMatch = lokasi.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                    if(atMatch){
                        lat = parseFloat(atMatch[1]);
                        lon = parseFloat(atMatch[2]);
                    }
                }
                
                if(lat && lon){
                    const kampusLat = -7.1224094;
                    const kampusLng = 112.4223971;
                    
                    let map = L.map('mapKaprodi').setView([lat, lon], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                    
                    var greenIcon = new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                        iconSize: [25,41],
                        iconAnchor: [12,41],
                        popupAnchor: [1,-34],
                        shadowSize: [41,41]
                    });
                    
                    L.marker([lat, lon]).addTo(map).bindPopup("Tempat PKL Mahasiswa").openPopup();
                    L.marker([kampusLat, kampusLng], {icon: greenIcon}).addTo(map).bindPopup("Kampus Universitas Islam Lamongan");
                    L.polyline([[kampusLat, kampusLng], [lat, lon]], { color: 'blue', weight: 4, opacity: 0.7 }).addTo(map);
                    
                    let group = new L.featureGroup([L.marker([lat, lon]), L.marker([kampusLat, kampusLng])]);
                    map.fitBounds(group.getBounds().pad(0.3));
                }
            }
        });
    </script>
</x-app-layout>