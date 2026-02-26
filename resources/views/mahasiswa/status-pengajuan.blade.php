
<x-app-layout>

    <x-slot name="title">
        Status Pengajuan PKL
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-6">

        {{-- ================= BELUM ADA PENGAJUAN ================= --}}
        @if (!$pengajuan)

            <div class="p-6 border border-amber-200 rounded-xl bg-amber-50">
                <h3 class="text-lg font-semibold text-amber-800">
                    Belum Ada Pengajuan PKL
                </h3>

                <p class="mt-2 text-sm text-amber-700">
                    Kamu belum mengajukan PKL. Silakan ajukan terlebih dahulu.
                </p>

                <a href="{{ route('mahasiswa.pengajuan.create') }}"
                   class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Ajukan PKL
                </a>
            </div>
        @else

        {{-- ================= DITOLAK TU ================= --}}
        @if ($pengajuan->status === 'ditolak_tu')
            <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                <p class="text-sm font-semibold text-red-700">
                    ❌ Pengajuan PKL ditolak oleh TU
                </p>

                <p class="mt-1 text-sm text-red-600">
                    Alasan:
                    <span class="italic">
                        {{ $pengajuan->catatan_tu ?? 'Tidak ada catatan.' }}
                    </span>
                </p>

                <a href="{{ route('mahasiswa.pengajuan.create') }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Ajukan Ulang PKL
                </a>
            </div>
        @endif

        {{-- ================= DITOLAK KAPRODI ================= --}}
        @if ($pengajuan->status === 'ditolak_kaprodi')
            <div class="p-4 border border-red-300 rounded-lg bg-red-50">
                <p class="text-sm font-semibold text-red-800">
                    ❌ Pengajuan PKL ditolak oleh Kaprodi
                </p>

                <p class="mt-1 text-sm text-red-700">
                    Alasan:
                    <span class="italic">
                        {{ $pengajuan->catatan_kaprodi ?? 'Tidak ada catatan.' }}
                    </span>
                </p>

                <a href="{{ route('mahasiswa.pengajuan.create') }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Ajukan Ulang PKL
                </a>
            </div>
        @endif


        {{-- ================= TIMELINE ================= --}}
@php
    $status = $pengajuan->status;

    $timeline = [
        'pengajuan' => [
            'label' => 'Pengajuan',
            'active' => true,
        ],
        'verifikasi_tu' => [
            'label' => 'Verifikasi TU',
            'active' => in_array($status, [
                'diverifikasi_tu',
                'pending_kaprodi',
                'disetujui'
            ]),
        ],
        'kaprodi' => [
            'label' => 'Persetujuan Kaprodi',
            'active' => in_array($status, [
                'pending_kaprodi',
                'disetujui'
            ]),
        ],
        'selesai' => [
            'label' => 'Selesai',
            'active' => $status === 'disetujui',
        ],
    ];
@endphp

<div class="p-6 bg-white border border-green-100 shadow rounded-xl">
    <h3 class="mb-6 text-lg font-semibold text-green-800">
        Timeline Pengajuan PKL
    </h3>

    <div class="relative flex justify-between">
        <div class="absolute left-0 right-0 h-1 bg-green-100 top-4"></div>

        @foreach ($timeline as $step)
            <div class="relative z-10 flex flex-col items-center w-1/4">
                <div class="
                    w-9 h-9 flex items-center justify-center rounded-full font-semibold
                    {{ $step['active']
                        ? 'bg-green-600 text-white ring-4 ring-green-100'
                        : 'bg-gray-300 text-gray-600'
                    }}">
                    {{ $step['active'] ? '✓' : $loop->iteration }}
                </div>

                <span class="mt-2 text-sm font-medium
                    {{ $step['active'] ? 'text-green-700' : 'text-gray-500' }}">
                    {{ $step['label'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>


        {{-- ================= STATUS ================= --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-green-800">
                    Status Pengajuan
                </h3>

                @php
                    $badge = match($pengajuan->status) {
                        'pending_tu'       => 'bg-amber-100 text-amber-800',
                        'pending_kaprodi'  => 'bg-blue-100 text-blue-800',
                        'disetujui'        => 'bg-green-100 text-green-800',
                        'ditolak_tu'       => 'bg-red-100 text-red-800',
                        default            => 'bg-gray-100 text-gray-800',
                    };

                    $labelStatus = match($pengajuan->status) {
                        'pending_tu'      => 'Menunggu Verifikasi TU',
                        'pending_kaprodi' => 'Menunggu Persetujuan Kaprodi',
                        'disetujui'       => 'Disetujui',
                        'ditolak_tu'      => 'Ditolak TU',
                        default           => ucfirst($pengajuan->status),
                    };
                @endphp

                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $badge }}">
                    {{ $labelStatus }}
                </span>
            </div>

            <p class="mt-2 text-sm text-gray-600">
                Tanggal Pengajuan:
                <strong class="text-gray-800">
                    {{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}
                </strong>
            </p>
        </div>

        {{-- ================= DATA TEMPAT ================= --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-green-800">
                Data Tempat PKL
            </h3>

            <table class="w-full text-sm">
                <tr class="border-b">
                    <td class="w-40 py-2 font-medium text-gray-600">Nama Tempat</td>
                    <td class="py-2">: {{ optional($pengajuan->tempatPkl)->nama_tempat ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-medium text-gray-600">Jenis</td>
                    <td class="py-2">: {{ optional($pengajuan->tempatPkl)->jenis_tempat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-medium text-gray-600">No HP</td>
                    <td class="py-2">: {{ optional($pengajuan->tempatPkl)->no_hp ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <h3 class="mb-6 text-lg font-semibold text-green-800">
                Dokumen Pengajuan
            </h3>

            @php
                $khsList = $pengajuan->dokumenPengajuan->where('jenis_dokumen', 'KHS');
                $lainnya = $pengajuan->dokumenPengajuan->where('jenis_dokumen', '!=', 'KHS');
            @endphp

            {{-- ================= KHS MULTIPLE ================= --}}
            <div class="mb-6">
                <h4 class="mb-3 font-semibold text-gray-700">
                    📚 KHS Semester 1 - Terakhir
                </h4>

                @forelse ($khsList as $index => $doc)
                    <div class="p-4 mb-3 border rounded-lg bg-gray-50">

                        <div class="flex items-center justify-between">
                            <a href="{{ asset('storage/' . $doc->path_file) }}"
                            target="_blank"
                            class="font-medium text-green-700 hover:underline">
                                📄 KHS File {{ $index + 1 }}
                            </a>

                            @php
                                $badge = match($doc->status_verifikasi) {
                                    'valid'   => 'bg-green-100 text-green-800',
                                    'invalid' => 'bg-red-100 text-red-800',
                                    default   => 'bg-amber-100 text-amber-800',
                                };
                            @endphp

                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $badge }}">
                                {{ ucfirst($doc->status_verifikasi) }}
                            </span>
                        </div>

                        @if ($doc->status_verifikasi === 'invalid' && $doc->catatan)
                            <p class="mt-2 text-sm text-red-700">
                                <strong>Catatan TU:</strong> {{ $doc->catatan }}
                            </p>
                        @endif

                        @if ($pengajuan->status === 'ditolak_tu' && $doc->status_verifikasi === 'invalid')
                            <form method="POST"
                                action="{{ route('mahasiswa.pengajuan.dokumen.upload-ulang', $doc->id) }}"
                                enctype="multipart/form-data"
                                class="mt-3">
                                @csrf

                                <input type="file"
                                    name="dokumen"
                                    required
                                    accept=".pdf,.doc,.docx"
                                    class="block w-full text-sm">

                                <button type="submit"
                                        class="px-4 py-2 mt-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Upload Ulang
                                </button>
                            </form>
                        @endif

                    </div>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada KHS diunggah.</p>
                @endforelse
            </div>

            {{-- ================= DOKUMEN LAINNYA ================= --}}
            <div>
                <h4 class="mb-3 font-semibold text-gray-700">
                    📄 Dokumen Lainnya
                </h4>

                @foreach ($lainnya as $doc)
                    <div class="p-4 mb-3 border rounded-lg">

                        <div class="flex items-center justify-between">
                            <a href="{{ asset('storage/' . $doc->path_file) }}"
                            target="_blank"
                            class="font-medium text-green-700 hover:underline">
                                📄 {{ $doc->jenis_dokumen }}
                            </a>

                            @php
                                $badge = match($doc->status_verifikasi) {
                                    'valid'   => 'bg-green-100 text-green-800',
                                    'invalid' => 'bg-red-100 text-red-800',
                                    default   => 'bg-amber-100 text-amber-800',
                                };
                            @endphp

                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $badge }}">
                                {{ ucfirst($doc->status_verifikasi) }}
                            </span>
                        </div>

                        @if ($doc->status_verifikasi === 'invalid' && $doc->catatan)
                            <p class="mt-2 text-sm text-red-700">
                                <strong>Catatan TU:</strong> {{ $doc->catatan }}
                            </p>
                        @endif

                        @if ($pengajuan->status === 'ditolak_tu' && $doc->status_verifikasi === 'invalid')
                            <form method="POST"
                                action="{{ route('mahasiswa.pengajuan.dokumen.upload-ulang', $doc->id) }}"
                                enctype="multipart/form-data"
                                class="mt-3">
                                @csrf

                                <input type="file"
                                    name="dokumen"
                                    required
                                    accept=".pdf,.doc,.docx,.jpg,.png"
                                    class="block w-full text-sm">

                                <button type="submit"
                                        class="px-4 py-2 mt-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Upload Ulang
                                </button>
                            </form>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

            {{-- ================= SURAT PENGANTAR ================= --}}
            @if($pengajuan->pkl && $pengajuan->pkl->suratPengantar)
                <div class="p-4 mt-6 border border-green-200 bg-green-50 rounded-xl">
                    <h4 class="mb-3 font-semibold text-green-800">
                        📄 Surat Pengantar PKL
                    </h4>

                    <a href="{{ asset('storage/' . $pengajuan->pkl->suratPengantar->path_file) }}"
                    target="_blank"
                    class="inline-block px-5 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Download Surat Pengantar
                    </a>
                </div>
            @endif

        </div>

        {{-- ================= INFO PKL (SETELAH DISETUJUI) ================= --}}
        @if ($pengajuan->pkl)
            <div class="p-6 border border-green-200 rounded-xl bg-green-50">
                <h3 class="mb-2 text-lg font-semibold text-green-800">
                    Informasi PKL
                </h3>

                <p class="text-sm text-green-700">
                    Dosen Pembimbing:
                    <strong>{{ optional($pengajuan->pkl->dosen)->nama ?? '-' }}</strong>
                </p>

                <p class="mt-1 text-sm text-green-700">
                    Status PKL:
                    <strong>{{ ucfirst($pengajuan->pkl->status) }}</strong>
                </p>
            </div>
        @endif

        @endif
    </div>

</x-app-layout>
