<x-guest-layout>

    <div class="max-w-2xl px-6 py-10 mx-auto">

        <div class="p-8 bg-white shadow rounded-2xl">

            <div class="mb-6 text-center">

                <h1 class="text-3xl font-bold text-green-700">
                    Dokumen Terverifikasi
                </h1>

                <p class="mt-2 text-gray-500">
                    Dokumen penilaian PKL resmi Sibolang
                </p>

            </div>

            <div class="space-y-4">

                <div>
                    <span class="font-semibold">
                        Nama Mahasiswa:
                    </span>

                    {{ $penilaian->pkl->mahasiswa->nama }}
                </div>

                <div>
                    <span class="font-semibold">
                        NIM:
                    </span>

                    {{ $penilaian->pkl->mahasiswa->nim }}
                </div>

                <div>
                    <span class="font-semibold">
                        Program Studi:
                    </span>

                    {{ $penilaian->pkl->mahasiswa->prodi->nama }}
                </div>

                <div>
                    <span class="font-semibold">
                        Nilai:
                    </span>

                    {{ $penilaian->rata_rata }}
                    ({{ $penilaian->grade }})
                </div>

                <div>
                    <span class="font-semibold">
                        Tanggal Input:
                    </span>

                    {{ \Carbon\Carbon::parse($penilaian->tgl_input)->translatedFormat('d F Y') }}
                </div>

            </div>

        </div>

    </div>

</x-guest-layout>