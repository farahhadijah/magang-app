<x-app-layout>
    <x-slot name="header">
        <h2 class="flex items-center gap-2 text-2xl font-bold text-green-900">
            <i class="fa-solid fa-book-open"></i> Review Logbook Mahasiswa
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash message --}}
        @if (session('success'))
            <div class="flex items-center gap-2 p-4 text-green-800 bg-green-100 border border-green-200 shadow rounded-xl">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Info Mahasiswa --}}
        <div class="flex items-center gap-2 p-6 transition border border-green-200 shadow-lg rounded-xl bg-green-50 hover:shadow-xl">
            <i class="fa-solid fa-user"></i>
            <p class="text-sm text-green-800">
                Logbook mahasiswa: <strong>Andi Pratama</strong>
            </p>
        </div>

        {{-- Tabel Logbook --}}
        <div class="overflow-x-auto transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-xl">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Kegiatan</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Status</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- dummy data --}}
                    <tr class="transition border-b hover:bg-green-50">
                        <td class="px-4 py-3">01-02-2026</td>
                        <td class="px-4 py-3">Observasi sistem</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                <i class="fa-solid fa-spinner fa-spin"></i> Menunggu
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                onclick="document.getElementById('reviewModal').classList.remove('hidden')"
                                class="flex items-center gap-1 font-medium text-green-700 transition hover:text-green-900">
                                <i class="fa-solid fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    {{-- nanti foreach --}}
                </tbody>
            </table>
        </div>

        {{-- Modal Review --}}
        <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 transition border border-green-200 shadow-lg bg-green-50 rounded-xl hover:shadow-xl">
                <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-green-800">
                    <i class="fa-solid fa-pencil"></i> Review Logbook
                </h3>

                <form method="POST" action="#">
                    @csrf

                    <div class="flex flex-col gap-1 mb-4">
                        <label class="block text-sm font-medium text-green-800">Status</label>
                        <select name="status" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                            <option value="disetujui">Disetujui</option>
                            <option value="revisi">Perlu Revisi</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1 mb-4">
                        <label class="block text-sm font-medium text-green-800">Catatan</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                onclick="document.getElementById('reviewModal').classList.add('hidden')"
                                class="px-4 py-2 transition border rounded-lg hover:bg-green-100">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </button>

                        <button type="submit"
                                class="flex items-center gap-1 px-4 py-2 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
