<x-app-layout>
    <x-slot name="title">
        Remedial Mata Kuliah - Sibolang
    </x-slot>

    <div class="py-4 space-y-6 sm:py-6">

        {{-- HEADER --}}
        <div
            class="overflow-hidden border border-red-200 shadow-sm rounded-2xl bg-gradient-to-r from-red-50 via-white to-white"
        >
            <div class="p-5 sm:p-6">

                <div class="flex items-start gap-4">

                    <div
                        class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-red-600 bg-red-100 rounded-xl"
                    >
                        <i class="text-xl fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div class="flex-1">

                        <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                            Pengajuan Remedial
                        </h1>

                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Sistem mendeteksi bahwa Anda masih memiliki nilai
                            <span class="font-semibold text-red-600">D / E</span>
                            pada beberapa mata kuliah, sehingga pengajuan PKL
                            belum dapat dilakukan.
                        </p>

                    </div>

                </div>

            </div>
        </div>

        {{-- INFORMASI MAHASISWA --}}
        <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-2xl sm:p-6">

            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-6 bg-green-600 rounded-full"></div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Mahasiswa
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-sm text-gray-500">
                        Nama
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $mahasiswa->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        NIM
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Program Studi
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $mahasiswa->prodi->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Angkatan
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $mahasiswa->angkatan }}
                    </p>
                </div>

            </div>

        </div>

        {{-- TABEL NILAI D / E --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">

            <div class="px-5 py-4 border-b border-gray-100 sm:px-6">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Mata Kuliah Remedial
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Data berikut saat ini masih menggunakan simulasi API SIAKAD.
                        </p>
                    </div>

                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full"
                    >
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        Nilai D / E Terdeteksi
                    </div>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                No
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Kode MK
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Mata Kuliah
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                SKS
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Jenis
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Nilai
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Biaya
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach ($matakuliahRemedial as $index => $mk)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ $mk['kode_mk'] }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $mk['nama_mk'] }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $mk['sks'] }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="
                                            inline-flex items-center px-2.5 py-1
                                            text-xs font-medium rounded-full
                                            {{ $mk['jenis'] === 'Praktikum'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-gray-100 text-gray-700' }}
                                        "
                                    >
                                        {{ $mk['jenis'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="
                                            inline-flex items-center justify-center
                                            w-8 h-8 text-sm font-bold rounded-full
                                            {{ $mk['nilai'] === 'E'
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-amber-100 text-amber-700' }}
                                        "
                                    >
                                        {{ $mk['nilai'] }}
                                    </span>

                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <tfoot class="bg-gray-50">

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-4 text-sm font-semibold text-right text-gray-700"
                            >
                                Total Biaya Remedial
                            </td>

                            <td
                                class="px-6 py-4 text-base font-bold text-red-600"
                            >
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

        {{-- ALUR REMEDIAL --}}
        <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-2xl sm:p-6">

            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-6 bg-blue-600 rounded-full"></div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Alur Pengajuan Remedial
                </h2>
            </div>

            <div class="space-y-4">

                <div class="flex gap-4">

                    <div
                        class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold text-white bg-blue-600 rounded-full"
                    >
                        1
                    </div>

                    <div>
                        <p class="font-medium text-gray-800">
                            Download formulir remedial
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Unduh formulir sesuai fakultas Anda melalui tombol di bawah.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div
                        class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold text-white bg-blue-600 rounded-full"
                    >
                        2
                    </div>

                    <div>
                        <p class="font-medium text-gray-800">
                            Lengkapi formulir dan tanda tangan
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Formulir wajib ditandatangani oleh mahasiswa,
                            orang tua, dosen wali akademik, dekan, dan kaprodi.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div
                        class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold text-white bg-blue-600 rounded-full"
                    >
                        3
                    </div>

                    <div>
                        <p class="font-medium text-gray-800">
                            Serahkan ke Tata Usaha
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Setelah lengkap, formulir diserahkan ke Staff TU
                            untuk diteruskan kepada dosen pengampu mata kuliah.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div
                        class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold text-white bg-blue-600 rounded-full"
                    >
                        4
                    </div>

                    <div>
                        <p class="font-medium text-gray-800">
                            Menunggu update nilai dari dosen
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Setelah nilai diperbaiki di SIAKAD,
                            menu pengajuan PKL akan otomatis terbuka kembali.
                        </p>
                    </div>

                </div>

            </div>

        </div>

        {{-- DOWNLOAD FORM --}}
        <div
            class="p-5 border border-green-200 shadow-sm bg-gradient-to-r from-green-50 to-white rounded-2xl sm:p-6"
        >

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-green-800">
                        Formulir Pendaftaran Remedial
                    </h2>

                    <p class="mt-2 text-sm leading-relaxed text-green-700">
                        Download formulir remedial resmi fakultas Anda,
                        kemudian isi dan lengkapi seluruh tanda tangan yang dibutuhkan.
                    </p>

                </div>

                <div class="flex-shrink-0">

                    @if ($formulirRemedial)

                        <a
                            href="{{ asset('storage/' . $formulirRemedial->path_file) }}"
                            target="_blank"
                            class="
                                inline-flex items-center gap-2
                                px-5 py-3
                                text-sm font-medium text-white
                                transition bg-green-600 rounded-xl
                                hover:bg-green-700
                            "
                        >
                            <i class="fa-solid fa-download"></i>

                            Download Formulir
                        </a>

                    @else

                        <div
                            class="px-4 py-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded-xl"
                        >
                            Formulir remedial belum tersedia.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>
</x-app-layout>