<x-app-layout>

    <x-slot name="title">
        Resume PKL - Sibolang
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="overflow-hidden mb-6 bg-white rounded-2xl shadow-sm">
                <div class="px-5 py-5 sm:px-7 sm:py-6">
                    <div class="flex flex-col gap-3 justify-between items-start sm:flex-row sm:items-center">

                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                                Resume PKL Mahasiswa
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Daftar mahasiswa yang telah menyelesaikan kegiatan PKL.
                            </p>
                        </div>

                        <div class="px-3 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full">
                            Total: {{ $angkatan->sum('jumlah_mahasiswa') }} Mahasiswa
                        </div>

                    </div>
                </div>
            </div>

            {{-- Daftar Angkatan --}}
            <div class="overflow-hidden bg-white rounded-2xl shadow-sm">
                <div class="px-5 py-5 sm:px-7 sm:py-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        @forelse($angkatan as $item)
                            <a href="{{ route('mitra.resume.mahasiswa', $item->angkatan) }}"
                                class="block p-5 bg-white rounded-xl border border-gray-200 transition hover:border-green-300 hover:shadow-md">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">
                                            Angkatan
                                        </p>

                                        <h3 class="mt-1 text-2xl font-bold text-gray-900">
                                            {{ $item->angkatan }}
                                        </h3>
                                    </div>

                                    <div
                                        class="flex justify-center items-center w-12 h-12 text-green-700 bg-green-100 rounded-full">
                                        <i class="text-xl fa-solid fa-users"></i>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100">
                                    <span class="text-sm text-gray-500">
                                        Mahasiswa selesai PKL
                                    </span>

                                    <span
                                        class="px-3 py-1 text-sm font-semibold text-green-700 bg-green-50 rounded-full">
                                        {{ $item->jumlah_mahasiswa }}
                                    </span>
                                </div>

                                <div class="flex items-center mt-4 text-sm font-medium text-green-700">
                                    Lihat mahasiswa
                                    <i class="ml-2 fa-solid fa-arrow-right"></i>
                                </div>
                            </a>
                        @empty

                            <div class="col-span-full px-6 py-12 text-center">
                                <div class="flex flex-col gap-3 items-center">
                                    <div
                                        class="flex justify-center items-center w-14 h-14 text-gray-400 bg-gray-100 rounded-full">
                                        <i class="text-xl fa-solid fa-folder-open"></i>
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-700">
                                            Belum Ada Resume PKL
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Belum ada mahasiswa yang telah menyelesaikan PKL.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
