<x-app-layout>
    <x-slot name="title">
        Pengajuan PKL - MagangApp
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-6">
        {{-- ================= NOTIFIKASI ================= --}}
        @foreach (['success', 'error'] as $msg)
            @if (session($msg))
                <div class="flex items-center gap-2 p-4 rounded-xl border {{ $msg === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                    <i class="fa-solid {{ $msg === 'success' ? 'fa-circle-check text-green-600' : 'fa-circle-xmark text-red-600' }}"></i>
                    <span>{{ session($msg) }}</span>
                </div>
            @endif
        @endforeach

        {{-- ================= ERROR VALIDASI ================= --}}
        @if ($errors->any())
            <div class="p-4 border border-red-200 rounded-xl bg-red-50">
                <h4 class="mb-2 font-semibold text-red-700">Terjadi Kesalahan</h4>
                <ul class="pl-4 text-sm text-red-700 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form id="formPengajuan" method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" enctype="multipart/form-data" class="p-6 space-y-6 bg-white border border-green-100 shadow rounded-xl">
            @csrf
            <input type="hidden" name="force_create" id="force_create" value="0">

            {{-- ================= DATA TEMPAT ================= --}}
            <div>
                <h4 class="mb-4 text-lg font-semibold text-green-800">Data Tempat PKL</h4>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="font-medium text-green-800">Nama instansi </p>
                        <input type="text" id="nama_tempat" name="nama_tempat" value="{{ old('nama_tempat') }}" required autocomplete="off" class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500">
                        <div id="warningTempat" class="hidden mt-2 text-sm text-amber-600"></div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium text-green-800">
                            Lokasi Instansi (Google Maps)
                        </label>
                        <input 
                            type="text"
                            id="lokasi_maps"
                            name="lokasi_maps"
                            value="{{ old('lokasi_maps') }}"
                            required
                            autocomplete="off"
                            class="block w-full border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500">
                            <div class="mt-2 space-y-2">
                                <button type="button" id="btnGoogleMaps"
                                    class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Cari Lokasi di Google Maps
                                </button>
                                <div id="manualGuide" class="hidden p-3 text-sm text-gray-700 border rounded-lg bg-gray-50">
                                    <p class="mb-2 font-semibold text-gray-800">
                                        Jika lokasi tidak ditemukan otomatis:
                                    </p>
                                    <ol class="pl-5 space-y-1 list-decimal">
                                        <li>Klik tombol <b>"Cari Lokasi di Google Maps"</b></li>
                                        <li>Cari nama instansi Anda</li>
                                        <li>Klik tombol <b>Bagikan (Share)</b></li>
                                        <li>Pilih <b>Salin Link</b></li>
                                        <li>Tempelkan link tersebut ke kolom lokasi</li>
                                    </ol>
                                </div>
                            </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Lokasi akan terisi otomatis setelah nama instansi dimasukkan.
                            Jika tidak ditemukan atau tidak sesuai, Anda dapat mengisinya secara manual.
                        </p>
                        {{-- MAP PREVIEW --}}
                        <div id="mapPreview" class="hidden mt-4 transition-all duration-300">
                            <div class="p-3 border border-green-100 shadow-sm bg-green-50 rounded-xl">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="text-sm font-semibold text-green-800">
                                        Preview Lokasi
                                    </h5>
                                    <span class="text-xs text-gray-500">
                                        Drag untuk melihat area sekitar
                                    </span>
                                </div>
                                <div id="map" class="border rounded-lg" style="height:320px;"></div>
                            </div>
                        </div>
                        <p id="previewInfo" class="hidden mt-2 text-xs text-gray-500">
                        Preview hanya tersedia untuk lokasi yang ditemukan otomatis.
                        </p>
                    </div>

                    <div>
                        <p class="font-medium text-green-800">Jenis instansi</p>
                        <select name="jenis_tempat" required class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Jenis Instansi --</option>
                            @foreach (['Pemerintah','Sekolah','PT','CV'] as $jenis)
                                <option value="{{ $jenis }}" @selected(old('jenis_tempat') === $jenis)>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <p class="font-medium text-green-800">No HP instansi</p>
                        <input type="text" name="no_hp" pattern="^08[0-9]{7,14}$" value="{{ old('no_hp') }}" required autocomplete="off" class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            {{-- ================= DATA AKADEMIK ================= --}}
            <div>
                <div class="grid gap-4 md:grid-cols-2">
                    {{-- SEMESTER --}}
                    <div>
                        <label class="block mb-1 font-medium text-green-800">Semester (Angka Romawi) *</label>
                        <input type="text" name="semester" value="{{ old('semester') }}" required pattern="^(I|II|III|IV|V|VI|VII|VIII|IX|X)$" title="Gunakan angka romawi, contoh: V" class="block w-full uppercase rounded-lg input focus:ring-green-500 focus:border-green-500" style="text-transform: uppercase;">
                        <p class="mt-1 text-xs text-gray-500">Gunakan angka romawi (I, II, III, IV, V, dst).</p>
                    </div>

                    {{-- ALAMAT ASAL --}}
                    <div>
                        <label class="block mb-1 font-medium text-green-800">Alamat Asal Mahasiswa *</label>
                        <textarea name="alamat_asal" required rows="3" class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500" placeholder="Contoh: Ds. Sumberagung RT 13/RW 01, Kec. Sukodadi, Kab. Lamongan">{{ old('alamat_asal') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ================= DOKUMEN ================= --}}
            <div>
                <h4 class="mb-3 font-semibold text-green-800">Upload Dokumen Wajib</h4>

                <div class="p-4 mb-4 text-sm text-gray-600 border border-gray-200 rounded-lg bg-gray-50">
                    • Upload KHS dari semester 1 sampai semester terakhir. <br>
                    • Semua dokumen wajib dalam format PDF (kecuali pembayaran boleh gambar).
                </div>

                {{-- ================= KHS MULTIPLE ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">KHS Semester 1 - Terakhir *</label>
                    <input type="file" name="dokumen_khs[]" multiple required accept=".pdf,.doc,.docx" class="block w-full text-sm">
                </div>

                {{-- ================= PEMBAYARAN ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">Bukti Pembayaran PKL *</label>
                    <input type="file" name="dokumen_pembayaran" required accept=".pdf,.jpg,.png" class="block w-full text-sm">
                </div>

                {{-- ================= STUDI TOUR ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">Sertifikat Studi Tour *</label>
                    <input type="file" name="dokumen_studi_tour" required accept=".pdf,.doc,.docx" class="block w-full text-sm">
                </div>

                {{-- ================= FORM PKN (BARU) ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">Form Pengajuan PKN *</label>
                    <input type="file" name="dokumen_form_pkn" required accept=".pdf" class="block w-full text-sm">
                </div>

                {{-- ================= KRS REMEDIAL (BARU) ================= --}}
                {{-- <div>
                    <label class="block mb-1 font-medium text-green-800">KRS *</label>
                    <input type="file" name="dokumen_krs_remedial" required accept=".pdf" class="block w-full text-sm">
                </div> --}}
            </div>

            {{-- ================= BUTTON ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('mahasiswa.dashboard') }}" class="px-4 py-2 text-sm font-medium text-green-700 transition border border-green-300 rounded-lg hover:bg-green-50">Kembali</a>
                <button type="submit" class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-1 fa-solid fa-paper-plane"></i>Ajukan PKL
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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