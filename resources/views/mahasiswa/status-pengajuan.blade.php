<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Status Pengajuan PKL
        </h2>
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-6">
        {{-- TIMELINE PKL --}}
        @if ($pengajuan)
        @php
            $status = $pengajuan->status;

            $steps = [
                'pengajuan' => true,
                'verifikasi' => in_array($status, ['disetujui','berjalan','selesai']),
                'berjalan' => in_array($status, ['berjalan','selesai']),
                'selesai' => $status === 'selesai',
            ];
        @endphp

        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-6 text-lg font-semibold">
                Timeline PKL
            </h3>

            <div class="relative flex justify-between">

                {{-- Garis --}}
                <div class="absolute left-0 right-0 h-1 bg-gray-200 top-4"></div>

                @foreach ([
                    'pengajuan' => 'Pengajuan',
                    'verifikasi' => 'Verifikasi',
                    'berjalan' => 'PKL Berjalan',
                    'selesai' => 'Selesai'
                ] as $key => $label)

                    @php
                        $active = $steps[$key];
                    @endphp

                    <div class="relative z-10 flex flex-col items-center w-1/4 text-center">

                        {{-- Bulatan --}}
                        <div class="
                            w-8 h-8 rounded-full flex items-center justify-center
                            {{ $active ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}
                        ">
                            @if ($active)
                                ✓
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>

                        {{-- Label --}}
                        <span class="mt-2 text-sm font-medium
                            {{ $active ? 'text-green-700' : 'text-gray-500' }}">
                            {{ $label }}
                        </span>

                    </div>
                @endforeach

            </div>

            {{-- Status khusus ditolak --}}
            @if ($status === 'ditolak')
                <div class="p-4 mt-6 border border-red-200 rounded bg-red-50">
                    <p class="text-sm text-red-700">
                        ❌ Pengajuan PKL ditolak. Silakan ajukan kembali dengan data yang diperbaiki.
                    </p>
                </div>
            @endif
        </div>
        @endif


        {{-- Jika belum pernah mengajukan --}}
        @if (!$pengajuan)
            <div class="p-6 border border-yellow-200 rounded-lg bg-yellow-50">
                <h3 class="font-semibold text-yellow-800">
                    Belum Ada Pengajuan PKL
                </h3>
                <p class="mt-2 text-sm text-yellow-700">
                    Kamu belum mengajukan PKL. Silakan ajukan terlebih dahulu.
                </p>

                <a href="{{ route('mahasiswa.pengajuan.create') }}"
                   class="inline-block px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                    Ajukan PKL
                </a>
            </div>
        @else

        {{-- STATUS CARD --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">
                    Status Pengajuan
                </h3>

                {{-- Badge status --}}
                @php
                    $badge = match($pengajuan->status) {
                        'pending'   => 'bg-yellow-100 text-yellow-800',
                        'disetujui' => 'bg-green-100 text-green-800',
                        'ditolak'   => 'bg-red-100 text-red-800',
                        'berjalan'  => 'bg-blue-100 text-blue-800',
                        'selesai'   => 'bg-gray-200 text-gray-800',
                        default     => 'bg-gray-100 text-gray-800'
                    };
                @endphp

                <span class="px-3 py-1 text-sm rounded {{ $badge }}">
                    {{ ucfirst($pengajuan->status) }}
                </span>
            </div>

            <p class="mt-2 text-sm text-gray-600">
                Tanggal Pengajuan:
                <strong>{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</strong>
            </p>
        </div>

        {{-- DATA TEMPAT PKL --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold">Data Tempat PKL</h3>

            <table class="w-full text-sm">
                <tr>
                    <td class="w-40 py-1 font-medium">Nama Tempat</td>
                    <td>: {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium">Jenis</td>
                    <td>: {{ $pengajuan->tempatPkl->jenis_tempat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-medium">No HP</td>
                    <td>: {{ $pengajuan->tempatPkl->no_hp ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- DOKUMEN --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold">Dokumen Pengajuan</h3>

            @if ($pengajuan->dokumenPengajuan->count())
                <ul class="text-sm list-disc list-inside">
                    @foreach ($pengajuan->dokumenPengajuan as $doc)
                        <li>
                            <a href="{{ asset('storage/' . $doc->path_file) }}"
                               class="text-blue-600 underline"
                               target="_blank">
                                {{ $doc->jenis_dokumen }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">
                    Tidak ada dokumen terunggah.
                </p>
            @endif
        </div>

        {{-- INFO PKL (JIKA SUDAH DISETUJUI) --}}
        @if ($pengajuan->pkl)
            <div class="p-6 border border-green-200 rounded-lg bg-green-50">
                <h3 class="mb-2 text-lg font-semibold text-green-800">
                    Informasi PKL
                </h3>

                <p class="text-sm">
                    Dosen Pembimbing:
                    <strong>{{ $pengajuan->pkl->dosen->nama ?? '-' }}</strong>
                </p>

                <p class="text-sm">
                    Status PKL:
                    <strong>{{ ucfirst($pengajuan->pkl->status) }}</strong>
                </p>
            </div>
        @endif

        @endif
    </div>
</x-app-layout>
