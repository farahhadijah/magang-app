<x-app-layout>

    <x-slot name="title">
        Manajemen Akun Mitra - MagangApp
    </x-slot>

    <div class="px-0 py-6 mx-auto max-w-7xl min-h-[70vh]">

        {{-- INFO --}}
        <div class="p-4 mb-5 bg-green-50 rounded-lg border border-green-200">
            <div class="flex gap-3 items-start">
                <i class="mt-0.5 text-green-600 fa-solid fa-circle-info"></i>

                <div>
                    <h3 class="text-sm font-semibold text-green-800">
                        Daftar Mitra yang Belum Memiliki Akun
                    </h3>

                    <p class="mt-1 text-sm text-green-700">
                        Halaman ini hanya menampilkan tempat PKL yang belum memiliki
                        akun mitra dan membutuhkan pembuatan akun.
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-hidden bg-white rounded-lg border border-green-200 shadow">

            @if ($tempatPkls->count())

                {{-- DESKTOP TABLE --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-green-100">
                            <tr>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">
                                    Tempat PKL
                                </th>

                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">
                                    Jumlah Mahasiswa
                                </th>

                                <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach ($tempatPkls as $tempat)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $tempat->nama_tempat }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $tempat->jumlah_mahasiswa }} mahasiswa
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <button type="button"
                                            onclick="document.getElementById('form-{{ $tempat->id }}').classList.toggle('hidden')"
                                            class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                                            <i class="mr-1 fa-solid fa-user-plus"></i>
                                            Buat Akun
                                        </button>

                                    </td>

                                </tr>

                                {{-- FORM PEMBUATAN AKUN --}}
                                <tr id="form-{{ $tempat->id }}" class="hidden bg-gray-50">
                                    <td colspan="3" class="px-6 py-4">

                                        <div class="flex justify-between items-center">

                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">
                                                    Buat akun mitra?
                                                </p>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    Sistem akan membuat username dan password
                                                    secara otomatis untuk
                                                    <strong>{{ $tempat->nama_tempat }}</strong>.
                                                </p>
                                            </div>

                                            <form method="POST" action="{{ route('staff.mitra.store', $tempat->id) }}">
                                                @csrf

                                                <button type="submit"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                                    <i class="mr-1 fa-solid fa-user-plus"></i>
                                                    Buat Akun Mitra
                                                </button>
                                            </form>

                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- MOBILE CARD --}}
                <div class="p-3 space-y-3 md:hidden">

                    @foreach ($tempatPkls as $tempat)
                        <div class="p-4 bg-white rounded-lg border border-gray-200">

                            {{-- Nama tempat --}}
                            <div class="pb-3 mb-3 border-b border-gray-100">

                                <h3 class="text-base font-semibold text-gray-900">
                                    {{ $tempat->nama_tempat }}
                                </h3>

                            </div>

                            {{-- Jumlah mahasiswa --}}
                            <div class="flex justify-between items-center mb-3">

                                <span class="text-xs text-gray-500">
                                    Mahasiswa
                                </span>

                                <span class="text-sm font-medium text-gray-700">
                                    {{ $tempat->jumlah_mahasiswa }}
                                </span>

                            </div>

                            {{-- Button --}}
                            <button type="button"
                                onclick="document.getElementById('form-mobile-{{ $tempat->id }}').classList.toggle('hidden')"
                                class="py-2 w-full text-sm font-medium text-blue-600 rounded-lg border border-blue-600 transition hover:bg-blue-50">
                                <i class="mr-1 fa-solid fa-user-plus"></i>
                                Buat Akun Mitra
                            </button>

                            {{-- Form --}}
                            <div id="form-mobile-{{ $tempat->id }}" class="hidden mt-3">

                                <div class="p-3 mb-2 bg-gray-50 rounded-lg">

                                    <p class="text-xs text-gray-600">
                                        Sistem akan membuat username dan password
                                        secara otomatis.
                                    </p>

                                </div>

                                <form method="POST" action="{{ route('staff.mitra.store', $tempat->id) }}">
                                    @csrf

                                    <button type="submit"
                                        class="py-2 w-full text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                        <i class="mr-1 fa-solid fa-user-plus"></i>
                                        Buat Akun Mitra
                                    </button>
                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- TIDAK ADA MITRA YANG PERLU DIBUATKAN AKUN --}}
                <div class="flex flex-col justify-center items-center px-6 py-16 text-center">

                    <div
                        class="flex justify-center items-center mb-4 w-16 h-16 text-green-600 bg-green-100 rounded-full">
                        <i class="text-2xl fa-solid fa-circle-check"></i>
                    </div>

                    <h3 class="text-base font-semibold text-gray-800">
                        Semua Mitra Sudah Memiliki Akun
                    </h3>

                    <p class="mt-2 max-w-md text-sm text-gray-500">
                        Tidak ada tempat PKL yang membutuhkan pembuatan akun mitra
                        saat ini.
                    </p>

                </div>

            @endif


            {{-- PAGINATION --}}
            @if ($tempatPkls->hasPages())
                <div class="flex justify-center p-4 border-t border-gray-100">
                    {{ $tempatPkls->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>
