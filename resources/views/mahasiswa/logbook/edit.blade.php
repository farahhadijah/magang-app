<x-app-layout>
    <x-slot name="title">
        Edit Logbook - MagangApp
    </x-slot>

    <div class="max-w-3xl py-6 mx-auto space-y-6">

        {{-- Notifikasi Error --}}
        @if (session('error'))
            <div class="p-4 text-red-800 border border-red-200 rounded-lg bg-red-50">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <h2 class="mb-6 text-lg font-semibold text-green-700">
                Edit Logbook
            </h2>

            <form action="{{ route('mahasiswa.logbook.update', $logbook->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Tanggal --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Tanggal
                    </label>
                    <input type="date"
                           name="tgl"
                           value="{{ old('tgl', $logbook->tgl->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-green-200"
                           required>
                    @error('tgl')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kegiatan --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Kegiatan
                    </label>
                    <textarea name="kegiatan"
                              rows="4"
                              class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-green-200"
                              required>{{ old('kegiatan', $logbook->kegiatan) }}</textarea>
                    @error('kegiatan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('mahasiswa.logbook.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 border rounded-lg hover:bg-gray-100">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
