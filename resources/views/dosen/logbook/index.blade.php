<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            <i class="fa-solid fa-book-open"></i> Review Logbook Mahasiswa
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        {{-- Flash Message --}}
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
            <table class="w-full text-sm">
                <thead class="text-green-900 bg-green-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Mahasiswa</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($logbooks as $log)
                        <tr class="hover:bg-green-50">
                            <td class="px-4 py-3">
                                {{ $log->tgl->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $log->pkl->pengajuanPkl->mahasiswa->nama }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $log->kegiatan }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3" id="status-{{ $log->id }}">
                                @if ($log->status_approve === 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Disetujui
                                    </span>
                                @elseif ($log->status_approve === 'revisi')
                                    <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3">
                                @if ($log->status_approve === 'pending')
                                    <button type="button"
                                        onclick="openModal({{ $log->id }})"
                                        class="font-medium text-green-700 hover:text-green-900">
                                        <i class="fa-solid fa-eye"></i> Review
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">Terkunci</span>
                                @endif
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

        {{-- MODALS --}}
        @foreach ($logbooks as $log)
            @if ($log->status_approve === 'pending')
                <div id="modal-{{ $log->id }}"
                    class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">

                    <div class="w-full max-w-md p-6 bg-white border shadow-lg rounded-xl">

                        <h3 class="mb-4 text-lg font-semibold text-green-800">
                            Review Logbook
                        </h3>

                        {{-- Info Kegiatan --}}
                        <div class="mb-4 text-sm">
                            <strong>Kegiatan:</strong>
                            <div class="p-3 mt-1 border rounded-lg bg-gray-50">
                                {{ $log->kegiatan }}
                            </div>
                        </div>

                        {{-- FORM AJAX --}}
                        <form onsubmit="submitReview(event, {{ $log->id }})">
                            @csrf

                            {{-- Catatan --}}
                            <div class="mb-4" id="catatan-wrapper-{{ $log->id }}">
                                <label class="block mb-1 text-sm font-medium">
                                    Catatan Dosen
                                </label>
                                <textarea
                                    id="catatan-{{ $log->id }}"
                                    rows="3"
                                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400"
                                    placeholder="Isi jika perlu perbaikan...">{{ $log->catatan }}</textarea>
                                <p id="catatan-error-{{ $log->id }}" class="hidden mt-2 text-sm text-red-600"></p>
                            </div>

                            {{-- Status --}}
                            <div class="mb-4">
                                <label class="block mb-1 text-sm font-medium">
                                    Status
                                </label>
                                <select
                                    id="status-select-{{ $log->id }}"
                                    onchange="toggleCatatan({{ $log->id }})"
                                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                                    <option value="approved" {{ empty($log->catatan) ? 'selected' : '' }}>Disetujui</option>
                                    <option value="revisi" {{ !empty($log->catatan) ? 'selected' : '' }}>Perlu Revisi</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="closeModal({{ $log->id }})"
                                    class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                                    Batal
                                </button>

                                <button type="button"
                                    onclick="submitReview(event, {{ $log->id }})"
                                    class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Simpan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            @endif
        @endforeach

    </div>
</x-app-layout>
