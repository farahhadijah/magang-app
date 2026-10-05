<x-app-layout>
    <x-slot name="title">
        Approval Nilai PKL - MagangApp
    </x-slot>

    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl">
                Approval Nilai PKL
            </h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base">
                Daftar mahasiswa yang sudah memiliki nilai dari dosen pembimbing dan menunggu approval
            </p>
        </div>

        {{-- Alert Messages --}}
        @if ($errors->any())
            <div class="p-4 mb-4 bg-red-50 rounded-lg border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="flex gap-3 items-center p-4 mb-4 bg-green-50 rounded-lg border border-green-200">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm text-green-700 sm:text-base">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex gap-3 items-center p-4 mb-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm text-yellow-700 sm:text-base">{{ session('warning') }}</span>
            </div>
        @endif

        {{-- Filter & Bulk Action Section --}}
        <div class="p-4 mb-6 bg-white rounded-lg shadow">
            {{-- Bulk Action Bar --}}
            <div id="bulkActionBar" class="hidden p-3 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4 sm:justify-between sm:items-center">
                    <span class="text-sm text-gray-700">
                        <span id="selectedCount">0</span> item dipilih
                    </span>
                    <div class="flex gap-2">
                        <button type="button" onclick="clearSelection()"
                            class="flex-1 px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded transition hover:bg-gray-300 sm:flex-none">
                            Batal
                        </button>
                        <button type="button" onclick="showBulkApproveModal()"
                            class="flex-1 px-4 py-2 text-sm text-white bg-green-600 rounded transition hover:bg-green-700 sm:flex-none">
                            Approve Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        @if ($pkls->count() > 0)
            <form id="bulkActionForm">
                {{-- ================= DESKTOP TABLE ================= --}}
                <div class="hidden overflow-hidden bg-white rounded-lg shadow md:block">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"
                                            class="rounded">
                                    </th>
                                    <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">No</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">NIM</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Nama Mahasiswa
                                    </th>
                                    <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Program Studi
                                    </th>
                                    <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Nilai Dosen
                                    </th>
                                    <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Nilai Mitra
                                    </th>
                                    <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($pkls as $pkl)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox" name="selected_pkls[]" value="{{ $pkl->id }}"
                                                class="rounded checkbox-item" onchange="updateBulkBar()">
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $pkls->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                            {{ $pkl->pengajuanPkl->mahasiswa->nim ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $pkl->pengajuanPkl->mahasiswa->prodi->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex justify-center items-center px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                                                {{ $pkl->nilaiPkl->nilai_angka ?? '-' }}
                                                ({{ $pkl->nilaiPkl->nilai_huruf ?? '-' }})
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($pkl->penilaianMitra)
                                                <span
                                                    class="inline-flex justify-center items-center px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                                    {{ number_format($pkl->penilaianMitra->rata_rata, 2) }}
                                                    ({{ $pkl->penilaianMitra->grade }})
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">Belum ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button"
                                                class="px-3 py-2 text-xs text-white bg-green-600 rounded transition hover:bg-green-700"
                                                onclick="showApproveModal({{ $pkl->id }})">
                                                Approve
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $pkls->links() }}
                    </div>
                </div>

                {{-- ================= MOBILE CARD LIST ================= --}}
                {{-- ================= MOBILE CARD LIST (COMPACT) ================= --}}
                <div class="space-y-2 md:hidden">
                    {{-- Select All Mobile --}}
                    <div class="flex justify-between items-center px-3 py-2 bg-white rounded-lg shadow-sm">
                        <label class="flex gap-2 items-center text-xs font-medium text-gray-700">
                            <input type="checkbox" id="selectAllMobile" onchange="toggleSelectAllMobile()"
                                class="w-4 h-4 rounded">
                            Pilih Semua
                        </label>
                        <span class="text-[11px] text-gray-500">
                            {{ $pkls->total() }} data
                        </span>
                    </div>

                    @foreach ($pkls as $pkl)
                        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                            <div class="flex gap-2 items-start p-3">
                                {{-- Checkbox --}}
                                <input type="checkbox" name="selected_pkls[]" value="{{ $pkl->id }}"
                                    class="mt-0.5 w-4 h-4 rounded checkbox-item" onchange="updateBulkBar()">

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Row 1: Nama + No + Tombol Approve --}}
                                    <div class="flex gap-2 justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold leading-tight text-gray-900 truncate">
                                                {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                                            </h3>
                                            <p class="mt-0.5 text-[11px] text-gray-500 truncate">
                                                {{ $pkl->pengajuanPkl->mahasiswa->nim ?? '-' }}
                                                · {{ $pkl->pengajuanPkl->mahasiswa->prodi->nama ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="flex flex-shrink-0 gap-1.5 items-center">
                                            <span class="text-[10px] text-gray-400">
                                                #{{ $pkls->firstItem() + $loop->index }}
                                            </span>
                                            <button type="button"
                                                class="inline-flex justify-center items-center w-8 h-8 text-white bg-green-600 rounded-md transition hover:bg-green-700 active:bg-green-800"
                                                onclick="showApproveModal({{ $pkl->id }})"
                                                title="Approve Nilai">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Row 2: Grid nilai --}}
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <div class="px-2 py-1.5 bg-blue-50 rounded-md">
                                            <p class="text-[10px] font-medium text-blue-600 leading-none">Nilai Dosen
                                            </p>
                                            <p class="mt-1 text-xs font-semibold leading-none text-blue-700">
                                                {{ $pkl->nilaiPkl->nilai_angka ?? '-' }}
                                                <span
                                                    class="text-[10px] font-normal">({{ $pkl->nilaiPkl->nilai_huruf ?? '-' }})</span>
                                            </p>
                                        </div>
                                        <div class="px-2 py-1.5 bg-green-50 rounded-md">
                                            <p class="text-[10px] font-medium text-green-600 leading-none">Nilai Mitra
                                            </p>
                                            <p class="mt-1 text-xs font-semibold leading-none text-green-700">
                                                @if ($pkl->penilaianMitra)
                                                    {{ number_format($pkl->penilaianMitra->rata_rata, 2) }}
                                                    <span
                                                        class="text-[10px] font-normal">({{ $pkl->penilaianMitra->grade }})</span>
                                                @else
                                                    <span class="text-[10px] font-normal text-gray-400">Belum
                                                        ada</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Pagination Mobile --}}
                    <div class="p-3 bg-white rounded-lg shadow-sm">
                        {{ $pkls->links() }}
                    </div>
                </div>
            </form>
        @else
            <div class="p-8 text-center bg-white rounded-lg shadow sm:p-12">
                <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-base text-gray-500 sm:text-lg">Tidak ada nilai yang menunggu approval</p>
            </div>
        @endif

    </div>

    {{-- Modal Approve Single --}}
    <div id="approveModal"
        class="flex hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Approve Nilai</h3>
            </div>
            <div class="px-6 py-4">
                <p class="mb-4 text-sm text-gray-600 sm:text-base">Apakah Anda yakin ingin approve nilai ini? Status
                    PKL mahasiswa akan
                    berubah
                    menjadi <span class="font-semibold">SELESAI</span>.</p>
            </div>
            <div class="flex gap-3 justify-end px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeApproveModal()"
                    class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded transition hover:bg-gray-400 sm:text-base">
                    Batal
                </button>
                <form id="approveForm" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded transition hover:bg-green-700 sm:text-base">
                        Ya, Approve
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Bulk Approve --}}
    <div id="bulkApproveModal"
        class="flex hidden fixed inset-0 z-50 justify-center items-center p-4 bg-black bg-opacity-50">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Approve Nilai Terpilih</h3>
            </div>
            <div class="px-6 py-4">
                <p class="mb-4 text-sm text-gray-600 sm:text-base">Apakah Anda yakin ingin approve <span
                        id="bulkCount" class="font-semibold">0</span> nilai? Status PKL mahasiswa akan berubah
                    menjadi <span class="font-semibold">SELESAI</span>.</p>
            </div>
            <div class="flex gap-3 justify-end px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeBulkApproveModal()"
                    class="px-4 py-2 text-sm text-gray-700 bg-gray-300 rounded transition hover:bg-gray-400 sm:text-base">
                    Batal
                </button>
                <form id="bulkApproveForm" method="POST" action="{{ route('staff.nilai.bulk-approve') }}"
                    class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded transition hover:bg-green-700 sm:text-base">
                        Ya, Approve Semua
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            /*
                    |--------------------------------------------------------------------------
                    | Ambil ID PKL yang dipilih secara unik
                    |--------------------------------------------------------------------------
                    |
                    | Karena desktop dan mobile mempunyai checkbox masing-masing,
                    | satu PKL bisa mempunyai 2 checkbox di DOM.
                    | Kita gunakan Set agar ID tidak pernah dihitung dua kali.
                    |
                    */
            function getSelectedPklIds() {
                const selected = new Set();

                document.querySelectorAll('.checkbox-item:checked').forEach(function(checkbox) {
                    selected.add(checkbox.value);
                });

                return Array.from(selected);
            }


            /*
            |--------------------------------------------------------------------------
            | Select All Desktop
            |--------------------------------------------------------------------------
            */
            function toggleSelectAll() {
                const selectAll = document.getElementById('selectAll');

                if (!selectAll) {
                    return;
                }

                const checked = selectAll.checked;

                document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                    checkbox.checked = checked;
                });

                const selectAllMobile = document.getElementById('selectAllMobile');

                if (selectAllMobile) {
                    selectAllMobile.checked = checked;
                }

                updateBulkBar();
            }


            /*
            |--------------------------------------------------------------------------
            | Select All Mobile
            |--------------------------------------------------------------------------
            */
            function toggleSelectAllMobile() {
                const selectAllMobile = document.getElementById('selectAllMobile');

                if (!selectAllMobile) {
                    return;
                }

                const checked = selectAllMobile.checked;

                document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                    checkbox.checked = checked;
                });

                const selectAllDesktop = document.getElementById('selectAll');

                if (selectAllDesktop) {
                    selectAllDesktop.checked = checked;
                }

                updateBulkBar();
            }


            /*
            |--------------------------------------------------------------------------
            | Update Bulk Action Bar
            |--------------------------------------------------------------------------
            */
            function updateBulkBar() {
                const selectedIds = getSelectedPklIds();
                const selectedCount = selectedIds.length;

                const bulkBar = document.getElementById('bulkActionBar');
                const countElement = document.getElementById('selectedCount');

                if (!bulkBar || !countElement) {
                    return;
                }

                countElement.textContent = selectedCount;


                /*
                | Sinkronisasi Select All Desktop
                */
                const allCheckboxes = document.querySelectorAll('.checkbox-item');

                /*
                | Jumlah PKL unik
                */
                const totalPklIds = new Set();

                allCheckboxes.forEach(function(checkbox) {
                    totalPklIds.add(checkbox.value);
                });

                const isAllSelected =
                    totalPklIds.size > 0 &&
                    selectedIds.length === totalPklIds.size;


                const selectAllDesktop = document.getElementById('selectAll');
                const selectAllMobile = document.getElementById('selectAllMobile');

                if (selectAllDesktop) {
                    selectAllDesktop.checked = isAllSelected;
                }

                if (selectAllMobile) {
                    selectAllMobile.checked = isAllSelected;
                }


                /*
                | Tampilkan / sembunyikan bulk action bar
                */
                if (selectedCount > 0) {
                    bulkBar.classList.remove('hidden');
                } else {
                    bulkBar.classList.add('hidden');
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Clear Selection
            |--------------------------------------------------------------------------
            */
            function clearSelection() {
                document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                    checkbox.checked = false;
                });

                const selectAllDesktop = document.getElementById('selectAll');
                const selectAllMobile = document.getElementById('selectAllMobile');

                if (selectAllDesktop) {
                    selectAllDesktop.checked = false;
                }

                if (selectAllMobile) {
                    selectAllMobile.checked = false;
                }

                updateBulkBar();
            }


            /*
            |--------------------------------------------------------------------------
            | Single Approve
            |--------------------------------------------------------------------------
            */
            function showApproveModal(pklId) {
                const form = document.getElementById('approveForm');

                if (!form) {
                    return;
                }

                form.action = `/staff/nilai/${pklId}/approve`;

                const modal = document.getElementById('approveModal');

                if (modal) {
                    modal.classList.remove('hidden');
                }
            }


            function closeApproveModal() {
                const modal = document.getElementById('approveModal');

                if (modal) {
                    modal.classList.add('hidden');
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Bulk Approve Modal
            |--------------------------------------------------------------------------
            */
            function showBulkApproveModal() {

                /*
                | Ambil ID PKL secara unik
                */
                const selectedIds = getSelectedPklIds();

                if (selectedIds.length === 0) {
                    alert('Pilih minimal 1 nilai untuk di-approve');
                    return;
                }


                const bulkForm = document.getElementById('bulkApproveForm');

                if (!bulkForm) {
                    return;
                }


                /*
                | Hapus hidden input lama
                */
                bulkForm
                    .querySelectorAll('input[name="pkl_ids[]"]')
                    .forEach(function(input) {
                        input.remove();
                    });


                /*
                | Tambahkan ID PKL yang unik
                */
                selectedIds.forEach(function(pklId) {

                    const input = document.createElement('input');

                    input.type = 'hidden';
                    input.name = 'pkl_ids[]';
                    input.value = pklId;

                    bulkForm.appendChild(input);
                });


                /*
                | Tampilkan jumlah mahasiswa
                */
                const bulkCount = document.getElementById('bulkCount');

                if (bulkCount) {
                    bulkCount.textContent = selectedIds.length;
                }


                /*
                | Tampilkan modal
                */
                const modal = document.getElementById('bulkApproveModal');

                if (modal) {
                    modal.classList.remove('hidden');
                }
            }


            function closeBulkApproveModal() {
                const modal = document.getElementById('bulkApproveModal');

                if (modal) {
                    modal.classList.add('hidden');
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Klik di luar modal
            |--------------------------------------------------------------------------
            */
            document.addEventListener('DOMContentLoaded', function() {

                const approveModal = document.getElementById('approveModal');

                if (approveModal) {
                    approveModal.addEventListener('click', function(event) {

                        if (event.target === this) {
                            closeApproveModal();
                        }

                    });
                }


                const bulkApproveModal = document.getElementById('bulkApproveModal');

                if (bulkApproveModal) {
                    bulkApproveModal.addEventListener('click', function(event) {

                        if (event.target === this) {
                            closeBulkApproveModal();
                        }

                    });
                }

            });
        </script>
    @endpush
</x-app-layout>
