<x-app-layout>
    <x-slot name="title">
        Pengajuan PKL - Sibolang
    </x-slot>

    <div class="min-h-screen px-0 py-8 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 via-white to-emerald-50">
        <div class="max-w-5xl mx-auto space-y-6">
            {{-- ================= HEADER SECTION ================= --}}
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 shadow-lg bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl">
                    Pengajuan <span class="text-transparent bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text">PKL</span>
                </h1>
                <p class="mt-2 text-gray-600">Isi formulir berikut untuk mengajukan Praktik Kerja Lapangan</p>
            </div>

            {{-- ================= NOTIFIKASI ================= --}}
            @foreach (['success', 'error'] as $msg)
                @if (session($msg))
                    <div class="flex items-center gap-3 p-4 rounded-xl shadow-sm transform transition-all duration-300 animate-in slide-in-from-top-2 {{ $msg === 'success' ? 'bg-green-50 border-l-4 border-green-500 text-green-800' : 'bg-red-50 border-l-4 border-red-500 text-red-800' }}">
                        <i class="fa-solid text-xl {{ $msg === 'success' ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600' }}"></i>
                        <span class="font-medium">{{ session($msg) }}</span>
                    </div>
                @endif
            @endforeach

            {{-- ================= ERROR VALIDASI ================= --}}
            @if ($errors->any())
                <div class="p-5 border-l-4 border-red-500 shadow-sm rounded-xl bg-red-50">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="text-red-600 fa-solid fa-circle-exclamation"></i>
                        <h4 class="font-semibold text-red-700">Terjadi Kesalahan Validasi</h4>
                    </div>
                    <ul class="pl-6 space-y-1 text-sm text-red-700 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================= FORM ================= --}}
            <form id="formPengajuan" method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" enctype="multipart/form-data" class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-2xl">
                @csrf
                <input type="hidden" name="force_create" id="force_create" value="0">

                {{-- ================= DATA TEMPAT ================= --}}
                <div class="p-6 border-b border-gray-100 md:p-8 bg-gradient-to-r from-green-50/30 to-transparent">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">Data Tempat PKL</h4>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Instansi <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_tempat" name="nama_tempat" value="{{ old('nama_tempat') }}" required autocomplete="off" 
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white">
                            <div id="warningTempat" class="flex items-center hidden gap-1 mt-2 text-xs md:text-sm text-amber-600">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span></span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                Lokasi Instansi (Google Maps) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="lokasi_maps" name="lokasi_maps" value="{{ old('lokasi_maps') }}" required autocomplete="off"
                                    class="w-full px-4 py-3 pl-10 transition-all duration-200 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white">
                                <div class="absolute -translate-y-1/2 left-3 top-1/2">
                                    <i class="text-gray-400 fa-solid fa-map-location-dot"></i>
                                </div>
                            </div>
                            
                            {{-- LOCATION STATUS --}}
                            <div id="locationStatus" class="hidden p-3 mt-2 border rounded-lg">
                                <div class="flex items-center gap-2" id="statusContent">
                                    <i class="fa-solid fa-spinner fa-spin" id="locationSpinner"></i>
                                    <span id="locationMessage">Sedang mencari lokasi...</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-3">
                                <button type="button" id="btnCariLokasi"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 shadow-sm bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700">
                                    <i class="fa-solid fa-magnifying-glass-location"></i>
                                    Cari Lokasi Otomatis
                                </button>
                                <button type="button" id="btnGoogleMaps"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 shadow-sm bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800">
                                    <i class="fa-solid fa-map"></i>
                                    Buka Google Maps
                                </button>
                            </div>
                            
                            <div id="manualGuide" class="hidden p-4 mt-3 text-sm text-gray-700 border border-blue-200 bg-blue-50 rounded-xl">
                                <p class="flex items-center gap-2 mb-2 font-semibold text-blue-800">
                                    <i class="fa-solid fa-lightbulb"></i>
                                    Cara mendapatkan link Google Maps:
                                </p>
                                <ol class="pl-5 space-y-1 text-gray-600 list-decimal">
                                    <li>Klik tombol <b>"Buka Google Maps"</b></li>
                                    <li>Cari nama instansi Anda</li>
                                    <li>Klik tombol <b>Bagikan (Share)</b></li>
                                    <li>Pilih <b>Salin Link</b></li>
                                    <li>Tempelkan link tersebut ke kolom lokasi</li>
                                </ol>
                            </div>
                            
                            <p class="flex items-center gap-1 mt-2 text-xs text-gray-500">
                                <i class="fa-solid fa-info-circle"></i>
                                Masukkan nama instansi, lalu klik "Cari Lokasi Otomatis" untuk mengisi link Google Maps secara otomatis.
                            </p>
                            
                            {{-- MAP PREVIEW --}}
                            <div id="mapPreview" class="hidden mt-4">
                                <div class="p-4 border border-green-200 shadow-sm bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="flex items-center gap-2 text-sm font-semibold text-green-800">
                                            <i class="fa-solid fa-map"></i>
                                            Preview Lokasi
                                        </h5>
                                    </div>
                                    <div id="map" style="height:300px; width:100%;" class="rounded-lg"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Jenis Instansi <span class="text-red-500">*</span></label>
                            <select name="jenis_tempat" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white">
                                <option value="">-- Pilih Jenis Instansi --</option>
                                @foreach (['Pemerintah','Sekolah','PT','CV'] as $jenis)
                                    <option value="{{ $jenis }}" @selected(old('jenis_tempat') === $jenis)>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">No HP Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" pattern="^08[0-9]{7,14}$" value="{{ old('no_hp') }}" required autocomplete="off" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white">
                            <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxx (min 9 digit, max 15 digit)</p>
                        </div>
                    </div>
                </div>

                {{-- ================= DATA AKADEMIK ================= --}}
                <div class="p-6 border-b border-gray-100 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">Data Akademik</h4>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Semester Saat Ini</label>
                            <input type="text" value="{{ $semesterAktif }}" disabled
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl">
                            @php
                                $romawi = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X'];
                                $semesterValue = $romawi[$semesterAktif] ?? $semesterAktif;
                            @endphp
                            <input type="hidden" name="semester" value="{{ $semesterValue }}">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Alamat Asal Mahasiswa <span class="text-red-500">*</span></label>
                            <textarea name="alamat_asal" required rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 resize-none rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white" 
                                placeholder="Contoh: Ds. Sumberagung RT 13/RW 01, Kec. Sukodadi, Kab. Lamongan">{{ old('alamat_asal') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ================= DOKUMEN ================= --}}
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center w-10 h-10 shadow-md bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">Upload Dokumen Wajib</h4>
                    </div>

                    <div class="p-4 mb-6 border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-blue-600 text-lg mt-0.5"></i>
                            <div class="text-sm text-gray-700">
                                <p>• Upload dokumen yang diperlukan untuk pengajuan PKL</p>
                                <p>• Pastikan file dalam format yang benar dan ukuran maksimal 2MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- UPLOAD AREA WITH FILE LIST PREVIEW --}}
                    @php
                        $dokumenFields = [
                            'dokumen_pembayaran' => ['label' => 'Bukti Pembayaran PKL', 'accept' => '.pdf,.jpg,.png', 'multiple' => false, 'icon' => 'fa-receipt', 'required' => true, 'color' => 'blue'],
                            'dokumen_studi_tour' => ['label' => 'Sertifikat Studi Tour', 'accept' => '.pdf,.doc,.docx', 'multiple' => false, 'icon' => 'fa-ticket', 'required' => true, 'color' => 'purple'],
                            'dokumen_krs' => ['label' => 'Kartu Rencana Studi (KRS) Semester Berjalan', 'accept' => '.pdf', 'multiple' => false, 'icon' => 'fa-file-lines', 'required' => true, 'color' => 'teal'],
                        ];
                    @endphp

                    @foreach ($dokumenFields as $name => $field)
                        <div class="mb-6 group">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ $field['label'] }} <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Upload Area --}}
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50/30 hover:bg-gray-50 transition-all duration-200 group-hover:border-{{ $field['color'] }}-400">
                                <input type="file" 
                                    name="{{ $name }}{{ $field['multiple'] ? '[]' : '' }}" 
                                    id="{{ $name }}"
                                    {{ $field['multiple'] ? 'multiple' : '' }} 
                                    {{ $field['required'] ? 'required' : '' }}
                                    accept="{{ $field['accept'] }}"
                                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer">
                                <div class="p-5 text-center">
                                    <i class="fa-solid {{ $field['icon'] }} text-3xl text-gray-400 mb-2 group-hover:text-{{ $field['color'] }}-500 transition-colors"></i>
                                    <p class="text-sm text-gray-500">
                                        <span class="font-medium text-{{ $field['color'] }}-600">Klik untuk upload</span> atau drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Format: {{ strtoupper(str_replace('.', ', ', $field['accept'])) }} | Max 2MB
                                    </p>
                                </div>
                            </div>
                            
                            {{-- File List Preview --}}
                            <div id="{{ $name }}-list" class="mt-3 space-y-2"></div>
                        </div>
                    @endforeach
                </div>

                {{-- ================= BUTTON ================= --}}
                <div class="flex flex-col justify-end gap-3 px-6 py-5 border-t border-gray-200 md:px-8 bg-gray-50 sm:flex-row">
                    <a href="{{ route('mahasiswa.dashboard') }}" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-paper-plane"></i>
                        Ajukan PKL
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Load Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== FILE PREVIEW ==========
            function setupFilePreview(inputId, listId, isMultiple = false) {
                const input = document.getElementById(inputId);
                const listContainer = document.getElementById(listId);
                
                if (!input || !listContainer) return;
                
                input.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    
                    if (files.length === 0) {
                        listContainer.innerHTML = '';
                        return;
                    }
                    
                    let html = '';
                    
                    if (isMultiple) {
                        html = `
                            <div class="p-3 border border-green-200 bg-green-50 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="text-green-600 fa-solid fa-file-circle-check"></i>
                                    <span class="text-sm font-semibold text-green-700">File yang akan diupload (${files.length} file):</span>
                                </div>
                                <div class="space-y-1 overflow-y-auto max-h-40">
                        `;
                        
                        files.forEach((file, index) => {
                            const fileSize = (file.size / 1024).toFixed(2);
                            html += `
                                <div class="flex items-center justify-between p-2 text-sm bg-white border border-green-100 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <i class="text-red-500 fa-regular fa-file-pdf"></i>
                                        <span class="max-w-xs text-gray-700 truncate">${file.name}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">${fileSize} KB</span>
                                </div>
                            `;
                        });
                        
                        html += `</div></div>`;
                    } else {
                        const file = files[0];
                        const fileSize = (file.size / 1024).toFixed(2);
                        const fileType = file.type;
                        const icon = fileType.includes('pdf') ? 'fa-file-pdf' : (fileType.includes('image') ? 'fa-file-image' : 'fa-file-alt');
                        const color = fileType.includes('pdf') ? 'red' : (fileType.includes('image') ? 'blue' : 'gray');
                        
                        html = `
                            <div class="bg-${color === 'red' ? 'red' : color === 'blue' ? 'blue' : 'green'}-50 rounded-xl p-3 border border-${color === 'red' ? 'red' : color === 'blue' ? 'blue' : 'green'}-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                                            <i class="fa-regular ${icon} text-${color === 'red' ? 'red' : color === 'blue' ? 'blue' : 'green'}-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">${file.name}</p>
                                            <p class="text-xs text-gray-500">${fileSize} KB</p>
                                        </div>
                                    </div>
                                    <i class="text-lg text-green-500 fa-solid fa-check-circle"></i>
                                </div>
                            </div>
                        `;
                    }
                    
                    listContainer.innerHTML = html;
                    listContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
            
            setupFilePreview('dokumen_pembayaran', 'dokumen_pembayaran-list', false);
            setupFilePreview('dokumen_studi_tour', 'dokumen_studi_tour-list', false);
            setupFilePreview('dokumen_krs', 'dokumen_krs-list', false);
            
            // ========== MAIN VARIABLES ==========
            const inputNama = document.getElementById("nama_tempat");
            const warningBox = document.getElementById('warningTempat');
            const lokasiInput = document.getElementById("lokasi_maps");
            const form = document.getElementById("formPengajuan");
            const locationStatus = document.getElementById('locationStatus');
            const statusContent = document.getElementById('statusContent');
            const locationMessage = document.getElementById('locationMessage');
            const locationSpinner = document.getElementById('locationSpinner');
            const manualGuide = document.getElementById('manualGuide');
            const mapPreview = document.getElementById('mapPreview');
            const btnCari = document.getElementById('btnCariLokasi');

            let map = null;
            let marker = null;
            let isLocating = false;

            // ========== UPDATE LOCATION STATUS ==========
            function updateLocationStatus(message, type = 'loading') {
                locationStatus.classList.remove('hidden');
                locationMessage.textContent = message;
                
                // Reset classes
                locationStatus.className = 'mt-2 p-3 rounded-lg border';
                
                if (type === 'loading') {
                    locationStatus.classList.add('bg-yellow-50', 'border-yellow-200');
                    locationSpinner.classList.remove('hidden');
                    statusContent.className = 'flex items-center gap-2 text-yellow-700';
                } else if (type === 'success') {
                    locationStatus.classList.add('bg-green-50', 'border-green-200');
                    locationSpinner.classList.add('hidden');
                    statusContent.className = 'flex items-center gap-2 text-green-700';
                } else if (type === 'error') {
                    locationStatus.classList.add('bg-red-50', 'border-red-200');
                    locationSpinner.classList.add('hidden');
                    statusContent.className = 'flex items-center gap-2 text-red-700';
                } else if (type === 'warning') {
                    locationStatus.classList.add('bg-yellow-50', 'border-yellow-200');
                    locationSpinner.classList.add('hidden');
                    statusContent.className = 'flex items-center gap-2 text-yellow-700';
                }
            }

            function hideLocationStatus() {
                locationStatus.classList.add('hidden');
            }

            // ========== SHOW MAP ==========
            function showMap(lat, lon) {
                mapPreview.classList.remove("hidden");
                
                if (!map) {
                    map = L.map('map').setView([lat, lon], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                } else {
                    map.setView([lat, lon], 16);
                    if (marker) {
                        map.removeLayer(marker);
                    }
                }
                
                marker = L.marker([lat, lon]).addTo(map);
                
                setTimeout(() => {
                    if (map) map.invalidateSize();
                }, 300);
            }

            // ========== SEARCH LOCATION WITH PHOTON API ==========
            function searchLocation(tempat) {
                if (isLocating) return;
                if (!tempat || tempat.length < 3) {
                    updateLocationStatus('Masukkan minimal 3 karakter untuk mencari lokasi', 'warning');
                    return;
                }

                isLocating = true;
                lokasiInput.value = "Mencari lokasi...";
                lokasiInput.readOnly = true;
                manualGuide.classList.add('hidden');
                mapPreview.classList.add('hidden');
                warningBox.classList.add('hidden');
                hideLocationStatus();
                
                updateLocationStatus(`Mencari lokasi untuk "${tempat}"...`, 'loading');

                // Gunakan Photon API (sudah terbukti berhasil untuk Lamongan)
                const query = encodeURIComponent(tempat);
                const url = `https://photon.komoot.io/api/?q=${query}&limit=5&lang=id`;

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'User-Agent': 'Sibolang-PKL-App/1.0'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP ${res.status} - ${res.statusText}`);
                    return res.json();
                })
                .then(data => {
                    console.log('Photon Response:', data);
                    
                    if (!data.features || data.features.length === 0) {
                        // Coba dengan "Indonesia"
                        const query2 = encodeURIComponent(`${tempat}, Indonesia`);
                        return fetch(`https://photon.komoot.io/api/?q=${query2}&limit=5&lang=id`, {
                            headers: {
                                'Accept': 'application/json',
                                'User-Agent': 'Sibolang-PKL-App/1.0'
                            }
                        }).then(res => res.json());
                    }
                    return data;
                })
                .then(data => {
                    console.log('Photon Response (2nd try):', data);
                    
                    if (!data.features || data.features.length === 0) {
                        throw new Error('Lokasi tidak ditemukan di database Photon');
                    }

                    const result = data.features[0];
                    const coords = result.geometry.coordinates;
                    const lon = coords[0];
                    const lat = coords[1];
                    
                    const name = result.properties?.name || result.properties?.street || tempat;
                    const city = result.properties?.city || result.properties?.state || '';
                    const displayName = `${name}${city ? ', ' + city : ''}`;

                    const mapsLink = `https://www.google.com/maps?q=${lat},${lon}`;
                    lokasiInput.value = mapsLink;
                    
                    showMap(lat, lon);
                    updateLocationStatus(`✓ Lokasi ditemukan: ${displayName.substring(0, 80)}`, 'success');
                    manualGuide.classList.add('hidden');
                    warningBox.classList.add('hidden');
                })
                .catch(err => {
                    console.error('Error searching location with Photon:', err);
                    
                    manualGuide.classList.remove('hidden');
                    warningBox.classList.remove('hidden');
                    warningBox.innerHTML = `
                        <i class="text-blue-600 fa-solid fa-info-circle"></i>
                        <span>💡 Lokasi tidak ditemukan otomatis. Klik "Buka Google Maps" untuk mencari manual, lalu salin link-nya.</span>
                    `;
                    
                    lokasiInput.value = "";
                    lokasiInput.placeholder = `Contoh: https://www.google.com/maps?q=-7.123,112.456`;
                    
                    updateLocationStatus('Lokasi tidak ditemukan. Silakan cari manual melalui Google Maps.', 'error');
                })
                .finally(() => {
                    isLocating = false;
                    lokasiInput.readOnly = false;
                    
                    if (!lokasiInput.value) {
                        lokasiInput.value = "";
                    }
                });
            }

            // ========== EVENT: CEK KEMIRIPAN NAMA TEMPAT ==========
            let timeout = null;
            inputNama.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length < 4) {
                        warningBox.classList.add('hidden');
                        return;
                    }
                    
                    fetch("{{ route('mahasiswa.pengajuan.cek-kemiripan') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            nama_tempat: this.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.mirip) {
                            warningBox.innerHTML = `
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>⚠️ Nama ini mirip dengan <b>${data.nama_mirip}</b>. Pastikan ini memang tempat yang berbeda, jika ya abaikan pesan ini</span>
                            `;
                            warningBox.classList.remove('hidden');
                        } else {
                            warningBox.classList.add('hidden');
                        }
                    })
                    .catch(err => console.error('Error checking similarity:', err));
                }, 600);
            });

            // ========== EVENT: CARI LOKASI (Tombol) ==========
            btnCari.addEventListener('click', function () {
                const tempat = inputNama.value.trim();
                if (!tempat || tempat.length < 3) {
                    updateLocationStatus('Masukkan nama instansi terlebih dahulu (minimal 3 karakter)', 'warning');
                    inputNama.focus();
                    return;
                }
                searchLocation(tempat);
            });

            // ========== EVENT: ENTER KEY ==========
            inputNama.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btnCari.click();
                }
            });

            // ========== EVENT: GOOGLE MAPS BUTTON ==========
            document.getElementById("btnGoogleMaps").addEventListener("click", function () {
                let tempat = inputNama.value.trim();
                let url = "https://www.google.com/maps";
                if (tempat) {
                    url = `https://www.google.com/maps/search/${encodeURIComponent(tempat)}`;
                }
                window.open(url, "_blank");
                manualGuide.classList.remove('hidden');
            });

            // ========== VALIDASI FORM ==========
            form.addEventListener("submit", function (e) {
                let lokasi = lokasiInput.value.trim();
                if (!lokasi || (!lokasi.includes("google.com/maps") && !lokasi.includes("maps.app.goo.gl"))) {
                    alert("⚠️ Lokasi harus berupa link Google Maps yang valid.\n\nContoh: https://www.google.com/maps?q=-7.123,112.456\n\nAtau klik tombol 'Buka Google Maps' untuk mencari manual.");
                    e.preventDefault();
                    return;
                }
            });

            // ========== AUTO DETECT LINK ==========
            lokasiInput.addEventListener("change", function () {
                const url = this.value.trim();
                if (!url) return;
                
                let match = url.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (!match) {
                    match = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                }
                
                if (match) {
                    const lat = parseFloat(match[1]);
                    const lon = parseFloat(match[2]);
                    showMap(lat, lon);
                    updateLocationStatus('✓ Lokasi valid dan siap digunakan', 'success');
                }
            });

            // ========== CHECK IF LOCATION ALREADY FILLED ==========
            if (lokasiInput.value && lokasiInput.value.includes('google.com/maps')) {
                const match = lokasiInput.value.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (match) {
                    showMap(parseFloat(match[1]), parseFloat(match[2]));
                    updateLocationStatus('✓ Lokasi sudah terisi dan valid', 'success');
                }
            }

            console.log('✅ Pengajuan PKL script loaded successfully!');
        });
    </script>
</x-app-layout>