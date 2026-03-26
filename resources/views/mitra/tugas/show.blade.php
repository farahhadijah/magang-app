<x-app-layout>
    <x-slot name="title">
        Detail Tugas - MagangApp
    </x-slot>

    <div class="max-w-5xl px-4 py-6 mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold text-gray-800">
                Detail Tugas
            </h2>
            <a href="{{ route('mitra.tugas.index') }}"
                class="px-4 py-2 text-sm text-white bg-gray-500 rounded-lg hover:bg-gray-600 w-fit">
                Kembali
            </a>
        </div>

        {{-- INFO MAHASISWA --}}
        <div class="p-5 border border-green-200 shadow bg-green-50 rounded-xl">
            <h3 class="text-lg font-semibold text-green-900">
                {{ $tugas->pkl->mahasiswa->nama }}
            </h3>
            <p class="text-sm text-green-700">
                NIM: {{ $tugas->pkl->mahasiswa->nim }}
            </p>
        </div>

        {{-- DETAIL TUGAS --}}
        <div class="p-5 space-y-4 bg-white border border-green-200 shadow rounded-xl">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ $tugas->judul }}
            </h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">Deadline :</span>
                <span class="text-gray-800">
                    {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') : '-' }}
                </span>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-600">
                    Deskripsi
                </span>
                <div class="mt-2 text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                    {{ $tugas->deskripsi ?? '-' }}
                </div>
            </div>
            @if($tugas->file)
                <div class="p-3 mt-4 border rounded bg-gray-50">
                    <p class="mb-2 text-sm font-medium text-gray-700">
                        File Tugas dari Mitra
                    </p>

                    <a href="{{ asset('storage/'.$tugas->file) }}"
                    target="_blank"
                    class="px-3 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                        Download File
                    </a>
                </div>
            @endif
        </div>

        {{-- HASIL PENGERJAAN MAHASISWA --}}
        <div class="p-5 bg-white border border-green-200 shadow rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-green-900">
                Hasil Pengerjaan Mahasiswa
            </h3>

            @if($tugas->submit->isEmpty())
                <div class="py-10 text-sm text-center text-gray-500">
                    Mahasiswa belum mengumpulkan tugas.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($tugas->submit as $submit)
                        <div class="p-4 space-y-3 border border-gray-200 rounded-lg bg-gray-50">

                            {{-- HEADER SUBMIT --}}
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm text-gray-600">
                                    Dikirim : {{ \Carbon\Carbon::parse($submit->created_at)->format('d M Y H:i') }}
                                </div>
                                <div>
                                    @if($submit->status == 'pending' && !$submit->revisi)
                                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                            Pending
                                        </span>
                                    @elseif($submit->revisi)
                                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                            Revisi
                                        </span>
                                    @elseif($submit->status == 'selesai')
                                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- LAPORAN --}}
                            @if($submit->laporan)
                                <div class="text-sm text-gray-700 whitespace-pre-line">
                                    {{ $submit->laporan }}
                                </div>
                            @endif

                            {{-- FILE --}}
                            @if($submit->file)
                                <button onclick="openFileModal('{{ asset('storage/'.$submit->file) }}')"
                                    class="px-3 py-2 text-sm font-medium text-white rounded-md bg-emerald-600 hover:bg-emerald-700">
                                    Preview / Download File
                                </button>
                            @endif

                            {{-- CATATAN REVISI --}}
                            @if($submit->catatan_revisi)
                                <div class="p-3 text-sm text-red-700 border border-red-200 rounded bg-red-50">
                                    <b>Catatan Revisi :</b>
                                    <div class="mt-1 whitespace-pre-line">
                                        {{ $submit->catatan_revisi }}
                                    </div>
                                </div>
                            @endif

                            {{-- AKSI MITRA --}}
                            @if($submit->status != 'selesai' && !$submit->revisi)
                                <div class="flex flex-wrap gap-2 pt-2">
                                    <form method="POST" action="{{ route('mitra.tugas.verifikasi', $submit->id) }}">
                                        @csrf
                                        <input type="hidden" name="aksi" value="selesai">
                                        <button class="px-3 py-1 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                                            Terima
                                        </button>
                                    </form>
                                    <button onclick="openRevisiModal({{ $submit->id }})"
                                        class="px-3 py-1 text-sm text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                        Minta Revisi
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL FILE --}}
    <div id="fileModal" class="fixed inset-0 z-[999999] hidden bg-black/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-4xl overflow-hidden bg-white shadow-lg rounded-xl">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="font-semibold text-gray-700">
                        Preview File
                    </h3>
                    <button onclick="closeFileModal()" class="text-gray-500 hover:text-red-500">
                        ✕
                    </button>
                </div>
                <div class="p-4">
                    <iframe id="fileViewer" class="w-full h-[60vh] rounded">
                    </iframe>
                    <div class="mt-4 text-right">
                        <a id="downloadBtn" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700" download>
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL REVISI --}}
    <div id="revisiModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-md p-6 bg-white shadow rounded-xl">
                <h3 class="mb-3 text-lg font-semibold">
                    Catatan Revisi
                </h3>
                <form method="POST" id="formRevisi">
                    @csrf
                    <input type="hidden" name="aksi" value="revisi">
                    <textarea name="catatan_revisi"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-yellow-200"
                        placeholder="Tuliskan revisi untuk mahasiswa..." required></textarea>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="closeRevisiModal()" class="px-3 py-1 bg-gray-300 rounded">
                            Batal
                        </button>
                        <button class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                            Kirim Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFileModal(url) {
    let viewer = document.getElementById('fileViewer');
    let download = document.getElementById('downloadBtn');
    let ext = url.split('.').pop().toLowerCase();

    document.getElementById('fileModal').classList.remove('hidden');

    // RESET isi viewer
    viewer.style.display = 'block';

    // HANDLE FILE
    if (ext === 'pdf') {
        viewer.src = url;

    } else if (ext === 'doc' || ext === 'docx' || ext === 'xls' || ext === 'xlsx') {
        viewer.src = "https://view.officeapps.live.com/op/embed.aspx?src=" + encodeURIComponent(url);

    } else if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') {
        // 🔥 HANDLE GAMBAR
        viewer.src = url;

    } else {
        viewer.src = "";
    }

    download.href = url;
}

        function closeFileModal() {
            document.getElementById('fileModal').classList.add('hidden');
            document.getElementById('fileViewer').src = "";
        }

        function openRevisiModal(id) {
            document.getElementById('revisiModal').classList.remove('hidden');
            document.getElementById('formRevisi').action = "/mitra/tugas/verifikasi/" + id;
        }

        function closeRevisiModal() {
            document.getElementById('revisiModal').classList.add('hidden');
        }
    </script>
</x-app-layout>