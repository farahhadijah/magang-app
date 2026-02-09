
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            <i class="fa-solid fa-book-open"></i> Review Logbook Mahasiswa
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash --}}
        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-100 border border-green-200 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 text-red-800 bg-red-100 border border-red-200 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="overflow-x-auto bg-white border border-green-200 shadow rounded-xl">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Mahasiswa</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logbooks as $log)
                        <tr class="border-b hover:bg-green-50">
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($log->tgl)->format('d-m-Y') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $log->pkl->pengajuanPkl->mahasiswa->nama }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $log->kegiatan }}
                            </td>
                            <td id="status-{{ $log->id }}">
                                @if ($log->status_approve === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Perlu Revisi</span>
                                @elseif ($log->status_approve === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <button onclick="openModal({{ $log->id }})"
                                    class="font-medium text-green-700 hover:text-green-900">
                                    <i class="fa-solid fa-eye"></i> Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada logbook mahasiswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL (di luar table) --}}
        @foreach ($logbooks as $log)
            <div id="modal-{{ $log->id }}"
                class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
                <div class="w-full max-w-md p-6 border shadow-lg bg-green-50 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-green-800">
                        Review Logbook
                    </h3>

                    <form onsubmit="submitReview(event, {{ $log->id }})">
                        @csrf
                        <div class="mb-4">
                            <label class="text-sm font-medium">Catatan Dosen</label>
                            <textarea id="catatan-{{ $log->id }}" name="catatan"
                                class="w-full p-2 border rounded-lg"
                                rows="3"
                                placeholder="Isi jika perlu perbaikan...">{{ $log->catatan }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="text-sm font-medium">Status</label>
                            <select id="status-select-{{ $log->id }}" name="status"
                                class="w-full p-2 border rounded-lg">
                                <option value="approved" {{ $log->status_approve === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="revisi" {{ $log->status_approve === 'pending' ? 'selected' : '' }}>Perlu Revisi</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button"
                                onclick="closeModal({{ $log->id }})"
                                class="px-4 py-2 border rounded-lg">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-white bg-green-600 rounded-lg">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach


    </div>
</x-app-layout>
