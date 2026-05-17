<x-app-layout>

    <x-slot name="title">
        Input Nilai Mahasiswa - Sibolang
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            
            <!-- Card Container -->
            <div class="overflow-hidden bg-white shadow-sm rounded-xl">
                
                <!-- Compact Header -->
                <div class="px-4 py-4 border-b border-gray-100 sm:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-lg font-bold text-gray-800 sm:text-xl">
                                Form Penilaian Mahasiswa
                            </h1>
                            <p class="mt-0.5 text-xs text-gray-500 sm:text-sm">
                                Isi nilai untuk mahasiswa PKL dibawah ini
                            </p>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <div class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-full sm:text-sm">
                                {{ $pkl->mahasiswa->user->name }}
                            </div>
                            <div class="mt-0.5 text-xs text-gray-400 text-center sm:text-left">
                                {{ $pkl->mahasiswa->nim }}
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $nilai = $pkl->penilaianMitra;
                @endphp

                <form action="{{ route('mitra.penilaian.store', $pkl->id) }}" method="POST" class="p-4 space-y-4 sm:p-6">
                    @csrf

                    @php
                        $fields = [
                            'kedisiplinan' => 'Kedisiplinan',
                            'kreativitas' => 'Kreativitas',
                            'ketekunan' => 'Ketekunan',
                            'kerjasama' => 'Kerjasama',
                            'kejujuran' => 'Kejujuran',
                            'kesopanan' => 'Kesopanan (Tata Krama)',
                            'semangat_kerja' => 'Semangat Kerja (Motivasi)',
                            'kedalaman_materi' => 'Kedalaman Materi',
                        ];
                    @endphp

                    <!-- Grid Layout for Fields - 2 columns on desktop, 1 on mobile -->
                    <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
                        @foreach($fields as $field => $label)
                            <div class="space-y-1">
                                <label class="block text-xs font-medium text-gray-600 sm:text-sm">
                                    {{ $label }}
                                    <span class="text-gray-400">(0-100)</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="number"
                                        name="{{ $field }}"
                                        min="0"
                                        max="100"
                                        step="1"
                                        value="{{ old($field, $nilai->$field ?? '') }}"
                                        class="w-full py-2 pl-3 pr-10 text-sm border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-base @error($field) border-red-300 @enderror"
                                        required
                                    >
                                    <!-- Small hint for range -->
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-xs text-gray-400">/100</span>
                                    </div>
                                </div>
                                @error($field)
                                    <div class="text-xs text-red-600 sm:text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <!-- Note & Action Buttons -->
                    <div class="pt-4 mt-2 border-t border-gray-100">
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-gray-400 sm:text-sm">
                                <span class="inline-block w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                Nilai 0-100, semakin tinggi semakin baik.
                            </p>
                            <div class="flex flex-col gap-2 sm:flex-row">
                                <a href="{{ url()->previous() }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <!-- Info Card (Optional - can be removed if not needed) -->
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-400">
                    Pastikan nilai yang diinput sudah sesuai dengan kriteria penilaian.
                </p>
            </div>

        </div>
    </div>

</x-app-layout>