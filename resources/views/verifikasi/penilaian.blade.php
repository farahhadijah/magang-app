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
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <tbody>
                            <tr class="bg-gray-50">
                                <th class="py-2 px-3 font-medium">Nama Mahasiswa</th>
                                <td class="py-2 px-3">{{ $penilaian->pkl->mahasiswa->nama ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th class="py-2 px-3 font-medium">NIM</th>
                                <td class="py-2 px-3">{{ $penilaian->pkl->mahasiswa->nim ?? '-' }}</td>
                            </tr>

                            <tr class="bg-gray-50">
                                <th class="py-2 px-3 font-medium">Program Studi</th>
                                <td class="py-2 px-3">{{ $penilaian->pkl->mahasiswa->prodi->nama ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th class="py-2 px-3 font-medium">Instansi (Tempat PKL)</th>
                                <td class="py-2 px-3">{{ $penilaian->pkl->pengajuanPkl->tempatPkl->nama_tempat ?? '-' }}</td>
                            </tr>

                            <tr class="bg-gray-50">
                                <th class="py-2 px-3 font-medium">Rata-rata</th>
                                <td class="py-2 px-3">{{ $penilaian->rata_rata }}</td>
                            </tr>

                            <tr>
                                <th class="py-2 px-3 font-medium">Grade</th>
                                <td class="py-2 px-3">{{ $penilaian->grade }}</td>
                            </tr>

                            <tr class="bg-gray-50">
                                <th class="py-2 px-3 font-medium">Tanggal Input</th>
                                <td class="py-2 px-3">{{ \Carbon\Carbon::parse($penilaian->tgl_input)->translatedFormat('d F Y') }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</x-guest-layout>