<x-app-layout>

    ```
    <x-slot name="title">
        Edit Logbook - MagangApp
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-3xl">

        {{-- ================= NOTIFIKASI ERROR ================= --}}
        @if (session('error'))
            <div class="p-4 text-red-800 bg-red-50 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 text-red-800 bg-red-50 rounded-lg border border-red-200">
                <ul class="text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================= FORM EDIT LOGBOOK ================= --}}
        <div class="p-6 bg-white rounded-xl border border-green-100 shadow">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-green-700">
                    Edit Logbook
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Perbarui tanggal atau isi kegiatan logbook Anda.
                </p>
            </div>


            <form action="{{ route('mahasiswa.logbook.update', $logbook->id) }}" method="POST" class="space-y-5">

                @csrf
                @method('PUT')


                {{-- ================= TANGGAL ================= --}}
                <div>

                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Tanggal
                    </label>

                    @php
                        $tz = 'Asia/Jakarta';

                        $today = \Carbon\Carbon::now($tz)->toDateString();

                        $pklStart = \Carbon\Carbon::parse($logbook->pkl->tgl_mulai)->toDateString();

                        $pklEnd = $logbook->pkl->tgl_selesai
                            ? \Carbon\Carbon::parse($logbook->pkl->tgl_selesai)->toDateString()
                            : $today;

                        // Tanggal maksimal adalah hari ini
                        // atau tanggal selesai PKL,
                        // mana yang lebih kecil.
                        $maxDate = \Carbon\Carbon::createFromFormat('Y-m-d', $pklEnd, $tz)->lt(
                            \Carbon\Carbon::createFromFormat('Y-m-d', $today, $tz),
                        )
                            ? $pklEnd
                            : $today;
                    @endphp

                    <input type="date" name="tgl" min="{{ $pklStart }}" max="{{ $maxDate }}"
                        value="{{ old('tgl', $logbook->tgl->format('Y-m-d')) }}"
                        class="px-3 py-2 w-full rounded-lg border focus:ring focus:ring-green-200" required>

                    @error('tgl')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================= KEGIATAN ================= --}}
                <div>

                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Kegiatan
                    </label>

                    <textarea name="kegiatan" rows="5" class="px-3 py-2 w-full rounded-lg border focus:ring focus:ring-green-200"
                        required>{{ old('kegiatan', $logbook->kegiatan) }}</textarea>

                    @error('kegiatan')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ================= INFORMASI DOKUMENTASI ================= --}}
                @if ($logbook->link_dokumentasi)
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
                                    Link dokumentasi tidak dapat diubah melalui
                                    halaman edit logbook.
                                </p>

                                <a href="{{ route('mahasiswa.logbook.dokumentasi.edit') }}"
                                    class="inline-flex gap-2 items-center px-3 py-2 mt-3 text-sm font-medium text-blue-700 bg-white rounded-lg border border-blue-200 hover:bg-blue-100">
                                    <i class="fa-solid fa-link"></i>
                                    Perbaiki Link Dokumentasi
                                </a>

                            </div>

                        </div>

                    </div>
                @endif


                {{-- ================= TOMBOL ================= --}}
                <div class="flex gap-3 justify-end pt-4 border-t border-green-100">

                    <a href="{{ route('mahasiswa.logbook.index') }}"
                        class="px-4 py-2 text-sm text-gray-600 rounded-lg border hover:bg-gray-100">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Update Logbook
                    </button>

                </div>

            </form>

        </div>

    </div>
    ```

</x-app-layout>
