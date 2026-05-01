<x-app-layout>
    <x-slot name="title">
        Prodi - MagangApp
    </x-slot>
<div class="py-6 px-0 sm:px-6 lg:px-8 min-h-screen">

    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                Daftar Prodi & Angkatan
            </h1>
            <p class="text-emerald-600 text-sm">
                Pilih program studi dan angkatan untuk melihat daftar mahasiswa
            </p>
        </div>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4 border border-green-500">
                <p class="text-sm text-gray-600">Total Prodi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $prodi->unique('prodi_id')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border border-green-500">
                <p class="text-sm text-gray-600">Total Angkatan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $prodi->unique('angkatan')->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border border-green-500">
                <p class="text-sm text-gray-600">Total Data</p>
                <p class="text-2xl font-bold text-gray-800">{{ $prodi->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border border-green-500">
                <p class="text-sm text-gray-600">Total Mahasiswa</p>
                <p class="text-2xl font-bold text-green-600">{{ $prodi->sum('jumlah_mahasiswa') }}</p>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="bg-white rounded-lg shadow-sm p-5 mb-8 border border-green-500">
            <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Filter Berdasarkan Angkatan
                    </label>
                    <select name="angkatan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatanList as $a)
                            <option value="{{ $a }}" {{ $angkatan == $a ? 'selected' : '' }}>
                                Angkatan {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 mt-4 sm:mt-6">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm hover:shadow-md">
                        Filter
                    </button>
                    @if($angkatan)
                        <a href="{{ request()->url() }}" 
                           class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition shadow-sm hover:shadow-md">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Info Filter Aktif --}}
        @if($angkatan)
        <div class="mb-5 flex items-center gap-2">
            <span class="text-sm text-gray-600">Filter aktif:</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                Angkatan {{ $angkatan }}
            </span>
        </div>
        @endif

        {{-- Grid Card Prodi & Angkatan --}}
        @if($prodi->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($prodi as $p)
            <a href="{{ route('pimpinan.mahasiswa', [$p->prodi_id, $p->angkatan]) }}"
               class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                
                {{-- Top accent border --}}
                <div class="h-1 bg-gradient-to-r from-emerald-400 to-green-500"></div>
                
                {{-- Card Body --}}
                <div class="p-4">
                    {{-- Nama Prodi --}}
                    <div class="mb-1">
                        <h2 class="text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors line-clamp-2">
                            {{ $p->nama_prodi }}
                        </h2>
                    </div>
                    
                    {{-- Angkatan Badge --}}
                    <div class="mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Angkatan {{ $p->angkatan }}
                        </span>
                    </div>
                    
                    {{-- Divider --}}
                    <div class="border-t border-gray-100 my-1"></div>
                    
                    {{-- Jumlah Mahasiswa --}}
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm text-gray-500">Jumlah Mahasiswa</span>
                        <span class="text-xl font-bold text-green-600">
                            {{ number_format($p->jumlah_mahasiswa, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    {{-- Link indicator --}}
                    <div class="mt-1 text-right">
                        <span class="text-xs text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            Lihat detail →
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        {{-- Pagination Placeholder (jika ada) --}}
        @if(method_exists($prodi, 'links'))
        <div class="mt-8">
            {{ $prodi->links() }}
        </div>
        @endif
        
        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="text-gray-400 mb-3">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada data</h3>
            <p class="text-gray-500">
                @if($angkatan)
                    Tidak ditemukan program studi untuk angkatan {{ $angkatan }}
                @else
                    Belum ada program studi dan angkatan yang tersedia
                @endif
            </p>
            @if($angkatan)
            <div class="mt-4">
                <a href="{{ request()->url() }}" class="text-green-600 hover:text-green-700 font-medium">
                    ↺ Reset filter
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>

</div>
</x-app-layout>