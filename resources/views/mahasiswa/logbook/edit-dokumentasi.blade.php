<x-app-layout>

    <x-slot name="title">
        Perbaiki Dokumentasi - MagangApp
    </x-slot>

    <div class="px-0 py-6 mx-auto space-y-6 max-w-3xl">

        {{-- ================= NOTIFIKASI ================= --}}

        @if (session('success'))
            <div class="flex gap-2 items-center p-4 text-green-800 bg-green-50 rounded-xl border border-green-200">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex gap-2 items-center p-4 text-yellow-800 bg-yellow-50 rounded-xl border border-yellow-200">
                <i class="text-yellow-600 fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 text-red-800 bg-red-50 rounded-xl border border-red-200">
                <ul class="text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================= HEADER ================= --}}

        <div>
            <h1 class="text-lg font-semibold text-slate-800">
                Dokumentasi Kegiatan PKL
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbaiki link folder Google Drive dokumentasi kegiatan PKL Anda.
            </p>
        </div>


        {{-- ================= INFORMASI ================= --}}

        <div class="p-5 bg-blue-50 rounded-xl border border-blue-200">

            <div class="flex gap-3 items-start">

                <div
                    class="flex flex-shrink-0 justify-center items-center w-10 h-10 text-blue-600 bg-blue-100 rounded-lg">
                    <i class="text-lg fa-brands fa-google-drive"></i>
                </div>

                <div>
                    <h2 class="font-semibold text-blue-900">
                        Folder Dokumentasi Google Drive
                    </h2>

                    <p class="mt-1 text-sm leading-relaxed text-blue-800">
                        Folder ini digunakan untuk menyimpan seluruh dokumentasi
                        kegiatan PKL selama periode pelaksanaan.
                    </p>

                    <p class="mt-2 text-xs leading-relaxed text-blue-700">
                        Perubahan link di halaman ini hanya mengubah alamat
                        dokumentasi. Status persetujuan dan isi logbook tidak akan berubah.
                    </p>
                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}

        <div class="p-6 bg-white rounded-xl border border-green-100 shadow">

            <form method="POST" action="{{ route('mahasiswa.logbook.dokumentasi.update') }}" class="space-y-6">

                @csrf
                @method('PUT')


                {{-- ================= LINK SAAT INI ================= --}}

                <div>

                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Link Folder Google Drive Saat Ini
                    </label>

                    <div class="flex gap-2 items-center">

                        <div
                            class="flex flex-shrink-0 justify-center items-center w-10 h-10 text-blue-600 bg-blue-100 rounded-lg">
                            <i class="fa-brands fa-google-drive"></i>
                        </div>

                        <a href="{{ $logbookDokumentasi->link_dokumentasi }}" target="_blank" rel="noopener noreferrer"
                            class="text-sm text-blue-600 break-all hover:underline">

                            {{ $logbookDokumentasi->link_dokumentasi }}

                        </a>

                    </div>

                </div>


                {{-- ================= LINK BARU ================= --}}

                <div>

                    <label for="link_dokumentasi" class="block mb-1 text-sm font-medium text-gray-700">

                        Link Folder Google Drive Baru
                        <span class="text-red-600">*</span>

                    </label>

                    <input type="url" id="link_dokumentasi" name="link_dokumentasi"
                        value="{{ old('link_dokumentasi', $logbookDokumentasi->link_dokumentasi) }}"
                        placeholder="https://drive.google.com/drive/folders/..." required
                        class="px-3 py-2 w-full bg-white rounded-lg border border-blue-200 outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">

                    @error('link_dokumentasi')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-500">
                        Gunakan link folder Google Drive yang dapat diakses
                        oleh Dosen Pembimbing dan Mitra.
                    </p>

                </div>


                {{-- ================= PERINGATAN ================= --}}

                <div class="p-3 text-sm text-yellow-800 bg-yellow-50 rounded-lg border border-yellow-200">

                    <div class="flex gap-2 items-start">

                        <i class="mt-0.5 fa-solid fa-circle-info"></i>

                        <p>
                            Pastikan link yang dimasukkan benar dan folder
                            dapat diakses oleh pihak yang membutuhkan.
                        </p>

                    </div>

                </div>


                {{-- ================= BUTTON ================= --}}

                <div class="flex flex-col-reverse gap-3 pt-4 border-t border-green-100 sm:flex-row sm:justify-end">

                    <a href="{{ route('mahasiswa.logbook.index') }}"
                        class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg border border-gray-300 hover:bg-gray-100">

                        <i class="fa-solid fa-arrow-left"></i>
                        Batal

                    </a>

                    <button type="submit"
                        class="inline-flex gap-2 justify-center items-center px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">

                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
