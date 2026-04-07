<x-app-layout>
    <x-slot name="title">
        Mahasiswa Aktif - MagangApp
    </x-slot>

    <div class="px-0 py-6 mx-auto max-w-7xl min-h-[70vh] flex flex-col">

        @if ($mahasiswas->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Tidak ada mahasiswa dengan status PKL aktif.
                </p>
            </div>
        @else

            <div class="flex-1 overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max mobile-table">
                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="text-left border desktop-padding mobile-padding desktop-text mobile-text">No</th>
                            <th class="text-left border desktop-padding mobile-padding desktop-text mobile-text">Nama</th>
                            <th class="text-left border desktop-padding mobile-padding desktop-text mobile-text">NIM</th>
                            <th class="text-left border desktop-padding mobile-padding desktop-text mobile-text">Prodi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach($mahasiswas as $mhs)
                        <tr class="transition hover:bg-green-50">
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $mhs->nama }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $mhs->nim }}
                            </td>
                            <td class="border desktop-padding mobile-padding desktop-text mobile-text">
                                {{ $mhs->prodi->nama ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-6">
                {{ $mahasiswas->links() }}
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
    
    /* Mobile styles (max-width: 768px) */
    @media (max-width: 768px) {
        .desktop-padding {
            padding: 0.625rem;  /* 10px */
        }
        
        .desktop-text {
            font-size: 0.875rem;  /* 14px */
        }
        
        .mobile-padding {
            padding: 0.5rem;  /* 8px */
        }
        
        .mobile-text {
            font-size: 0.8rem;  /* 12.8px */
        }
    }
    
    /* Extra small devices (max-width: 640px) */
    @media (max-width: 640px) {
        .desktop-padding {
            padding: 0.5rem;  /* 8px */
        }
        
        .desktop-text {
            font-size: 0.8rem;  /* 12.8px */
        }
        
        .mobile-padding {
            padding: 0.375rem;  /* 6px */
        }
        
        .mobile-text {
            font-size: 0.75rem;  /* 12px */
        }
    }
</style>