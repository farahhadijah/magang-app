<x-app-layout>
    <x-slot name="title">
        Histori - MagangApp
    </x-slot>

    <div class="py-1 min-h-[70vh] flex flex-col">

        @if($verifikasis->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Belum ada histori verifikasi.
                </p>
            </div>
        @else
            <div class="overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max mobile-table">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">No</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Nama Mahasiswa</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">NIM</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Instansi</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Status</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Catatan</th>
                            <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Tanggal Verifikasi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($verifikasis as $item)
                            <tr class="transition hover:bg-green-50">
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ $item->pengajuan?->mahasiswa?->nama ?? '-' }}
                                </td>
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ $item->pengajuan?->mahasiswa?->nim ?? '-' }}
                                </td>
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ $item->pengajuan?->tempatPkl?->nama_tempat ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    @if($item->status === 'approved')
                                        <span class="font-semibold text-green-800 bg-green-100 rounded-full desktop-badge mobile-badge">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="font-semibold text-red-800 bg-red-100 rounded-full desktop-badge mobile-badge">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- Catatan --}}
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ $item->catatan ?? '-' }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                    {{ \Carbon\Carbon::parse($item->tgl_verifikasi)->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center pt-2 mt-auto">
                {{ $verifikasis->links() }}
            </div>
        @endif

    </div>
    <script>
    (function(){
    // cek viewport via matchMedia (lebih handal), dan hindari loop redirect
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    const url = new URL(window.location.href);
    const currentPerPage = url.searchParams.get('perPage');
    const expectedPerPage = isMobile ? '15' : '9';

    if (currentPerPage !== expectedPerPage) {
        // prevent redirect loop using sessionStorage flag
        if (!sessionStorage.getItem('perPageRedirectDone')) {
        sessionStorage.setItem('perPageRedirectDone', '1');
        url.searchParams.set('perPage', expectedPerPage);
        window.location.replace(url.toString());
        } else {
        // sudah redirect satu kali, hapus flag supaya navigasi berikutnya normal
        sessionStorage.removeItem('perPageRedirectDone');
        }
    }
    })();
    </script>
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