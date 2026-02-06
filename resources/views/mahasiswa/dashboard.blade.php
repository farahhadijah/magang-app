<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Dashboard Mahasiswa
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- Welcome --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Selamat datang 👋
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Silakan pantau dan kelola kegiatan PKL kamu di sini.
            </p>
        </div>

        {{-- Timeline PKL --}}
        @if ($pengajuan)
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Timeline PKL
                </h3>

                <x-pkl-timeline :timeline="$timeline" />
            </div>
        @endif

        {{-- Status PKL --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Status --}}
            <div class="p-6 rounded-lg shadow bg-blue-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                    Status Pengajuan PKL
                </h3>
                <div class="mt-2">
                    @if (!$pengajuan)
                        <span class="font-semibold text-gray-500">
                            Belum Mengajukan
                        </span>
                    @else
                        <x-status-badge :status="$pengajuan->status" />
                    @endif
                </div>
            </div>

            {{-- Tempat PKL --}}
            <div class="p-6 rounded-lg shadow bg-green-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-green-700 dark:text-green-300">
                    Tempat PKL
                </h3>
                <p class="mt-2 text-xl font-bold text-green-900 dark:text-green-100">
                    {{ $pengajuan?->tempatPkl?->nama_tempat ?? '-' }}
                </p>
            </div>

            {{-- Dosen --}}
            <div class="p-6 rounded-lg shadow bg-purple-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-purple-700 dark:text-purple-300">
                    Dosen Pembimbing
                </h3>
                <p class="mt-2 text-xl font-bold text-purple-900 dark:text-purple-100">
                    {{ $pengajuan?->pkl?->dosen?->username ?? '-' }}
                </p>
            </div>

        </div>

        {{-- Quick Action --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                Aksi Cepat
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Ajukan PKL --}}
                @if (!$pengajuan)
                    <a href="{{ route('mahasiswa.pengajuan.create') }}"
                       class="block p-4 text-center border rounded-lg hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                        Ajukan PKL
                    </a>
                @else
                    <div class="block p-4 text-center text-gray-400 border rounded-lg cursor-not-allowed">
                        PKL Sudah Diajukan
                    </div>
                @endif

                {{-- Logbook --}}
                @if ($pengajuan?->pkl)
                    <a href="{{ route('mahasiswa.logbook.index') }}"
                       class="block p-4 text-center border rounded-lg hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                        Isi Logbook
                    </a>
                @else
                    <div class="block p-4 text-center text-gray-400 border rounded-lg cursor-not-allowed">
                        Isi Logbook
                    </div>
                @endif

                {{-- Upload Laporan --}}
                <div class="block p-4 text-center text-gray-400 border rounded-lg cursor-not-allowed">
                    Upload Laporan Akhir
                </div>

                {{-- Status Pengajuan --}}
                <a href="{{ route('mahasiswa.pengajuan.status') }}"
                   class="block p-4 text-center border rounded-lg hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                    Status Pengajuan PKL
                </a>

            </div>
        </div>

        {{-- Informasi --}}
        <div class="p-6 rounded-lg shadow bg-yellow-50 dark:bg-gray-700">
            <h3 class="font-semibold text-yellow-800 dark:text-yellow-300">
                Informasi
            </h3>
            <p class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                Pastikan semua dokumen PKL diunggah sesuai dengan ketentuan yang berlaku.
            </p>
        </div>

    </div>
</x-app-layout>
