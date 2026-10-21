<x-app-layout>
    <x-slot name="title">
        Manajemen Mitra - SIBOLANG
    </x-slot>

    <div class="px-0 py-6 mx-auto max-w-7xl min-h-[70vh]">

        {{-- SEARCH --}}
        <form method="GET" action="{{ route('staff.manajemen-mitra.index') }}" class="mb-4">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama tempat atau jenis instansi..."
                    class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-1 fa-solid fa-search"></i> Cari
                </button>
                @if (!empty($search))
                    <a href="{{ route('staff.manajemen-mitra.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-hidden bg-white rounded-lg border border-green-200 shadow">

            @if ($mitras->count())

                {{-- DESKTOP --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-100">
                            <tr>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Tempat PKL</th>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Email</th>
                                <th class="px-6 py-3 text-sm font-semibold text-left text-gray-700">Username</th>
                                <th class="px-6 py-3 text-sm font-semibold text-center text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($mitras as $mitra)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        {{ $mitra->tempatPkl->nama_tempat ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 break-all">
                                        {{ $mitra->tempatPkl->email ?? '— Belum ada —' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-700">
                                        {{ $mitra->user->username ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                                            class="inline-block px-3 py-1.5 text-xs font-medium text-gray-700 bg-white rounded border border-gray-300 hover:bg-gray-50">
                                            <i class="mr-1 fa-solid fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('staff.mitra.kredensial.show', $mitra->id) }}"
                                            class="inline-block px-3 py-1.5 ml-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                            <i class="mr-1 fa-solid fa-key"></i> Kelola Kredensial
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE --}}
                <div class="p-3 space-y-3 md:hidden">
                    @foreach ($mitras as $mitra)
                        <div class="p-4 bg-white rounded-lg border border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900">
                                {{ $mitra->tempatPkl->nama_tempat ?? '-' }}</h3>
                            <p class="mt-1 text-xs text-gray-500 break-all">Email: {{ $mitra->tempatPkl->email ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-500 break-all">Username: {{ $mitra->user->username ?? '-' }}
                            </p>

                            <div class="grid grid-cols-2 gap-2 mt-3">
                                <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                                    class="py-2 text-xs font-medium text-center text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50">
                                    <i class="mr-1 fa-solid fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('staff.mitra.kredensial.show', $mitra->id) }}"
                                    class="py-2 text-xs font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <i class="mr-1 fa-solid fa-key"></i> Kredensial
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col justify-center items-center px-6 py-16 text-center">
                    <div class="flex justify-center items-center mb-4 w-16 h-16 text-gray-400 bg-gray-100 rounded-full">
                        <i class="text-2xl fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">Belum Ada Mitra</h3>
                    <p class="mt-2 max-w-md text-sm text-gray-500">
                        {{ !empty($search) ? 'Tidak ada mitra yang cocok dengan pencarian.' : 'Mitra akan muncul otomatis setelah pengajuan PKL disetujui Kaprodi.' }}
                    </p>
                </div>
            @endif

            @if ($mitras->hasPages())
                <div class="flex justify-center p-4 border-t border-gray-100">
                    {{ $mitras->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
