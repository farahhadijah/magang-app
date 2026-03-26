<x-app-layout>
    <x-slot name="title">
        Laporan Akhir - MagangApp
    </x-slot>
    <div class="max-w-4xl py-10 mx-auto">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-green-900">
                    Upload Laporan Akhir
                </h2>
                <p class="mt-1 text-sm text-green-700">
                    Unggah file laporan akhir PKL dalam format PDF
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="flex items-start gap-3 p-4 mb-6 border-l-4 border-green-600 rounded-lg bg-green-50">
            <i class="w-5 mt-1 text-green-700 fa-solid fa-circle-info"></i>
            <p class="text-sm text-green-800">
                Pastikan seluruh logbook sudah lengkap dan disetujui dosen sebelum mengunggah laporan akhir.
            </p>
        </div>

        <!-- Form Card -->
        <div class="p-8 bg-white border border-green-100 shadow-lg rounded-2xl">

            <form method="POST"
                  action="{{ route('mahasiswa.laporan.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                <!-- File Input -->
                <div>
                    <label class="flex items-center gap-2 mb-2 text-sm font-semibold text-green-800">
                        <i class="w-4 text-red-500 fa-solid fa-file-pdf"></i>
                        Pilih File Laporan (PDF)
                    </label>

                    <input type="file"
                           name="file"
                           required
                           accept="application/pdf"
                           class="block w-full px-4 py-3 text-sm border border-green-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('file')
                        <p class="flex items-center gap-2 mt-2 text-sm text-red-600">
                            <i class="w-4 fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 font-semibold text-white transition bg-green-600 shadow rounded-xl hover:bg-green-700 hover:shadow-lg">
                        <i class="w-5 fa-solid fa-upload"></i>
                        Upload Laporan
                    </button>
                </div>

            </form>

        </div>

    </div>
</x-app-layout>
