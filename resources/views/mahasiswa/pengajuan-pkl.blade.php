<x-app-layout>

    <x-slot name="title">
        Pengajuan PKL - MagangApp
    </x-slot>



    <div class="max-w-5xl py-6 mx-auto space-y-6">


        {{-- ================= NOTIFIKASI ================= --}}
        @foreach (['success', 'error'] as $msg)

            @if (session($msg))

            <div class="
                flex items-center gap-2 p-4 rounded-xl border
                {{ $msg === 'success'
                    ? 'bg-green-50 border-green-200 text-green-800'
                    : 'bg-red-50 border-red-200 text-red-800'
                }}
            ">

                <i class="
                    fa-solid
                    {{ $msg === 'success'
                        ? 'fa-circle-check text-green-600'
                        : 'fa-circle-xmark text-red-600'
                    }}
                "></i>

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



        {{-- ================= INFO ================= --}}
        <div class="p-5 border border-amber-200 rounded-xl bg-amber-50">

            <h4 class="flex items-center mb-1 font-semibold text-amber-800">

                <i class="mr-2 fa-solid fa-circle-info"></i>
                Informasi

            </h4>

            <p class="text-sm text-amber-700">

                Form ini digunakan untuk
                <b>mengajukan permohonan PKL</b>.
                Pengajuan akan diverifikasi oleh Staff TU sebelum disetujui secara akademik.

            </p>

        </div>



        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('mahasiswa.pengajuan.store') }}"
              enctype="multipart/form-data"
              class="p-6 space-y-6 bg-white border border-green-100 shadow rounded-xl">

            @csrf



            {{-- ================= DATA TEMPAT ================= --}}
            <div>

                <h4 class="mb-4 text-lg font-semibold text-green-800">
                    Data Tempat PKL
                </h4>

                <div class="grid gap-4 md:grid-cols-2">

                    <input
                        type="text"
                        name="nama_tempat"
                        placeholder="Nama Instansi / Perusahaan"
                        value="{{ old('nama_tempat') }}"
                        required
                        class="input focus:ring-green-500 focus:border-green-500"
                    >


                    <select
                        name="jenis_tempat"
                        required
                        class="input focus:ring-green-500 focus:border-green-500"
                    >

                        <option value="">-- Jenis Instansi --</option>

                        @foreach (['Pemerintah','Sekolah','PT','CV'] as $jenis)

                            <option value="{{ $jenis }}"
                                @selected(old('jenis_tempat')==$jenis)>

                                {{ $jenis }}

                            </option>

                        @endforeach

                    </select>


                    <input
                        type="text"
                        name="no_hp"
                        placeholder="No HP / Telepon Instansi"
                        value="{{ old('no_hp') }}"
                        required
                        class="input focus:ring-green-500 focus:border-green-500"
                    >


                    <textarea
                        name="lokasi_maps"
                        rows="2"
                        required
                        class="input focus:ring-green-500 focus:border-green-500"
                        placeholder="Link lokasi Google Maps"
                    >{{ old('lokasi_maps') }}</textarea>

                </div>

            </div>



            {{-- ================= DOKUMEN ================= --}}
            <div>

                <label class="block mb-1 font-medium text-green-800">

                    Surat Pengantar / Permohonan PKL
                    <span class="text-red-500">*</span>

                </label>


                <input
                    type="file"
                    name="dokumen"
                    required
                    class="block w-full text-sm text-gray-600 transition file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700"
                >


                <p class="mt-1 text-xs text-gray-500">
                    File ini akan diverifikasi oleh Staff TU (PDF/DOC).
                </p>

            </div>



            {{-- ================= BUTTON ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t">


                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="px-4 py-2 text-sm font-medium text-green-700 transition border border-green-300 rounded-lg hover:bg-green-50">

                    Kembali

                </a>


                <button
                    type="submit"
                    class="px-5 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700"
                    onclick="this.disabled=true;this.innerText='Mengirim...';this.form.submit();"
                >

                    <i class="mr-1 fa-solid fa-paper-plane"></i>
                    Ajukan PKL

                </button>


            </div>

        </form>

    </div>

</x-app-layout>
