<x-app-layout>

    <x-slot name="title">
        Create Logbook - MagangApp
    </x-slot>

    <div class="max-w-4xl py-6 mx-auto">


        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('mahasiswa.logbook.store') }}"
              class="p-6 space-y-6 bg-white border border-green-100 shadow rounded-xl">

            @csrf



            {{-- ================= TANGGAL ================= --}}
            <div>

                <label class="block mb-1 text-sm font-medium text-green-800">
                    Tanggal
                </label>

                <input type="date"
                       name="tanggal"
                       required
                       class="w-full px-3 py-2 border border-green-200 rounded-lg outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">

            </div>



            {{-- ================= KEGIATAN ================= --}}
            <div>

                <label class="block mb-1 text-sm font-medium text-green-800">
                    Kegiatan
                </label>

                <textarea name="kegiatan"
                          rows="4"
                          required
                          placeholder="Deskripsikan kegiatan hari ini..."
                          class="w-full px-3 py-2 border border-green-200 rounded-lg outline-none resize-none focus:ring-2 focus:ring-green-400 focus:border-green-400"></textarea>

            </div>



            {{-- ================= KETERANGAN ================= --}}
            <div>

                <label class="block mb-1 text-sm font-medium text-green-800">
                    Keterangan (Opsional)
                </label>

                <textarea name="keterangan"
                          rows="3"
                          placeholder="Tambahan informasi jika ada..."
                          class="w-full px-3 py-2 border border-green-200 rounded-lg outline-none resize-none focus:ring-2 focus:ring-green-400 focus:border-green-400"></textarea>

            </div>



            {{-- ================= BUTTON ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-green-100">


                {{-- Batal --}}
                <a href="{{ route('mahasiswa.logbook.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 transition border border-green-300 rounded-lg hover:bg-green-50">

                    <i class="fa-solid fa-arrow-left"></i>
                    Batal

                </a>


                {{-- Simpan --}}
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">

                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan

                </button>


            </div>


        </form>

    </div>

</x-app-layout>
