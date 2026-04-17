<x-app-layout>
    <x-slot name="title">
        Histori - MagangApp
    </x-slot>

    <div class="px-0 py-1 mx-auto max-w-7xl min-h-[70vh] flex flex-col">

        @if ($pengajuans->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Belum ada histori verifikasi kaprodi.
                </p>
            </div>
        @else

            <div class="flex-1 overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max mobile-table">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">No</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">Nama Mahasiswa</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">NIM</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">Instansi</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">Status</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">Catatan Kaprodi</th>
                            <th class="p-3 text-left border desktop-padding mobile-padding desktop-text mobile-text">Tanggal Verifikasi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($pengajuans as $item)

                        @php
                            $verifikasiKaprodi = $item->verifikasi
                                ->where('level', 'kaprodi')
                                ->first();
                        @endphp

                        <tr class="transition hover:bg-green-50">
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $loop->iteration }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $item->mahasiswa?->nama ?? '-' }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $item->mahasiswa?->nim ?? '-' }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $item->tempatPkl?->nama_tempat ?? '-' }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                @if($verifikasiKaprodi)
                                    <span class="px-2 py-1 text-xs font-semibold rounded mobile-badge
                                        {{ $verifikasiKaprodi->status === 'approved'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($verifikasiKaprodi->status) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $verifikasiKaprodi->catatan ?? '-' }}
                            </td>
                            @php
                                $verifikasiKaprodi = $item->verifikasi->first();
                            @endphp

                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $verifikasiKaprodi?->tgl_verifikasi 
                                    ? \Carbon\Carbon::parse($verifikasiKaprodi->tgl_verifikasi)->format('d-m-Y H:i')
                                    : '-' 
                                }}
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-6">
                {{ $pengajuans->links() }}
            </div>

        @endif

    </div>
</x-app-layout>

<style>
    /* Default (Desktop) styles */
    .desktop-padding {
        padding: 0.75rem;
    }
    
    .desktop-text {
        font-size: 1rem;
    }
    
    .mobile-padding {
        /* Default no change for desktop */
    }
    
    .mobile-text {
        /* Default no change for desktop */
    }
    
    .mobile-badge {
        /* Default no change for desktop */
    }
    
    /* Mobile styles (max-width: 768px) */
    @media (max-width: 768px) {
        .desktop-padding {
            padding: 0.5rem;
        }
        
        .desktop-text {
            font-size: 0.75rem;
        }
        
        .mobile-padding {
            padding: 0.27rem;
        }
        
        .mobile-text {
            font-size: 0.8rem;
        }
        
        .mobile-badge {
            padding: 0.125rem 0.375rem;
            font-size: 0.65rem;
        }
        
        /* Optional: Make table container more compact */
        .mobile-table {
            font-size: 0;
        }
    }
    
    /* Extra small devices (max-width: 640px) */
    @media (max-width: 640px) {
        .desktop-padding {
            padding: 0.375rem;
        }
        
        .desktop-text {
            font-size: 0.7rem;
        }
        
        .mobile-padding {
            padding: 0.125rem;
        }
        
        .mobile-text {
            font-size: 0.65rem;
        }
        
        .mobile-badge {
            padding: 0.125rem 0.25rem;
            font-size: 0.6rem;
        }
    }
</style>