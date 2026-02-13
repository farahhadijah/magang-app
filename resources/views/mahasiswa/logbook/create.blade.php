<x-app-layout>
    <x-slot name="title">
        Create Logbook - MagangApp
    </x-slot>

    <div class="max-w-4xl py-6 mx-auto space-y-6">

        {{-- ================= FLASH MESSAGES ================= --}}
        @if(session('success'))
            <div class="flex items-center gap-2 p-4 text-green-800 border border-green-200 rounded-xl bg-green-50">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="flex items-center gap-2 p-4 text-yellow-800 border border-yellow-200 rounded-xl bg-yellow-50">
                <i class="text-yellow-600 fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-red-800 border border-red-200 rounded-xl bg-red-50">
                <ul class="text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('mahasiswa.logbook.store') }}"
              class="p-6 space-y-6 bg-white border border-green-100 shadow rounded-xl">
            @csrf

            {{-- Tanggal --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-green-800">Tanggal</label>
                @php
                    // Use Asia/Jakarta timezone explicitly so 'today' matches local date
                    $tz = 'Asia/Jakarta';
                    $today = \Carbon\Carbon::now($tz)->toDateString();

                    // Determine max date: the earlier of PKL end and today
                    $pklEnd = \Carbon\Carbon::parse($pkl->tgl_selesai)->toDateString();
                    $maxDate = \Carbon\Carbon::createFromFormat('Y-m-d', $pklEnd, $tz)->lt(\Carbon\Carbon::createFromFormat('Y-m-d', $today, $tz))
                        ? $pklEnd
                        : $today;

                    // Determine min date: the later of PKL start and today (prevent selecting past dates)
                    $pklStart = \Carbon\Carbon::parse($pkl->tgl_mulai)->toDateString();
                    $minDate = \Carbon\Carbon::createFromFormat('Y-m-d', $pklStart, $tz)->gt(\Carbon\Carbon::createFromFormat('Y-m-d', $today, $tz))
                        ? $pklStart
                        : $today;
                @endphp
                <input type="date"
                        name="tgl"
                        min="{{ $minDate }}"
                        max="{{ $maxDate }}"
                        value="{{ old('tgl', $today) }}"
                        required
                        class="w-full px-3 py-2 border border-green-200 rounded-lg outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
                @error('tgl')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kegiatan --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-green-800">Kegiatan</label>
                <textarea name="kegiatan"
                          rows="4"
                          required
                          placeholder="Deskripsikan kegiatan hari ini..."
                          class="w-full px-3 py-2 border border-green-200 rounded-lg outline-none resize-none focus:ring-2 focus:ring-green-400 focus:border-green-400">{{ old('kegiatan') }}</textarea>
                @error('kegiatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-green-100">
                <a href="{{ route('mahasiswa.logbook.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 transition border border-green-300 rounded-lg hover:bg-green-50">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
