<x-app-layout>
    <x-slot name="title">
        Mahasiswa Belum Pkl - MagangApp
    </x-slot>

<div class="px-0 py-6">
    {{-- Card --}}
    <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

        @if($mahasiswas->count() > 0)

        <div class="overflow-x-auto border border-green-200">
            <table class="min-w-full mobile-table">
                <thead class="bg-green-100 text-slate-800">
                    <tr>
                        <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">No</th>
                        <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">NIM</th>
                        <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Nama</th>
                        <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Prodi</th>
                        <th class="text-center border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Status Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    @foreach($mahasiswas as $index => $mhs)
                    <tr class="transition hover:bg-green-50">
                        <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                            {{ $mahasiswas->firstItem() + $index }}
                        </td>
                        <td class="font-medium border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                            {{ $mhs->nim }}
                        </td>
                        <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                            {{ $mhs->nama }}
                        </td>
                        <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                            {{ $mhs->prodi->nama ?? '-' }}
                        </td>
                        <td class="text-center border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                            @php
                                $last = $mhs->pengajuanPkl->sortByDesc('created_at')->first();
                            @endphp

                            @if($last)
                                <span class="px-3 py-1 font-semibold text-red-600 bg-red-100 rounded-full mobile-badge desktop-badge">
                                    {{ str_replace('_', ' ', $last->status) }}
                                </span>
                            @else
                                <span class="px-3 py-1 font-semibold text-gray-600 bg-gray-100 rounded-full mobile-badge desktop-badge">
                                    Belum Pernah Mengajukan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $mahasiswas->links() }}
        </div>

        @else

        {{-- Empty State --}}
        <div class="p-10 text-center">
            <div class="mb-4 text-5xl text-green-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-700">
                Semua Mahasiswa Sudah Mengajukan PKL 
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Tidak ada mahasiswa yang belum mengajukan.
            </p>
        </div>

        @endif

    </div>
</div>
</x-app-layout>

<style>
    /* Default (Desktop) styles */
    .desktop-padding-th {
        padding: 0.75rem 1rem;
    }
    
    .desktop-padding-td {
        padding: 0.75rem 1rem;
    }
    
    .desktop-text-th {
        font-size: 1rem;
    }
    
    .desktop-text-td {
        font-size: 0.875rem;
    }
    
    .desktop-badge {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }
    
    /* Mobile styles (max-width: 768px) */
    @media (max-width: 768px) {
        .desktop-padding-th {
            padding: 0.625rem 0.75rem;
        }
        
        .desktop-padding-td {
            padding: 0.625rem 0.75rem;
        }
        
        .desktop-text-th {
            font-size: 0.875rem;
        }
        
        .desktop-text-td {
            font-size: 0.8rem;
        }
        
        .mobile-padding-th {
            padding: 0.5rem 0.625rem;
        }
        
        .mobile-padding-td {
            padding: 0.5rem 0.625rem;
        }
        
        .mobile-text-th {
            font-size: 0.8rem;
        }
        
        .mobile-text-td {
            font-size: 0.75rem;
        }
        
        .mobile-badge {
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
        }
    }
    
    /* Extra small devices (max-width: 640px) */
    @media (max-width: 640px) {
        .desktop-padding-th {
            padding: 0.5rem 0.625rem;
        }
        
        .desktop-padding-td {
            padding: 0.5rem 0.625rem;
        }
        
        .desktop-text-th {
            font-size: 0.8rem;
        }
        
        .desktop-text-td {
            font-size: 0.75rem;
        }
        
        .mobile-padding-th {
            padding: 0.375rem 0.5rem;
        }
        
        .mobile-padding-td {
            padding: 0.375rem 0.5rem;
        }
        
        .mobile-text-th {
            font-size: 0.75rem;
        }
        
        .mobile-text-td {
            font-size: 0.7rem;
        }
        
        .mobile-badge {
            padding: 0.15rem 0.375rem;
            font-size: 0.65rem;
        }
    }
</style>