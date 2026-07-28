<x-app-layout>
    <x-slot name="title">
        Input Nilai - MagangApp
    </x-slot>

    <div class="max-w-4xl px-4 py-4 mx-auto sm:px-6 sm:py-6">

        {{-- Header --}}
        <div class="mb-4 sm:mb-6">
            <h2 class="text-xl font-semibold text-green-700 sm:text-2xl">
                Input Nilai PKL
            </h2>

            <p class="text-xs text-gray-500 sm:text-sm">
                Berikan penilaian akhir untuk mahasiswa
            </p>
        </div>

        <div class="bg-white border border-green-100 shadow-sm rounded-xl sm:rounded-2xl">

            <form
                method="POST"
                action="{{ route('dosen.nilai.store', $pkl->id) }}"
                x-data="{
                    nilai: '{{ old('nilai') }}',
                    openPdf: false
                }"
                class="p-4 sm:p-6"
            >
                @csrf

                {{-- PENILAIAN MITRA --}}
                @if($pkl->penilaianMitra)
                    @php
                        $nilaiMitra = $pkl->penilaianMitra;
                    @endphp

                    <div class="pb-4 mb-5 border-b border-gray-100">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">

                                    <h3 class="text-base font-semibold text-blue-700 sm:text-lg">
                                        Penilaian Mitra
                                    </h3>

                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        {{ $nilaiMitra->grade }}
                                    </span>

                                </div>

                                <p class="text-xs text-gray-500 sm:text-sm">
                                    Nilai dari pembimbing lapangan
                                </p>
                            </div>

                            <div class="text-right">
                                <div class="text-base font-bold text-gray-800 sm:text-lg">
                                    {{ number_format($nilaiMitra->rata_rata, 2) }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($nilaiMitra->tgl_input)->translatedFormat('d M Y') }}
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-wrap gap-2 mt-4">

                            {{-- Gunakan Nilai --}}
                            <button
                                type="button"
                                @click="nilai = '{{ $nilaiMitra->rata_rata }}'"
                                class="px-3 py-1.5 text-xs sm:text-sm font-medium text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700 sm:px-4 sm:py-2"
                            >
                                Gunakan Nilai Ini
                            </button>

                            {{-- PDF preview removed per new requirement --}}

                        </div>

                    </div>
                @endif

                {{-- NILAI --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-medium text-gray-700 sm:text-base">
                        Nilai Angka
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="nilai"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                        x-model="nilai"
                        placeholder="0 - 100"
                        class="w-full px-3 py-2 text-sm transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-base sm:px-4"
                    >

                    <div class="flex items-center justify-between mt-1">

                        <p class="text-xs text-gray-400">
                            Otomatis dikonversi ke nilai huruf
                        </p>

                        <p
                            class="text-xs font-medium text-gray-500"
                            x-text="nilai ? 'Nilai: ' + parseFloat(nilai).toFixed(2) : ''"
                        ></p>

                    </div>

                    @error('nilai')
                        <p class="mt-1 text-xs text-red-600 sm:text-sm">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- KETERANGAN --}}
                <div class="mb-5">

                    <label class="block mb-1 text-sm font-medium text-gray-700 sm:text-base">
                        Keterangan
                        <span class="text-xs font-normal text-gray-400">
                            (Opsional)
                        </span>
                    </label>

                    <textarea
                        name="keterangan"
                        rows="3"
                        placeholder="Tambahkan catatan jika diperlukan..."
                        class="w-full px-3 py-2 text-sm transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-base sm:px-4"
                    >{{ old('keterangan') }}</textarea>

                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-600 sm:text-sm">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- SUBMIT --}}
                <div class="pt-3 border-t border-gray-100">

                    <button
                        type="submit"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto sm:px-6"
                    >
                        Simpan & Selesaikan PKL
                    </button>

                </div>

                {{-- PDF preview removed per new requirement --}}

            </form>

        </div>

    </div>
</x-app-layout>