<x-app-layout>
    <x-slot name="title">
        Manajemen Mahasiswa - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 p-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span class="flex-1">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 p-3 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50">
                <i class="text-red-600 fa-solid fa-circle-xmark"></i>
                <span class="flex-1">{{ session('error') }}</span>
            </div>
        @endif


        {{-- ================= CARD SINKRONISASI SIAKAD ================= --}}
        <div class="p-5 border border-green-200 rounded-xl bg-gradient-to-r from-green-50 to-green-50">
            <div class="flex items-start gap-3 mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Manajemen Mahasiswa</h2>
                    <p class="text-sm text-gray-600">
                        Tarik data mahasiswa terbaru langsung dari SIAKAD berdasarkan prodi dan angkatan.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.mahasiswa.sync') }}" method="POST"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">
                @csrf

                {{-- Prodi --}}
                <div class="w-full sm:w-72">
                    <label for="sync_prodi_id" class="block mb-1 text-sm font-medium text-gray-700">
                        Prodi
                    </label>
                    <select id="sync_prodi_id" name="prodi_id" required
                        class="w-full p-2 text-sm border rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($prodiForSync as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Angkatan --}}
                <div class="w-full sm:w-48">
                    <label for="sync_angkatan" class="block mb-1 text-sm font-medium text-gray-700">
                        Angkatan
                    </label>
                    <select id="sync_angkatan" name="angkatan" required
                        class="w-full p-2 text-sm border rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Angkatan --</option>
                        @for ($tahun = now()->year; $tahun >= now()->year - 10; $tahun--)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Tombol --}}
                <button type="submit"
                    class="text-sm inline-flex items-center gap-2 px-5 py-2 font-semibold text-white bg-indigo-600 rounded-lg shadow-lg animate-pulse hover:bg-indigo-700 hover:animate-none focus:ring-4 focus:ring-indigo-300">
                    Sinkronisasi SIAKAD
                </button>

                {{-- Tambah --}}
                <a href="{{ route('admin.mahasiswa.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Mahasiswa
                </a>
            </form>
        </div>


        {{-- ================= TABEL PRODI ================= --}}
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-white bg-green-700">
                        <tr>
                            <th class="w-16 px-4 py-3 font-semibold text-center">No</th>
                            <th class="px-4 py-3 font-semibold">Nama Prodi</th>
                            <th class="w-32 px-4 py-3 font-semibold text-center">Jml Angkatan</th>
                            <th class="w-36 px-4 py-3 font-semibold text-center">Total Mahasiswa</th>
                            <th class="w-40 px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($prodi as $index => $p)
                            <tr class="transition hover:bg-green-50">
                                <td class="px-4 py-3 text-center text-gray-500">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $p->nama }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">
                                        {{ $p->jumlah_angkatan }} Angkatan
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        {{ $p->jumlah_mahasiswa }} Mhs
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.mahasiswa.angkatan', $p->id) }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white transition bg-green-600 rounded-lg shadow-sm hover:bg-green-700 hover:shadow-md">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-sm">Belum ada prodi aktif.</p>
                                        <p class="text-xs text-gray-400">
                                            Silakan tambahkan prodi terlebih dahulu di menu Manajemen Prodi.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
