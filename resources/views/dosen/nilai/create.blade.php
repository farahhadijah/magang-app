<x-app-layout>
    <x-slot name="title">
        Input Nilai - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-3xl sm:px-6">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-green-700 sm:text-2xl">
                Input Nilai PKL
            </h2>
            <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                Berikan penilaian akhir untuk mahasiswa
            </p>
        </div>

        {{-- INFO ALUR STATUS PKL (diringkas jadi satu baris) --}}
        <div class="flex gap-3 items-start p-3 mb-6 text-xs bg-blue-50 rounded-lg border-l-4 border-blue-500 sm:text-sm">
            <svg class="mt-0.5 w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z"
                    clip-rule="evenodd" />
            </svg>
            <div class="text-blue-900">
                <span class="font-semibold">Alur Penyelesaian:</span>
                <span class="text-blue-800">
                    Input nilai di form ini
                    <span class="mx-1 text-blue-400">→</span>
                    Status menjadi <span class="font-semibold text-orange-600">"Menunggu Approval"</span>
                    <span class="mx-1 text-blue-400">→</span>
                    Setelah di-approve Staff, status menjadi <span class="font-semibold text-green-600">"Selesai"</span>
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-green-100 shadow-sm sm:rounded-2xl">

            <form method="POST" action="{{ route('dosen.nilai.store', $pkl->id) }}" x-data="{
                nilai: '{{ old('nilai') }}'
            }"
                class="p-5 sm:p-6">
                @csrf

                {{-- PENILAIAN MITRA (diringkas) --}}
                @if ($pkl->penilaianMitra)
                    @php $nilaiMitra = $pkl->penilaianMitra; @endphp

                    <div
                        class="flex flex-col gap-3 p-4 mb-6 rounded-lg border border-blue-100 bg-blue-50/50 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <div class="flex gap-2 items-center">
                                <h3 class="text-sm font-semibold text-blue-700 sm:text-base">
                                    Penilaian Mitra
                                </h3>
                                <span
                                    class="inline-flex px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    {{ $nilaiMitra->grade }}
                                </span>
                            </div>
                            <p class="mt-0.5 text-xs text-gray-500">
                                Nilai dari pembimbing lapangan ·
                                {{ \Carbon\Carbon::parse($nilaiMitra->tgl_input)->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        <div class="flex gap-3 items-center">
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-800">
                                    {{ number_format($nilaiMitra->rata_rata, 2) }}
                                </div>
                                <div class="text-[10px] uppercase tracking-wide text-gray-400">
                                    Rata-rata
                                </div>
                            </div>

                            <button type="button" @click="nilai = '{{ $nilaiMitra->rata_rata }}'"
                                class="px-3 py-1.5 text-xs font-medium text-white whitespace-nowrap bg-blue-600 rounded-md transition-colors hover:bg-blue-700 sm:text-sm">
                                Gunakan Nilai Ini
                            </button>
                        </div>

                    </div>
                @endif

                {{-- NILAI --}}
                <div class="mb-4">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nilai Angka <span class="text-red-500">*</span>
                    </label>

                    <div class="flex gap-3 items-center">
                        <input type="number" name="nilai" min="0" max="100" step="0.01" required
                            x-model="nilai" placeholder="0 - 100"
                            class="px-3 py-2 w-full text-sm rounded-lg border border-gray-300 transition-colors focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-base">

                        {{-- Preview nilai huruf realtime --}}
                        <template x-if="nilai !== '' && !isNaN(parseFloat(nilai))">
                            <span
                                class="inline-flex justify-center items-center px-3 py-1 text-sm font-bold text-white rounded-full shrink-0"
                                :class="{
                                    'bg-green-600': parseFloat(nilai) >= 85,
                                    'bg-blue-600': parseFloat(nilai) >= 80 && parseFloat(nilai) < 85,
                                    'bg-indigo-600': parseFloat(nilai) >= 75 && parseFloat(nilai) < 80,
                                    'bg-yellow-500': parseFloat(nilai) >= 68 && parseFloat(nilai) < 75,
                                    'bg-orange-500': parseFloat(nilai) >= 60 && parseFloat(nilai) < 68,
                                    'bg-orange-600': parseFloat(nilai) >= 50 && parseFloat(nilai) < 60,
                                    'bg-red-600': parseFloat(nilai) < 50
                                }"
                                x-text="
                                    parseFloat(nilai) >= 85 ? 'A' :
                                    parseFloat(nilai) >= 80 ? 'AB' :
                                    parseFloat(nilai) >= 75 ? 'B' :
                                    parseFloat(nilai) >= 68 ? 'BC' :
                                    parseFloat(nilai) >= 60 ? 'C' :
                                    parseFloat(nilai) >= 50 ? 'D' : 'E'
                                "></span>
                        </template>
                    </div>

                    <p class="mt-1 text-xs text-gray-400">
                        Otomatis dikonversi ke nilai huruf
                    </p>

                    @error('nilai')
                        <p class="mt-1 text-xs text-red-600 sm:text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PANDUAN KONVERSI NILAI (sesuai sistem) --}}
                <details class="mb-5 bg-gray-50 rounded-lg border border-gray-200 group" open>
                    <summary class="flex justify-between items-center px-4 py-2.5 list-none cursor-pointer">
                        <span class="text-sm font-semibold text-gray-700">
                            📊 Panduan Konversi Nilai
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>

                    <div class="grid grid-cols-2 gap-2 px-4 pb-4 sm:grid-cols-7">
                        @php
                            $konversi = [
                                ['range' => '85-100', 'grade' => 'A', 'color' => 'green-600'],
                                ['range' => '80-84', 'grade' => 'AB', 'color' => 'blue-600'],
                                ['range' => '75-79', 'grade' => 'B', 'color' => 'indigo-600'],
                                ['range' => '68-74', 'grade' => 'BC', 'color' => 'yellow-500'],
                                ['range' => '60-67', 'grade' => 'C', 'color' => 'orange-500'],
                                ['range' => '50-59', 'grade' => 'D', 'color' => 'orange-600'],
                                ['range' => '0-49', 'grade' => 'E', 'color' => 'red-600'],
                            ];
                        @endphp

                        @foreach ($konversi as $k)
                            <div
                                class="flex flex-col items-center p-2 text-center bg-white rounded-lg border border-gray-200">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 mb-1 text-xs font-bold text-white rounded-full bg-{{ $k['color'] }}">
                                    {{ $k['grade'] }}
                                </span>
                                <span class="text-[11px] font-medium text-gray-700">{{ $k['range'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </details>

                {{-- KETERANGAN --}}
                <div class="mb-5">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Keterangan
                        <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                    </label>

                    <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                        class="px-3 py-2 w-full text-sm rounded-lg border border-gray-300 transition-colors focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-base">{{ old('keterangan') }}</textarea>

                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-600 sm:text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="px-6 py-2.5 w-full text-sm font-medium text-white bg-green-600 rounded-lg transition-colors hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                        Simpan &amp; Selesaikan PKL
                    </button>
                </div>

            </form>

        </div>

    </div>
</x-app-layout>
