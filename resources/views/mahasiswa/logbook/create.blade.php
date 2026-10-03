<x-app-layout>

    <x-slot name="title">
        Tambah Logbook - MagangApp
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-4xl">

        {{-- ================= FLASH MESSAGES ================= --}}
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
            <div class="p-4 mb-4 text-red-800 bg-red-50 rounded-xl border border-red-200">
                <ul class="text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================= FORM ================= --}}
        <form method="POST" action="{{ route('mahasiswa.logbook.store') }}"
            class="p-6 space-y-6 bg-white rounded-xl border border-green-100 shadow">

            @csrf


            {{-- ================= TANGGAL ================= --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-green-800">
                    Tanggal
                </label>

                @php
                    $tz = 'Asia/Jakarta';

                    $today = \Carbon\Carbon::now($tz)->toDateString();

                    $pklStart = \Carbon\Carbon::parse($pkl->tgl_mulai)->toDateString();

                    $pklEnd = $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->toDateString() : $today;

                    // Maksimal tanggal adalah hari ini atau
                    // tanggal selesai PKL, mana yang lebih kecil.
                    $maxDate = \Carbon\Carbon::createFromFormat('Y-m-d', $pklEnd, $tz)->lt(
                        \Carbon\Carbon::createFromFormat('Y-m-d', $today, $tz),
                    )
                        ? $pklEnd
                        : $today;
                @endphp

                <input type="date" name="tgl" min="{{ $pklStart }}" max="{{ $maxDate }}"
                    value="{{ old('tgl', $today) }}" required
                    class="px-3 py-2 w-full rounded-lg border border-green-200 outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">

                @error('tgl')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- ================= KEGIATAN ================= --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-green-800">
                    Kegiatan
                </label>

                <textarea name="kegiatan" rows="4" required placeholder="Deskripsikan kegiatan yang dilakukan..."
                    class="px-3 py-2 w-full rounded-lg border border-green-200 outline-none resize-none focus:ring-2 focus:ring-green-400 focus:border-green-400">{{ old('kegiatan') }}</textarea>

                @error('kegiatan')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- ========================================================= --}}
            {{-- DOKUMENTASI GOOGLE DRIVE                                  --}}
            {{-- Hanya muncul ketika membuat logbook pertama.              --}}
            {{-- ========================================================= --}}
            @if ($isFirstLogbook)
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">

                    <div class="flex gap-3 items-start">

                        <div
                            class="flex flex-shrink-0 justify-center items-center w-10 h-10 text-blue-600 bg-blue-100 rounded-lg">
                            <i class="text-lg fa-brands fa-google-drive"></i>
                        </div>

                        <div>
                            <h3 class="font-semibold text-blue-900">
                                Dokumentasi Kegiatan PKL
                            </h3>

                            <p class="mt-1 text-sm leading-relaxed text-blue-800">
                                Buat satu folder Google Drive untuk menyimpan
                                seluruh dokumentasi kegiatan PKL.
                                Folder ini digunakan selama periode PKL.
                            </p>
                        </div>

                    </div>


                    {{-- Input Link --}}
                    <div class="mt-4">

                        <label class="block mb-1 text-sm font-medium text-blue-900">
                            Link Folder Google Drive
                            <span class="text-red-600">*</span>
                        </label>

                        <input type="url" name="link_dokumentasi" value="{{ old('link_dokumentasi') }}" required
                            placeholder="https://drive.google.com/drive/folders/..."
                            class="px-3 py-2 w-full bg-white rounded-lg border border-blue-200 outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">

                        @error('link_dokumentasi')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="mt-2 text-xs text-blue-700">
                            Pastikan folder dapat diakses oleh Dosen Pembimbing
                            dan pihak terkait dalam kegiatan PKL.
                        </p>

                    </div>

                </div>
            @endif


            {{-- ================= BUTTONS ================= --}}
            <div class="flex gap-3 justify-end pt-4 border-t border-green-100">

                <a href="{{ route('mahasiswa.logbook.index') }}"
                    class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-green-700 rounded-lg border border-green-300 transition hover:bg-green-50">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex gap-2 items-center px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
