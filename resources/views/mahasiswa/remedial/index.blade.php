<x-app-layout>
    <x-slot name="title">
        Remedial Mata Kuliah - Sibolang
    </x-slot>

    <div x-data="pdfViewer" class="py-4 space-y-6 sm:py-6">

        {{-- HEADER --}}
        <div class="overflow-hidden border border-red-200 shadow-sm rounded-2xl bg-gradient-to-r from-red-50 via-white to-white">
            <div class="p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 text-red-600 bg-red-100 rounded-xl">
                        <i class="text-xl fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                            Pengajuan Remedial
                        </h1>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Sistem mendeteksi bahwa Anda masih memiliki nilai
                            <span class="font-semibold text-red-600">D / E</span>
                            pada beberapa mata kuliah, sehingga pengajuan PKL belum dapat dilakukan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFORMASI MAHASISWA --}}
        <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-2xl sm:p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                <h2 class="text-lg font-semibold text-gray-800">Informasi Mahasiswa</h2>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-medium text-gray-800 break-words">{{ $mahasiswa->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">NIM</p>
                    <p class="font-medium text-gray-800">{{ $mahasiswa->nim }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Program Studi</p>
                    <p class="font-medium text-gray-800 break-words">{{ $mahasiswa->prodi->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Angkatan</p>
                    <p class="font-medium text-gray-800">{{ $mahasiswa->angkatan }}</p>
                </div>
            </div>
        </div>

        {{-- TABEL NILAI D / E --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100 sm:px-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Mata Kuliah Remedial</h2>
                        <p class="mt-1 text-sm text-gray-500">Data berikut diambil langsung dari sistem SIAKAD.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full w-fit">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        Nilai D / E Terdeteksi
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">Kode MK</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">Mata Kuliah</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">SKS</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">Jenis</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 sm:px-6">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($matakuliahRemedial as $index => $mk)
                            @php
                                $isPraktikum = str_contains(strtolower($mk['NAMAMK']), 'praktikum');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 sm:px-6">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-medium text-gray-700 sm:px-6">{{ $mk['KODEMK'] }}</td>
                                <td class="px-4 py-4 break-words sm:px-6">{{ $mk['NAMAMK'] }}</td>
                                <td class="px-4 py-4 sm:px-6">{{ $mk['SKS'] }}</td>
                                <td class="px-4 py-4 sm:px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $isPraktikum ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $isPraktikum ? 'Praktikum' : 'Teori' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 sm:px-6">
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold rounded-full {{ $mk['NILAI'] === 'E' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $mk['NILAI'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- totalBiaya removed: not used anymore --}}
                </table>
            </div>
        </div>

        {{-- ALUR REMEDIAL --}}
        <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-2xl sm:p-6">
            <div class="flex items-center gap-2 mb-5">
                <h2 class="text-lg font-semibold text-gray-800">Alur Pengajuan Remedial</h2>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                @php
                    $steps = [
                        ['num' => 1, 'title' => 'Download formulir remedial', 'desc' => 'Unduh formulir sesuai fakultas Anda melalui tombol di bawah.'],
                        ['num' => 2, 'title' => 'Lengkapi formulir dan tanda tangan', 'desc' => 'Formulir wajib ditandatangani oleh mahasiswa, orang tua, dosen wali akademik, dekan, dan kaprodi.'],
                        ['num' => 3, 'title' => 'Serahkan ke Tata Usaha', 'desc' => 'Setelah lengkap, formulir diserahkan ke Staff TU untuk diteruskan kepada dosen pengampu mata kuliah.'],
                        ['num' => 4, 'title' => 'Menunggu update nilai dari dosen', 'desc' => 'Setelah nilai diperbaiki di SIAKAD, menu pengajuan PKL akan otomatis terbuka kembali.'],
                    ];
                @endphp
                @foreach ($steps as $step)
                    <div class="flex gap-4 p-4 transition-all bg-gray-50 rounded-xl hover:shadow-md">
                        <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-bold text-white bg-green-600 rounded-full">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $step['title'] }}</p>
                            <p class="mt-1 text-xs text-gray-500 sm:text-sm">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DOWNLOAD FORM --}}
        <div class="p-5 border border-green-200 shadow-sm bg-gradient-to-r from-green-50 to-white rounded-2xl sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-green-800">Formulir Pendaftaran Remedial</h2>
                    <p class="mt-2 text-sm leading-relaxed text-green-700">
                        Download formulir remedial resmi fakultas Anda, kemudian isi dan lengkapi seluruh tanda tangan yang dibutuhkan.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if ($formulirRemedial)
                        <button 
                            @click="openModal('{{ asset('storage/' . $formulirRemedial->path_file) }}')"
                            class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-white transition bg-green-600 rounded-xl hover:bg-green-700"
                        >
                            <i class="fa-solid fa-eye"></i>
                            Lihat Formulir
                        </button>
                    @else
                        <div class="px-4 py-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded-xl">
                            Formulir remedial belum tersedia.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div 
            x-show="isOpen" 
            x-cloak
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black bg-opacity-50"
            @click.away="closeModal()"
        >
            <div class="relative w-full max-w-5xl bg-white rounded-xl shadow-2xl">
                {{-- Header Modal --}}
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Preview Dokumen</h3>
                    <button 
                        @click="closeModal()"
                        class="text-gray-500 transition hover:text-gray-700"
                    >
                        <i class="text-xl fa-solid fa-times"></i>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="p-4">
                    <iframe 
                        :src="fileUrl" 
                        class="w-full border-0 rounded-lg"
                        style="min-height: 70vh"
                        frameborder="0"
                    ></iframe>
                </div>

                {{-- Footer Modal --}}
                <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-xl">
                    <a 
                        :href="fileUrl" 
                        download
                        class="px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700"
                    >
                        <i class="mr-2 fa-solid fa-download"></i>
                        Download
                    </a>
                    <button 
                        @click="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-gray-200 rounded-lg hover:bg-gray-300"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        
        /* Mobile Responsive Table */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            table {
                font-size: 12px;
            }
            td, th {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }
        
        /* Break word untuk teks panjang di mobile */
        @media (max-width: 640px) {
            .break-words {
                word-break: break-word;
                max-width: 150px;
            }
        }
    </style>
    @endpush
</x-app-layout>