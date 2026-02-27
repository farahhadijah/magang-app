<x-app-layout>

    <x-slot name="title">
        Pengajuan PKL - MagangApp
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-6">

        {{-- ================= NOTIFIKASI ================= --}}
        @foreach (['success', 'error'] as $msg)
            @if (session($msg))
                <div class="flex items-center gap-2 p-4 rounded-xl border
                    {{ $msg === 'success'
                        ? 'bg-green-50 border-green-200 text-green-800'
                        : 'bg-red-50 border-red-200 text-red-800'
                    }}">
                    <i class="fa-solid
                        {{ $msg === 'success'
                            ? 'fa-circle-check text-green-600'
                            : 'fa-circle-xmark text-red-600'
                        }}"></i>
                    <span>{{ session($msg) }}</span>
                </div>
            @endif
        @endforeach

        {{-- ================= ERROR VALIDASI ================= --}}
        @if ($errors->any())
            <div class="p-4 border border-red-200 rounded-xl bg-red-50">
                <h4 class="mb-2 font-semibold text-red-700">
                    Terjadi Kesalahan
                </h4>
                <ul class="pl-4 text-sm text-red-700 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form id="formPengajuan"
              method="POST"
              action="{{ route('mahasiswa.pengajuan.store') }}"
              enctype="multipart/form-data"
              class="p-6 space-y-6 bg-white border border-green-100 shadow rounded-xl">
            @csrf

            <input type="hidden" name="force_create" id="force_create" value="0">

            {{-- ================= DATA TEMPAT ================= --}}
            <div>
                <h4 class="mb-4 text-lg font-semibold text-green-800">
                    Data Tempat PKL
                </h4>

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <p class="font-medium text-green-800">
                            Nama instansi wajib ditulis lengkap, tidak menyertakan alamat.
                        </p>
                        <input
                            type="text"
                            name="nama_tempat"
                            value="{{ old('nama_tempat') }}"
                            required
                            autocomplete="off"
                            class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500"
                        >
                        <div id="warningTempat" class="hidden mt-2 text-sm text-amber-600"></div>
                    </div>

                    <div>
                        <p class="font-medium text-green-800">
                            Jenis instansi
                        </p>
                        <select
                            name="jenis_tempat"
                            required
                            class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">-- Jenis Instansi --</option>
                            @foreach (['Pemerintah','Sekolah','PT','CV'] as $jenis)
                                <option value="{{ $jenis }}"
                                    @selected(old('jenis_tempat') === $jenis)>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <p class="font-medium text-green-800">
                            No HP instansi
                        </p>
                        <input
                            type="text"
                            name="no_hp"
                            pattern="^08[0-9]{7,14}$"
                            value="{{ old('no_hp') }}"
                            required
                            autocomplete="off"
                            class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500"
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-green-800">
                            Lokasi Instansi (Google Maps)
                        </label>
                        <input
                            type="url"
                            name="lokasi_maps"
                            value="{{ old('lokasi_maps') }}"
                            pattern=".*google\..*"
                            required
                            autocomplete="off"
                            class="block w-full border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500"
                        >
                        <p class="mt-1 text-xs text-gray-500">
                            Salin link dari fitur Bagikan di Google Maps.
                        </p>
                    </div>

                </div>
            </div>

            {{-- ================= DATA AKADEMIK ================= --}}
            <div>
                <div class="grid gap-4 md:grid-cols-2">

                    {{-- SEMESTER --}}
                    <div>
                        <label class="block mb-1 font-medium text-green-800">
                            Semester (Angka Romawi) *
                        </label>
                        <input
                            type="text"
                            name="semester"
                            value="{{ old('semester') }}"
                            required
                            pattern="^(I|II|III|IV|V|VI|VII|VIII|IX|X)$"
                            title="Gunakan angka romawi, contoh: V"
                            class="block w-full uppercase rounded-lg input focus:ring-green-500 focus:border-green-500"
                            style="text-transform: uppercase;"
                        >
                        <p class="mt-1 text-xs text-gray-500">
                            Gunakan angka romawi (I, II, III, IV, V, dst).
                        </p>
                    </div>

                    {{-- ALAMAT ASAL --}}
                    <div>
                        <label class="block mb-1 font-medium text-green-800">
                            Alamat Asal Mahasiswa *
                        </label>
                        <textarea
                            name="alamat_asal"
                            required
                            rows="3"
                            class="block w-full rounded-lg input focus:ring-green-500 focus:border-green-500"
                            placeholder="Contoh: Ds. Sidomulyo RT 01/RW 02, Kec. Deket, Kab. Lamongan"
                        >{{ old('alamat_asal') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ================= DOKUMEN ================= --}}
            <div>
                <h4 class="mb-3 font-semibold text-green-800">
                    Upload Dokumen Wajib
                </h4>

                <div class="p-4 mb-4 text-sm text-gray-600 border border-gray-200 rounded-lg bg-gray-50">
                    • Upload KHS dari semester 1 sampai semester terakhir. <br>
                    • Semua dokumen wajib dalam format PDF (kecuali pembayaran boleh gambar).
                </div>

                {{-- ================= KHS MULTIPLE ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        KHS Semester 1 - Terakhir *
                    </label>
                    <input type="file"
                        name="dokumen_khs[]"
                        multiple
                        required
                        accept=".pdf,.doc,.docx"
                        class="block w-full text-sm">
                </div>

                {{-- ================= PEMBAYARAN ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Bukti Pembayaran PKL *
                    </label>
                    <input type="file"
                        name="dokumen_pembayaran"
                        required
                        accept=".pdf,.jpg,.png"
                        class="block w-full text-sm">
                </div>

                {{-- ================= STUDI TOUR ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Sertifikat Studi Tour *
                    </label>
                    <input type="file"
                        name="dokumen_studi_tour"
                        required
                        accept=".pdf,.doc,.docx"
                        class="block w-full text-sm">
                </div>

                {{-- ================= FORM PKN (BARU) ================= --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Form Pengajuan PKN *
                    </label>
                    <input type="file"
                        name="dokumen_form_pkn"
                        required
                        accept=".pdf"
                        class="block w-full text-sm">
                </div>

                {{-- ================= KRS REMEDIAL (BARU) ================= --}}
                <div>
                    <label class="block mb-1 font-medium text-green-800">
                        KRS Remedial *
                    </label>
                    <input type="file"
                        name="dokumen_krs_remedial"
                        required
                        accept=".pdf"
                        class="block w-full text-sm">
                </div>
            </div>

            {{-- ================= BUTTON ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="px-4 py-2 text-sm font-medium text-green-700 transition border border-green-300 rounded-lg hover:bg-green-50">
                    Kembali
                </a>

                <button type="submit"
                    class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-1 fa-solid fa-paper-plane"></i>
                    Ajukan PKL
                </button>

            </div>
        </form>

    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {

    const inputNama = document.querySelector('input[name="nama_tempat"]');
    const warningBox = document.getElementById('warningTempat');

    let timeout = null;

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
                        `⚠️ Nama ini mirip dengan <b>${data.nama_mirip}</b>. 
                         Pastikan ini memang tempat yang berbeda, jika ya abaikan pesan ini`;
                    warningBox.classList.remove('hidden');
                } else {
                    warningBox.classList.add('hidden');
                }

            });

        }, 600); // delay supaya tidak spam request

    });

});

// Auto Uppercase Semester
const semesterInput = document.querySelector('input[name="semester"]');

if (semesterInput) {
    semesterInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
}
</script>



</x-app-layout>
