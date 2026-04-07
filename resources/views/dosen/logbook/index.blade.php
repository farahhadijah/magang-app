<x-app-layout>
    <x-slot name="title">
        Logbook Mahasiswa Bimbingan - MagangApp
    </x-slot>

    <div class="max-w-6xl py-1 mx-auto space-y-6">

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


        {{-- TABLE CONTAINER --}}
        <div class="bg-white border border-green-200 shadow rounded-xl">

            <form method="POST" action="{{ route('dosen.logbook.bulk-approve') }}">
                @csrf

                {{-- Action Bar --}}
                <div class="flex flex-wrap items-center justify-start gap-3 p-4 border-b bg-gray-50 rounded-t-xl">
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                        Setujui yang Dipilih
                    </button>

                        @if($belumApprove > 0)
                        <div class="px-4 py-2 rounded-xl bg-amber-50">
                            <div class="flex items-center gap-3">
                                <i class="text-xl text-amber-600 fa-solid fa-triangle-exclamation"></i>

                                <div>
                                    <p class="font-semibold text-amber-800">
                                        {{ $belumApprove }} logbook belum direview
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                </div>

                {{-- Table --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead class="bg-green-100 text-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-center">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="px-4 py-3 text-center">No</th>
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

                                    {{-- Checkbox --}}
                                    <td class="px-4 py-3 text-center">
                                        @if($log->status_approve === 'pending')
                                            <input type="checkbox"
                                                name="logbook_ids[]"
                                                value="{{ $log->id }}"
                                                class="logbook-checkbox">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ ($logbooks->currentPage() - 1) * $logbooks->perPage() + $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $log->tgl->format('d-m-Y') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $log->pkl->pengajuanPkl->mahasiswa->nama }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $log->kegiatan }}
                                    </td>

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

                                    {{-- Aksi Manual --}}
                                    <td class="px-4 py-3">
                                        @if ($log->status_approve === 'pending')
                                            <button type="button"
                                                onclick="openModal({{ $log->id }})"
                                                class="text-green-700 hover:text-green-900">
                                                <i class="fa-solid fa-eye"></i> Review
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">Terkunci</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada logbook mahasiswa
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-b md:hidden">
                    {{ $logbooks->links() }}
                </div>
                <div class="flex items-center gap-2 px-4 pt-4 md:hidden">
                    <input type="checkbox" id="select-all-mobile">
                    <label for="select-all-mobile" class="text-sm text-gray-700">
                        Pilih Semua
                    </label>
                </div>
                    <div class="p-4 space-y-3 md:hidden">
                        @forelse ($logbooks as $log)
                            <div class="p-4 border rounded-xl shadow-sm bg-gray-50">

                                {{-- Header --}}
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500">
                                            {{ $log->tgl->format('d-m-Y') }}
                                        </p>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $log->pkl->pengajuanPkl->mahasiswa->nama }}
                                        </p>
                                    </div>

                                    {{-- Checkbox --}}
                                    @if($log->status_approve === 'pending')
                                        <input type="checkbox"
                                            name="logbook_ids[]"
                                            value="{{ $log->id }}"
                                            class="mt-1 logbook-checkbox">
                                    @endif
                                </div>

                                {{-- Kegiatan --}}
                                <p class="mb-2 text-sm text-gray-700 line-clamp-2">
                                    {{ $log->kegiatan }}
                                </p>

                                {{-- Status + Action --}}
                                <div class="flex items-center justify-between mt-2">

                                    {{-- Status --}}
                                    <div id="status-{{ $log->id }}">
                                        @if ($log->status_approve === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                Disetujui
                                            </span>
                                        @elseif ($log->status_approve === 'revisi')
                                            <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                                Revisi
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                                Pending
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    @if ($log->status_approve === 'pending')
                                        <button type="button"
                                            onclick="openModal({{ $log->id }})"
                                            class="text-xs text-green-700 hover:text-green-900">
                                            Review →
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">Terkunci</span>
                                    @endif

                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-center text-gray-500">
                                Belum ada logbook mahasiswa
                            </p>
                        @endforelse
                    </div>

                    <div class="p-4 border-b md:hidden">
                        {{ $logbooks->links() }}
                    </div>
                {{-- Pagination --}}
                <div class="hidden p-4 border-t md:block">
                    {{ $logbooks->links() }}
                </div>



            </form>

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

                <div class="mb-4 text-sm">
                    <strong>Kegiatan:</strong>
                    <div class="p-3 mt-1 border rounded-lg bg-gray-50">
                        {{ $log->kegiatan }}
                    </div>
                </div>

                <form onsubmit="submitReview(event, {{ $log->id }})">
                    @csrf

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium">
                            Status
                        </label>
                        <select
                            name="status"
                            id="status-select-{{ $log->id }}"
                            onchange="toggleCatatan({{ $log->id }})"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400">

                            <option value="approved">Disetujui</option>
                            <option value="revisi">Perlu Revisi</option>
                        </select>
                    </div>

                    {{-- CATATAN (default hidden) --}}
                    <div class="hidden mb-4"
                         id="catatan-wrapper-{{ $log->id }}">

                        <label class="block mb-1 text-sm font-medium">
                            Catatan Dosen
                        </label>

                        <textarea
                            name="catatan"
                            id="catatan-{{ $log->id }}"
                            rows="3"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400"
                            placeholder="Isi jika perlu perbaikan..."></textarea>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex justify-end gap-2">
                        <button type="button"
                            onclick="closeModal({{ $log->id }})"
                            class="px-4 py-2 border rounded-lg">
                            Batal
                        </button>

                        <button type="submit"
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


    <script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const selectAllMobile = document.getElementById('select-all-mobile');

    function toggleAll(checked) {
        document.querySelectorAll('.logbook-checkbox')
            .forEach(cb => cb.checked = checked);
    }

    // Desktop
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            toggleAll(this.checked);
        });
    }

    // Mobile
    if (selectAllMobile) {
        selectAllMobile.addEventListener('change', function () {
            toggleAll(this.checked);
        });
    }
});
</script>

</x-app-layout>