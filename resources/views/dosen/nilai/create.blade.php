<x-app-layout>
    <x-slot name="title">
        Input Nilai - MagangApp
    </x-slot>

    <div class="max-w-4xl py-6 mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-green-700">
                Input Nilai PKL
            </h2>
            <p class="text-sm text-gray-500">
                Berikan penilaian akhir untuk mahasiswa
            </p>
        </div>

        {{-- Card Form --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-2xl">

            <form method="POST"
                  action="{{ route('dosen.nilai.store', $pkl->id) }}"
                  class="space-y-6">
                @csrf

                {{-- Nilai --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Nilai (0 - 100)
                    </label>

                    <select name="nilai"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">-- Pilih Nilai --</option>

                        @for($i = 0; $i <= 100; $i+=5)
                            <option value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor
                    </select>

                    @error('nilai')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Keterangan (Opsional)
                    </label>

                    <textarea name="keterangan"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>

                    @error('keterangan')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                        Simpan Nilai & Selesaikan PKL
                    </button>
                </div>

            </form>

        </div>

    </div>
</x-app-layout>