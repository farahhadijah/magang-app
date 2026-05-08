<x-app-layout>
<x-slot name="title">
    Pengantar PKL - MagangApp
</x-slot>

<div class="p-6">

    <h1 class="mb-6 text-2xl font-bold">
        Surat Pengantar PKL
    </h1>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="px-4 py-3 mb-4 text-green-800 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="px-4 py-3 mb-4 text-red-800 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if(session('wa_list'))
        <div class="p-4 mb-4 border border-green-200 rounded-xl bg-green-50">
            <h2 class="mb-3 text-lg font-semibold text-green-700">
                Kirim WhatsApp ke Mahasiswa
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach(session('wa_list') as $wa)
                    <a href="{{ $wa['link'] }}"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>
                            {{ $wa['nama'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FORM BULK (HARUS DI LUAR LOOP) --}}
    <form method="POST" action="{{ route('staff.surat.bulk') }}">
        @csrf

        {{-- ACTION BAR --}}
        <div class="flex flex-wrap items-center gap-3 mb-4">

            {{-- CETAK --}}
            <button type="button"
                onclick="openBulkModal()"
                class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">

                <i class="fa-solid fa-print"></i>
                Cetak Terpilih

            </button>

            {{-- VALIDASI --}}
            <button type="button"
                onclick="submitBulkValidasi()"
                class="flex items-center gap-2 px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">

                <i class="fa-solid fa-check"></i>
                Validasi Terpilih

            </button>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto bg-white border shadow rounded-xl">
            <table class="w-full text-sm">
                <thead class="text-left bg-gray-100">
                    <tr>
                        <th class="p-3 text-center">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">NIM</th>
                        <th class="p-3">Tempat PKL</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Terakhir Diproses</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $item)
                        @php
                            $status = $item->statusSuratLabel();
                        @endphp

                        <tr class="border-t hover:bg-gray-50">

                            {{-- CHECKBOX --}}
                            <td class="p-3 text-center">
                                <input type="checkbox"
                                    name="ids[]"
                                    value="{{ $item->id }}"
                                    class="checkbox-item"
                                    {{ $item->status_surat === 'siap_diambil' ? 'disabled' : '' }}>
                            </td>

                            {{-- DATA --}}
                            <td class="p-3">{{ $item->mahasiswa->nama }}</td>
                            <td class="p-3">{{ $item->mahasiswa->nim }}</td>
                            <td class="p-3">
                                {{ $item->tempatPkl->nama_tempat ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>

                            {{-- UPDATED AT --}}
                            <td class="p-3 text-xs text-gray-600">
                                {{ $item->updated_at?->translatedFormat('d M Y') }}
                                <br>
                                <span class="text-gray-400">
                                    {{ $item->updated_at?->format('H:i') }} WIB
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">
                                    {{-- PREVIEW --}}
                                    <button type="button"
                                        onclick="openModal({{ $item->id }})"
                                        class="flex items-center gap-2 px-2 py-1 text-xs text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Preview</span>
                                    </button>
                                    {{-- VALIDASI --}}
                                    @if($item->bisaDivalidasi())
                                        <button type="button"
                                            onclick="submitValidasi({{ $item->id }})"
                                            class="flex items-center gap-2 px-2 py-1 text-xs text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Validasi</span>
                                        </button>
                                    @endif

                                    @if($item->status_surat === 'siap_diambil' && $item->mahasiswa->no_hp)

                                        @php
                                            $nomor = preg_replace('/[^0-9]/', '', $item->mahasiswa->no_hp);

                                            if(substr($nomor,0,1) == '0'){
                                                $nomor = '62' . substr($nomor,1);
                                            }

                                            $pesan = urlencode(
                                                "Halo {$item->mahasiswa->nama}, surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU)."
                                            );
                                        @endphp

                                        <a href="https://wa.me/{{ $nomor }}?text={{ $pesan }}"
                                            target="_blank"
                                            class="flex items-center gap-2 px-2 py-1 text-xs text-white transition bg-green-500 rounded-lg hover:bg-green-600">

                                            <i class="fa-brands fa-whatsapp"></i>

                                            <span>WhatsApp</span>

                                        </a>

                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Tidak ada data mahasiswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $data->links() }}
        </div>

    </form>

</div>

{{-- MODAL --}}
<div id="modal" class="fixed inset-0 z-[9999] hidden bg-black/60 flex items-center justify-center">

    <div class="w-[95%] h-[95vh] bg-white rounded-xl shadow-xl flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-700">
                Preview Surat
            </h2>

            <button onclick="closeModal()" class="text-red-500 hover:text-red-700">
                <i class="text-lg fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="flex-1">
            <iframe
                id="previewFrame"
                name="previewFrame"
                class="w-full h-full border-0">
            </iframe>
        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-2 px-4 py-3 border-t bg-gray-50">

            <button onclick="printIframe()"
                class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="fa-solid fa-print"></i>
                Cetak
            </button>

            <button onclick="closeModal()"
                class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                Tutup
            </button>

        </div>

    </div>

</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.checkbox-item:not(:disabled)')
                .forEach(cb => cb.checked = this.checked);
        });
    }

    window.openModal = function(id){
        const modal = document.getElementById('modal');
        const iframe = document.getElementById('previewFrame');
        if (!modal || !iframe) return;
        modal.classList.remove('hidden');
        iframe.src = '/staff/surat-pengantar/' + id + '/preview';
    }

    window.closeModal = function(){
        const modal = document.getElementById('modal');
        if (!modal) return;
        modal.classList.add('hidden');
        const iframe = document.getElementById('previewFrame');
        if (iframe) iframe.src = '';
    }

});
    function printIframe(){
    const iframe = document.getElementById('previewFrame');
    iframe.contentWindow.print();
}
function submitValidasi(id)
{
    if(confirm('Kirim validasi surat ke mahasiswa?'))
    {
        const form = document.getElementById('formValidasi');

        form.action = '/staff/surat-pengantar/' + id + '/validasi';

        form.submit();
    }
}

function submitBulkValidasi()
{
    const checked = document.querySelectorAll('.checkbox-item:checked');

    if (checked.length === 0) {
        alert('Pilih minimal satu mahasiswa.');
        return;
    }

    if(confirm('Kirim validasi ke mahasiswa terpilih?'))
    {
        const form = document.getElementById('bulkValidasiForm');

        // reset dulu
        form.innerHTML = '@csrf';

        checked.forEach(item => {

            const input = document.createElement('input');

            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = item.value;

            form.appendChild(input);
        });

        form.submit();
    }
}

    function openBulkModal()
{
    const checked = document.querySelectorAll('.checkbox-item:checked');

    if (checked.length === 0) {
        alert('Pilih minimal satu mahasiswa.');
        return;
    }

    const modal = document.getElementById('modal');
    const form = document.getElementById('bulkPreviewForm');

    // reset isi form
    form.innerHTML = '@csrf';

    checked.forEach(item => {

        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = item.value;

        form.appendChild(input);
    });

    modal.classList.remove('hidden');

    form.submit();
}
</script>
<form id="formValidasi" method="POST" style="display:none;">
    @csrf
</form>
<form id="bulkValidasiForm" method="POST" action="{{ route('staff.surat.bulk-validasi') }}">
    @csrf
</form>
<form id="bulkPreviewForm"
    method="POST"
    action="{{ route('staff.surat.bulk-preview') }}"
    target="previewFrame"
    style="display:none;">

    @csrf
</form>
</x-app-layout>