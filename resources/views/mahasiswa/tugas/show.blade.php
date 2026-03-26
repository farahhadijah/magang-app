<x-app-layout>
<x-slot name="title">
Detail Tugas
</x-slot>

<div class="max-w-4xl py-6 mx-auto space-y-6">

    {{-- Card Detail Tugas --}}
    <div class="p-6 bg-white rounded-lg shadow">

        <h2 class="mb-4 text-xl font-semibold text-gray-800">
            {{ $tugas->judul }}
        </h2>

        <div class="mb-4">
            <p class="mb-1 text-sm font-semibold text-gray-600">
                Deskripsi
            </p>

            <div class="max-w-2xl leading-relaxed text-gray-700 whitespace-pre-line">
                {{ $tugas->deskripsi }}
            </div>
        </div>

        {{-- File dari Mitra --}}
        @if($tugas->file)

        <div class="mb-4">

            <p class="mb-1 text-sm font-semibold text-gray-600">
                File Tugas dari Mitra
            </p>

            <a href="{{ asset('storage/'.$tugas->file) }}"
            target="_blank"
            class="inline-block px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">

                Download File Tugas

            </a>

        </div>

        @endif

        <div>
            <span class="text-sm font-semibold text-gray-600">
                Deadline :
            </span>

            <span class="px-3 py-1 ml-2 text-sm text-red-700 bg-red-100 rounded-full">
                {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
            </span>
        </div>

    </div>

    @if(!$submit)

{{-- =========================
BELUM SUBMIT
========================= --}}
<div class="p-6 bg-white rounded-lg shadow">

    <h3 class="mb-4 text-lg font-semibold text-gray-800">
        Kumpulkan Tugas
    </h3>

    <form method="POST"
          action="{{ route('mahasiswa.tugas.submit',$tugas->id) }}"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Laporan
            </label>

            <textarea
                name="laporan"
                rows="6"
                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-green-200"
                placeholder="Tuliskan laporan tugas..."
                required></textarea>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Upload File (opsional)
            </label>

            <input
                type="file"
                name="file"
                class="block w-full px-3 py-2 text-sm border rounded-lg">
        </div>

        <button
            type="submit"
            class="px-4 py-2 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
            Kirim Tugas
        </button>

    </form>

</div>

@elseif($submit->revisi)

{{-- =========================
REVISI
========================= --}}
<div class="p-6 bg-white border border-red-200 rounded-lg shadow bg-red-50">

    <h3 class="mb-3 text-lg font-semibold text-red-700">
        Tugas Perlu Revisi
    </h3>

    <p class="mb-2 text-sm font-semibold text-gray-700">
        Catatan dari Mitra:
    </p>

    <div class="p-3 mb-4 text-gray-700 bg-white border rounded-lg">
        {{ $submit->catatan_revisi ?? 'Tidak ada catatan.' }}
    </div>

    <form method="POST"
          action="{{ route('mahasiswa.tugas.submit',$tugas->id) }}"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Perbaiki Laporan
            </label>

            <textarea
                name="laporan"
                rows="6"
                class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-green-200"
                required>{{ $submit->laporan }}</textarea>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Upload File Baru
            </label>

            <input
                type="file"
                name="file"
                class="block w-full px-3 py-2 text-sm border rounded-lg">
        </div>

        <button
            type="submit"
            class="px-4 py-2 text-white transition bg-red-600 rounded-lg hover:bg-red-700">
            Upload Revisi
        </button>

    </form>

</div>

@else

{{-- =========================
SUDAH SUBMIT
========================= --}}
<div class="p-6 bg-white rounded-lg shadow">

    <h3 class="mb-4 text-lg font-semibold text-gray-800">
        Tugas Sudah Dikumpulkan
    </h3>

    <div class="mb-4">
        <p class="mb-1 text-sm font-semibold text-gray-600">
            Laporan Anda
        </p>

        <div class="max-w-2xl text-gray-700 whitespace-pre-line">
            {{ $submit->laporan }}
        </div>
    </div>

    @if($submit->file)
    <div>
        <a href="{{ asset('storage/'.$submit->file) }}"
           class="inline-block px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
            Download File
        </a>
    </div>
    @endif

    <div class="mt-4">
        <span class="px-3 py-1 text-sm text-yellow-800 bg-yellow-200 rounded-full">
            Status: {{ ucfirst($submit->status) }}
        </span>
    </div>

</div>

@endif


</div>

</x-app-layout>