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

        {{-- ================= TIMELINE ================= --}}
        @php
            $status = $pengajuan->status;

            // TIMELINE KHUSUS PENGAJUAN (BUKAN PKL)
            $steps = [
                'pengajuan'  => true,
                'verifikasi' => in_array($status, ['diverifikasi', 'disetujui']),
                'berjalan'   => $status === 'disetujui',
                'selesai'    => false, // nanti dari table PKL
            ];
        @endphp

        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <h3 class="mb-6 text-lg font-semibold text-green-800">
                Timeline Pengajuan PKL
            </h3>

            <div class="relative flex justify-between">
                {{-- Line --}}
                <div class="absolute left-0 right-0 h-1 bg-green-100 top-4"></div>

                @foreach ([
                    'pengajuan'  => 'Pengajuan',
                    'verifikasi' => 'Verifikasi TU',
                    'berjalan'   => 'Disetujui Kaprodi',
                    'selesai'    => 'Selesai'
                ] as $key => $label)

                    @php $active = $steps[$key]; @endphp

                    <div class="relative z-10 flex flex-col items-center w-1/4">
                        <div class="
                            w-9 h-9 flex items-center justify-center rounded-full font-semibold
                            {{ $active
                                ? 'bg-green-600 text-white ring-4 ring-green-100'
                                : 'bg-gray-300 text-gray-600'
                            }}">
                            {{ $active ? '✓' : $loop->iteration }}
                        </div>

                        <span class="mt-2 text-sm font-medium
                            {{ $active ? 'text-green-700' : 'text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Jika Ditolak --}}
            @if ($status === 'ditolak')
                <div class="p-4 mt-6 border border-red-200 rounded-lg bg-red-50">
                    <p class="text-sm text-red-700">
                        ❌ Pengajuan PKL ditolak. Silakan ajukan kembali dengan data yang diperbaiki.
                    </p>
                </div>
            @endif
        </div>

        {{-- ================= STATUS ================= --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-green-800">
                    Status Pengajuan
                </h3>

                @php
                    $badge = match($pengajuan->status) {
                        'pending'       => 'bg-amber-100 text-amber-800',
                        'diverifikasi'  => 'bg-blue-100 text-blue-800',
                        'disetujui'     => 'bg-green-100 text-green-800',
                        'ditolak'       => 'bg-red-100 text-red-800',
                        default         => 'bg-gray-100 text-gray-800',
                    };
                @endphp

                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $badge }}">
                    {{ ucfirst($pengajuan->status) }}
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
                    <td class="py-2">: {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-medium text-gray-600">Jenis</td>
                    <td class="py-2">: {{ $pengajuan->tempatPkl->jenis_tempat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-medium text-gray-600">No HP</td>
                    <td class="py-2">: {{ $pengajuan->tempatPkl->no_hp ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <div class="p-6 bg-white border border-green-100 shadow rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-green-800">
                Dokumen Pengajuan
            </h3>

            @if ($pengajuan->dokumenPengajuan->count())
                <ul class="space-y-2 text-sm">
                    @foreach ($pengajuan->dokumenPengajuan as $doc)
                        <li>
                            <a href="{{ asset('storage/' . $doc->path_file) }}"
                               target="_blank"
                               class="text-green-700 hover:text-green-900">
                                📄 {{ $doc->jenis_dokumen }}
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

        {{-- ================= INFO PKL (SETELAH DISETUJUI) ================= --}}
        @if ($pengajuan->pkl)
            <div class="p-6 border border-green-200 rounded-xl bg-green-50">
                <h3 class="mb-2 text-lg font-semibold text-green-800">
                    Informasi PKL
                </h3>

                <p class="text-sm text-green-700">
                    Dosen Pembimbing:
                    <strong>{{ $pengajuan->pkl->dosen->nama ?? '-' }}</strong>
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
