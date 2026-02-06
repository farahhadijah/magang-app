<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Review Logbook Mahasiswa
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Mahasiswa --}}
        <div class="p-6 rounded-lg shadow bg-blue-50 dark:bg-gray-700">
            <p class="text-sm text-blue-700 dark:text-blue-200">
                Logbook mahasiswa: <strong>Andi Pratama</strong>
            </p>
        </div>

        {{-- Tabel Logbook --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-4 py-3">01-02-2026</td>
                        <td class="px-4 py-3">Observasi sistem</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded">
                                Menunggu
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                onclick="document.getElementById('reviewModal').classList.remove('hidden')"
                                class="text-blue-600 hover:underline">
                                Review
                            </button>
                        </td>
                    </tr>

                    {{-- foreach --}}
                </tbody>
            </table>
        </div>

        {{-- Modal Review --}}
        <div id="reviewModal"
             class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 bg-white rounded-lg dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold">
                    Review Logbook
                </h3>

                <form method="POST" action="#">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status"
                                class="w-full border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700">
                            <option value="disetujui">Disetujui</option>
                            <option value="revisi">Perlu Revisi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Catatan</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                onclick="document.getElementById('reviewModal').classList.add('hidden')"
                                class="px-4 py-2 border rounded">
                            Batal
                        </button>

                        <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
