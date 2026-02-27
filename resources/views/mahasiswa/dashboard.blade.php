<x-app-layout>
    <x-slot name="title">
        Dashboard Mahasiswa - MagangApp
    </x-slot>
    <div class="py-6 space-y-8">
        {{-- ================= HERO ================= --}}
        <div class="p-6 text-white shadow bg-gradient-to-r from-green-700 via-green-600 to-green-500 rounded-xl">
            <h1 class="text-2xl font-bold">
                Sistem Informasi PKL
            </h1>
            <p class="mt-1 text-green-100">
                Pantau progres PKL kamu secara realtime.
            </p>
        </div>
        {{-- ================= STATUS CARDS ================= --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            {{-- STATUS PKL --}}
            <div class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md">
                <p class="text-sm text-green-700">
                    Status PKL
                </p>
                <div class="mt-2">
                    @if (!$pengajuan)
                        <span class="font-semibold text-gray-500">
                            Belum Mengajukan
                        </span>
                    @else
                        @php
                            $badge = match($pengajuan->status) {
                            'pending_tu'        => 'bg-amber-100 text-amber-800',
                            'diverifikasi_tu'   => 'bg-indigo-100 text-indigo-800',
                            'pending_kaprodi'   => 'bg-blue-100 text-blue-800',
                            'disetujui'         => 'bg-green-100 text-green-800',
                            'ditolak_tu', 
                            'ditolak_kaprodi'   => 'bg-red-100 text-red-800',
                            default             => 'bg-gray-100 text-gray-800',
                        };
                            $label = match($pengajuan->status) {
                            'pending_tu'        => 'Menunggu Verifikasi TU',
                            'diverifikasi_tu'   => 'Terverifikasi Administrasi',
                            'pending_kaprodi'   => 'Menunggu Persetujuan Kaprodi',
                            'disetujui'         => 'Disetujui Kaprodi',
                            'ditolak_tu'        => 'Ditolak TU',
                            'ditolak_kaprodi'   => 'Ditolak Kaprodi',
                            default             => ucfirst($pengajuan->status),
                        };
                        @endphp
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $badge }}">
                            {{ $label }}
                        </span>
                    @endif
                </div>
            </div>
            {{-- TEMPAT PKL --}}
            <div class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md">
                <p class="text-sm text-green-700">
                    Tempat PKL
                </p>
                <h3 class="mt-2 text-lg font-semibold text-green-900">
                    {{ optional($pengajuan?->tempatPkl)->nama_tempat ?? '-' }}
                </h3>
            </div>
            {{-- DOSEN PEMBIMBING --}}
            <div class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md">
                <p class="text-sm text-green-700">
                    Dosen Pembimbing
                </p>
                <h3 class="mt-2 text-lg font-semibold text-green-900">
                    {{ optional($pengajuan?->pkl?->dosen)->nama ?? '-' }}
                </h3>
            </div>
        </div>
        {{-- ================= TIMELINE ================= --}}
        @if ($pengajuan)
            @php
                    $pengajuanStatus = $pengajuan->status;
                    $pklStatus = $pengajuan->pkl?->status;
                    $steps = [
                        'Pengajuan' => true,
                        'Verifikasi TU' => in_array($pengajuanStatus, [
                            'diverifikasi_tu',
                            'pending_kaprodi',
                            'disetujui'
                        ]),
                        'Persetujuan Kaprodi' => in_array($pengajuanStatus, [
                            'pending_kaprodi',
                            'disetujui'
                        ]),
                        'PKL Berjalan' => $pklStatus === 'aktif'
                            || $pklStatus === 'selesai',
                        'Selesai' => $pklStatus === 'selesai',
                    ];
                @endphp
            <div class="p-6 bg-white border border-green-200 shadow-sm rounded-xl">
                <h3 class="mb-6 text-lg font-semibold text-green-800">
                    Timeline PKL
                </h3>
                <div class="relative flex justify-between">
                    <div class="absolute left-0 right-0 h-1 bg-green-100 top-4"></div>
                    @foreach ($steps as $label => $active)
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
            </div>
        @endif
        {{-- ================= INFO ================= --}}
        <div class="p-5 border border-amber-300 bg-amber-50 rounded-xl">
            <h4 class="font-semibold text-amber-700">
                ℹ️ Informasi
            </h4>
            <p class="mt-1 text-sm text-amber-600">
                Pastikan seluruh dokumen PKL sudah diunggah dan menunggu verifikasi TU sebelum diteruskan ke Kaprodi.
            </p>
        </div>
    </div>
</x-app-layout>
