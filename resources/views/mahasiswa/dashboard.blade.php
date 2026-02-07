<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold text-green-800">
                Dashboard Mahasiswa
            </h2>

            <p class="text-sm text-green-600">
                Selamat datang, {{ auth()->user()->getNama() }} 👋
            </p>
        </div>
    </x-slot>


    <div class="py-6 space-y-8">

        {{-- Hero Welcome --}}
        <div
            class="p-6 text-white shadow bg-gradient-to-r from-green-700 via-green-600 to-green-500 rounded-xl"
        >
            <h1 class="text-2xl font-bold">
                Sistem Informasi PKL
            </h1>

            <p class="mt-1 text-green-100">
                Pantau progres PKL kamu secara realtime.
            </p>
        </div>


        {{-- Status Cards --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Status --}}
            <div
                class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md"
            >
                <p class="text-sm text-green-700">
                    Status PKL
                </p>

                <div class="mt-2">
                    @if (!$pengajuan)
                        <span class="font-semibold text-green-400">
                            Belum Mengajukan
                        </span>
                    @else
                        <x-status-badge :status="$pengajuan->status" />
                    @endif
                </div>
            </div>


            {{-- Tempat --}}
            <div
                class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md"
            >
                <p class="text-sm text-green-700">
                    Tempat PKL
                </p>

                <h3 class="mt-2 text-lg font-semibold text-green-900">
                    {{ $pengajuan?->tempatPkl?->nama_tempat ?? '-' }}
                </h3>
            </div>


            {{-- Dosen --}}
            <div
                class="p-6 transition border border-green-200 bg-green-50 rounded-xl hover:shadow-md"
            >
                <p class="text-sm text-green-700">
                    Dosen Pembimbing
                </p>

                <h3 class="mt-2 text-lg font-semibold text-green-900">
                    {{ $pengajuan?->pkl?->dosen?->username ?? '-' }}
                </h3>
            </div>

        </div>


        {{-- Timeline --}}
        @if ($pengajuan)

            <div
                class="p-6 bg-white border border-green-200 shadow-sm rounded-xl"
            >
                <h3 class="mb-4 text-lg font-semibold text-green-800">
                    Timeline PKL
                </h3>

                <x-pkl-timeline :timeline="$timeline" />
            </div>

        @endif


        {{-- Information --}}
        <div
            class="p-5 border border-amber-300 bg-amber-50 rounded-xl"
        >
            <h4 class="font-semibold text-amber-700">
                ℹ️ Informasi
            </h4>

            <p class="mt-1 text-sm text-amber-600">
                Pastikan seluruh dokumen PKL sudah diunggah sesuai ketentuan.
            </p>
        </div>

    </div>

</x-app-layout>
