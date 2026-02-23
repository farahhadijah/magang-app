<x-app-layout>
    <x-slot name="title">
        Manajemen Mitra - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-7xl min-h-[70vh] flex flex-col">
        @if(session('success'))
            <div class="p-4 mb-4 border-l-4 border-green-600 bg-green-50">
                <strong>Akun Berhasil Dibuat</strong><br>
                {{ session('success') }}<br>
                <small class="text-red-600">
                    ⚠ Catat password ini sekarang, tidak akan ditampilkan lagi.
                </small>
            </div>
        @endif


        <div class="overflow-hidden bg-white border border-green-200 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Tempat PKL</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Jumlah Mahasiswa</th>
                        <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Status Mitra</th>
                        <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tempatPkls as $tempat)
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $tempat->nama_tempat }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $tempat->jumlah_mahasiswa }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                @if($tempat->mitra)
                                    <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded">
                                        Sudah Ada Mitra
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded">
                                        Belum Ada Mitra
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if(!$tempat->mitra)
                                    <button onclick="document.getElementById('form-{{ $tempat->id }}').classList.toggle('hidden')"
                                        class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Buat Mitra
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @if(!$tempat->mitra)
                        <tr id="form-{{ $tempat->id }}" class="hidden bg-gray-50">
                            <td colspan="4" class="px-6 py-4">
                                <form method="POST" action="{{ route('staff.mitra.store', $tempat->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                                        Buat Akun Mitra Otomatis
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-center pt-6 mt-auto">
                {{ $tempatPkls->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
