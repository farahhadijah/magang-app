<x-app-layout>

    <x-slot name="title">
        Laporan Akhir - MagangApp
    </x-slot>

    <div class="py-10 mx-auto max-w-4xl">

        {{-- Header --}}
        <div class="flex gap-3 items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-green-900">
                    Upload Laporan Akhir
                </h2>

                <p class="mt-1 text-sm text-green-700">
                    Unggah file laporan akhir PKL dalam format PDF
                </p>
            </div>
        </div>


        {{-- Info Box --}}
        <div class="flex gap-3 items-start p-4 mb-6 bg-green-50 rounded-lg border-l-4 border-green-600">
            <i class="mt-1 w-5 text-green-700 fa-solid fa-circle-info"></i>

            <p class="text-sm text-green-800">
                Pastikan seluruh logbook sudah lengkap dan disetujui dosen
                sebelum mengunggah laporan akhir.
            </p>
        </div>


        {{-- Form Card --}}
        <div class="p-8 bg-white rounded-2xl border border-green-100 shadow-lg">

            <form method="POST" action="{{ route('mahasiswa.laporan.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- File Input --}}
                <div>

                    <label class="flex gap-2 items-center mb-2 text-sm font-semibold text-green-800">
                        <i class="w-4 text-red-500 fa-solid fa-file-pdf"></i>
                        Pilih File Laporan (PDF)
                    </label>

                    <input type="file" name="file" required accept=".pdf,application/pdf"
                        class="block px-4 py-3 w-full text-sm rounded-xl border border-green-200 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    <p class="mt-2 text-xs text-gray-500">
                        Format yang diperbolehkan: PDF. Maksimal ukuran file 10 MB.
                    </p>

                    @error('file')
                        <p class="flex gap-2 items-center mt-2 text-sm text-red-600">
                            <i class="w-4 fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Button --}}
                <div class="pt-4">

                    <button type="submit"
                        class="inline-flex gap-2 items-center px-6 py-3 font-semibold text-white bg-green-600 rounded-xl shadow transition hover:bg-green-700 hover:shadow-lg">
                        <i class="w-5 fa-solid fa-upload"></i>
                        Upload Laporan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
