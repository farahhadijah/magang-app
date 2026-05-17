<x-app-layout>
    <x-slot name="title">
        Nilai PKL
    </x-slot>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Nilai PKL dari Mitra
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Hasil penilaian pembimbing lapangan.
                    </p>
                </div>
                @if($pkl && $pkl->penilaianMitra)
                    @php
                        $nilai = $pkl->penilaianMitra;
                    @endphp
                    <div class="p-6">
                        <!-- IDENTITAS -->
                        <div class="mb-6">
                            <h2 class="font-semibold text-gray-800">
                                {{ auth()->user()->mahasiswa->nama }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ auth()->user()->mahasiswa->nim }}
                            </p>
                        </div>
                        <!-- TABEL NILAI -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200">
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kedisiplinan
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kedisiplinan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kreativitas
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kreativitas }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Ketekunan
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->ketekunan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kerjasama
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kerjasama }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kejujuran
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kejujuran }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kesopanan
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kesopanan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Semangat Kerja
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->semangat_kerja }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            Kedalaman Materi
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $nilai->kedalaman_materi }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- RATA RATA -->
                        <div class="mt-6">
                            <div class="text-lg font-bold text-gray-800">
                                Nilai Akhir:
                                {{ $nilai->rata_rata }}
                                ({{ $nilai->grade }})
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                Diinput:
                                {{ \Carbon\Carbon::parse($nilai->tgl_input)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <!-- DOKUMEN -->
                        <div class="mt-8 flex flex-wrap gap-3">
                            @if($nilai->file_pdf)
                                <a
                                    href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                    target="_blank"
                                    class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Lihat PDF Penilaian
                                </a>
                            @endif
                            @if($nilai->file_scan)
                                <a
                                    href="{{ asset('storage/' . $nilai->file_scan) }}"
                                    target="_blank"
                                    class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                    Lihat Scan TTD
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-10 text-center text-gray-500">
                        Penilaian dari mitra belum tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>