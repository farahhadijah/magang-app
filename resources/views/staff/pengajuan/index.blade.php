<x-app-layout>
    <x-slot name="title">
        Pengajuan Pkl - MagangApp
    </x-slot>

    <div class="py-2 min-h-[70vh] flex flex-col">

        {{-- Flash message --}}
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 mb-6 border rounded-lg shadow-sm text-amber-800 bg-amber-100 border-amber-300">
                {{ session('warning') }}
            </div>
        @endif


        {{-- Jika kosong --}}
        @if ($pengajuans->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Tidak ada pengajuan PKL yang menunggu verifikasi.
                </p>
            </div>
        @else

        {{-- Table --}}
        <div class="overflow-x-auto border border-green-200 rounded-lg shadow-lg">
            <table class="w-full border-collapse min-w-max">
                <thead class="bg-green-100">
                    <tr>
                        <th class="p-3 text-left border">No</th>
                        <th class="p-3 text-left border">Nama Mahasiswa</th>
                        <th class="p-3 text-left border">NIM</th>
                        <th class="p-3 text-left border">Instansi</th>
                        <th class="p-3 text-left border">Status</th>
                        <th class="p-3 text-left border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white">
                    @foreach ($pengajuans as $item)
                        <tr class="transition hover:bg-green-50">
                            <td class="p-3 border">
                                {{ $pengajuans->firstItem() + $loop->index }}
                            </td>

                            <td class="p-3 border">
                                {{ $item->mahasiswa?->nama ?? '-' }}
                            </td>

                            <td class="p-3 border">
                                {{ $item->mahasiswa?->nim ?? '-' }}
                            </td>

                            <td class="p-3 border">
                                {{ $item->tempatPkl?->nama_tempat ?? '-' }}
                            </td>
                            <td class="p-3 border">
                                @php($status = $item->statusLabel())
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            
                            <td class="p-3 border">
                                <a href="{{ route('staff.pengajuan.show', $item->id) }}"
                                   class="px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded hover:bg-green-200">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-center pt-6 mt-auto">
            {{ $pengajuans->links() }}
        </div>

        @endif
    </div>
</x-app-layout>
