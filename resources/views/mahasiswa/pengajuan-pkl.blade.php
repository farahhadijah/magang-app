<x-app-layout>
    <x-slot name="title">
        Pengajuan PKL - MagangApp
    </x-slot>

    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 via-white to-emerald-50">
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
                            <div id="warningTempat" class="flex items-center hidden gap-1 mt-2 text-sm text-amber-600">
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
                            
                            <div class="flex flex-wrap gap-3 mt-3">
                                <button type="button" id="btnGoogleMaps"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 shadow-sm bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800">
                                    <i class="fa-solid fa-map"></i>
                                    Cari Lokasi di Google Maps
                                </button>
                            </div>
                            
                            <div id="manualGuide" class="hidden p-4 mt-3 text-sm text-gray-700 border border-blue-200 bg-blue-50 rounded-xl">
                                <p class="flex items-center gap-2 mb-2 font-semibold text-blue-800">
                                    <i class="fa-solid fa-lightbulb"></i>
                                    Jika lokasi tidak ditemukan otomatis:
                                </p>
                                <ol class="pl-5 space-y-1 text-gray-600 list-decimal">
                                    <li>Klik tombol <b>"Cari Lokasi di Google Maps"</b></li>
                                    <li>Cari nama instansi Anda</li>
                                    <li>Klik tombol <b>Bagikan (Share)</b></li>
                                    <li>Pilih <b>Salin Link</b></li>
                                    <li>Tempelkan link tersebut ke kolom lokasi</li>
                                </ol>
                            </div>
                            
                            <p class="flex items-center gap-1 mt-2 text-xs text-gray-500">
                                <i class="fa-solid fa-info-circle"></i>
                                Lokasi akan terisi otomatis setelah nama instansi dimasukkan. Jika tidak ditemukan, Anda dapat mengisinya secara manual.
                            </p>
                            
                            {{-- MAP PREVIEW --}}
                            <div id="mapPreview" class="hidden mt-4 transition-all duration-300">
                                <div class="p-4 border border-green-200 shadow-sm bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="flex items-center gap-2 text-sm font-semibold text-green-800">
                                            <i class="fa-solid fa-map"></i>
                                            Preview Lokasi
                                        </h5>
                                        <span class="text-xs text-gray-500">
                                            <i class="fa-solid fa-arrows-up-down-left-right"></i> Drag untuk melihat area sekitar
                                        </span>
                                    </div>
                                    <div id="map" class="rounded-lg shadow-md" style="height:320px;"></div>
                                </div>
                            </div>
                            <p id="previewInfo" class="hidden mt-2 text-xs text-gray-500">
                                <i class="fa-solid fa-eye-slash"></i> Preview hanya tersedia untuk lokasi yang ditemukan otomatis.
                            </p>
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
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Semester (Angka Romawi) <span class="text-red-500">*</span></label>
                            <input type="text" name="semester" value="{{ old('semester') }}" required pattern="^(I|II|III|IV|V|VI|VII|VIII|IX|X)$" 
                                title="Gunakan angka romawi, contoh: V" 
                                class="w-full px-4 py-3 uppercase border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 hover:bg-white" 
                                style="text-transform: uppercase;" placeholder="Contoh: V">
                            <p class="flex items-center gap-1 mt-1 text-xs text-gray-500">
                                <i class="fa-solid fa-graduation-cap"></i>
                                Gunakan angka romawi (I, II, III, IV, V, dst)
                            </p>
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
                                <p>• Upload KHS dari semester 1 sampai semester terakhir.</p>
                                <p>• Semua dokumen wajib dalam format PDF (kecuali pembayaran boleh gambar).</p>
                                <p class="mt-1 text-xs text-blue-600">• Maksimal ukuran file: 2MB per file</p>
                            </div>
                        </div>
                    </div>

                    {{-- UPLOAD AREA STYLING DENGAN FILE LIST PREVIEW --}}
                    @php
                        $dokumenFields = [
                            'dokumen_khs' => ['label' => 'KHS Semester 1 - Terakhir', 'accept' => '.pdf,.doc,.docx', 'multiple' => true, 'icon' => 'fa-file-pdf', 'required' => true, 'color' => 'green'],
                            'dokumen_pembayaran' => ['label' => 'Bukti Pembayaran PKL', 'accept' => '.pdf,.jpg,.png', 'multiple' => false, 'icon' => 'fa-receipt', 'required' => true, 'color' => 'blue'],
                            'dokumen_studi_tour' => ['label' => 'Sertifikat Studi Tour', 'accept' => '.pdf,.doc,.docx', 'multiple' => false, 'icon' => 'fa-ticket', 'required' => true, 'color' => 'purple'],
                            'dokumen_form_pkn' => ['label' => 'Form Pengajuan PKN', 'accept' => '.pdf', 'multiple' => false, 'icon' => 'fa-file-alt', 'required' => true, 'color' => 'orange'],
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
                                        @if($field['multiple']) (Multiple files allowed) @endif
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

    <script>
        // Script untuk menampilkan file list preview
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi untuk menampilkan daftar file yang dipilih
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
                        // Untuk multiple files (KHS)
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
                        // Untuk single file
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
                    
                    // Auto scroll ke file list
                    listContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
            
            // Setup preview untuk semua input file
            setupFilePreview('dokumen_khs', 'dokumen_khs-list', true);
            setupFilePreview('dokumen_pembayaran', 'dokumen_pembayaran-list', false);
            setupFilePreview('dokumen_studi_tour', 'dokumen_studi_tour-list', false);
            setupFilePreview('dokumen_form_pkn', 'dokumen_form_pkn-list', false);
            
            // ========== SCRIPT ORIGINAL (TIDAK DIUBAH) ==========
            const inputNama = document.getElementById("nama_tempat");
            const warningBox = document.getElementById('warningTempat');
            const lokasiInput = document.getElementById("lokasi_maps");

            // jika mahasiswa mulai mengedit lokasi manual
            lokasiInput.addEventListener("focus", function(){
                this.dataset.auto = "false";
            });
            const form = document.getElementById("formPengajuan");
            let timeout = null;
            // CEK KEMIRIPAN NAMA TEMPAT
            inputNama.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (inputNama.value.length < 4) {
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
                            nama_tempat: inputNama.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.mirip) {
                            warningBox.innerHTML =
                                `⚠️ Nama ini mirip dengan <b>${data.nama_mirip}</b>. Pastikan ini memang tempat yang berbeda, jika ya abaikan pesan ini`;
                            warningBox.classList.remove('hidden');
                        } else {
                            warningBox.classList.add('hidden');
                        }
                    });
                }, 600);
            });
            // AUTO UPPERCASE SEMESTER
            const semesterInput = document.querySelector('input[name="semester"]');
            if (semesterInput) {
                semesterInput.addEventListener('input', function () {
                    this.value = this.value.toUpperCase();
                });
            }
            // MAP PREVIEW
            let map;
            let marker;
            inputNama.addEventListener("blur", function () {
                let tempat = this.value.trim();
                if (!tempat || tempat.length < 3) return;
                lokasiInput.value = "Mencari lokasi...";
                let query = `${tempat} Indonesia`;
                fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`)
                .then(res => res.json())
                .then(data => {
                    if (!data.features || data.features.length === 0) {
                        alert("Lokasi tidak ditemukan otomatis. Silakan cari melalui Google Maps.");
                        lokasiInput.value = "";
                        document.getElementById("manualGuide")?.classList.remove("hidden");
                        document.getElementById("mapPreview").classList.add("hidden");
                        document.getElementById("previewInfo").classList.remove("hidden");
                        return;
                    }
                    let coords = data.features[0].geometry.coordinates;
                    let lon = coords[0];
                    let lat = coords[1];
                    let mapsLink = `https://www.google.com/maps?q=${lat},${lon}`;
                    lokasiInput.value = mapsLink;
                    lokasiInput.dataset.auto = "true";
                    showMap(lat, lon);
                    document.getElementById("previewInfo").classList.add("hidden");
                })
                .catch(err => {
                    console.error(err);
                    alert("Gagal mencari lokasi otomatis. Silakan isi manual.");
                    lokasiInput.readOnly = false;
                    lokasiInput.value = "";
                });
            });
            // FUNGSI MENAMPILKAN MAP
            function showMap(lat, lon) {
                const mapContainer = document.getElementById("mapPreview");
                mapContainer.classList.remove("hidden");
                if (!map) {
                    map = L.map('map').setView([lat, lon], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19
                    }).addTo(map);
                } else {
                    map.setView([lat, lon], 16);
                    if (marker) {
                        map.removeLayer(marker);
                    }
                }
                marker = L.marker([lat, lon]).addTo(map);
                map.invalidateSize();
            }
            // TOMBOL BUKA GOOGLE MAPS
            let btnMaps = document.getElementById("btnGoogleMaps");
            if (btnMaps) {
                btnMaps.addEventListener("click", function () {
                    let tempat = inputNama.value.trim();
                    let url = "https://www.google.com/maps";
                    if (tempat) {
                        url = `https://www.google.com/maps/search/${encodeURIComponent(tempat)}`;
                    }
                    window.open(url, "_blank");
                    document.getElementById("manualGuide")?.classList.remove("hidden");
                });
            }
            function extractLatLng(url){
                if(!url) return null;
                // format ?q=lat,lng
                let match1 = url.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
                if(match1){
                    return {
                        lat: parseFloat(match1[1]),
                        lng: parseFloat(match1[2])
                    };
                }
                // format @lat,lng
                let match2 = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                if(match2){
                    return {
                        lat: parseFloat(match2[1]),
                        lng: parseFloat(match2[2])
                    };
                }
                // format /place/.../@lat,lng
                let match3 = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+),/);
                if(match3){
                    return {
                        lat: parseFloat(match3[1]),
                        lng: parseFloat(match3[2])
                    };
                }
                return null;
            }
            // VALIDASI LINK GOOGLE MAPS
            form.addEventListener("submit", function (e) {

                let lokasi = lokasiInput.value.trim();

                if(
                    !lokasi.includes("google.com/maps") &&
                    !lokasi.includes("maps.app.goo.gl")
                ){
                    alert("Lokasi harus berupa link Google Maps.");
                    e.preventDefault();
                    return;
                }

                // jangan cek koordinat di frontend
                // server Laravel akan memproses linknya
            });
            // Improvement
            lokasiInput.addEventListener("change", function(){
            let coords = extractLatLng(this.value);
            if(coords){
                showMap(coords.lat, coords.lng);
            }
        });
        // jika mahasiswa mengganti link manual
        lokasiInput.addEventListener("input", function(){

            const autoGenerated = this.dataset.auto === "true";

            if(!autoGenerated){
                document.getElementById("mapPreview").classList.add("hidden");
                document.getElementById("previewInfo").classList.remove("hidden");
            }

        });
        });
    </script>
</x-app-layout>