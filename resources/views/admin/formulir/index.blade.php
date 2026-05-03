<x-app-layout>
    <x-slot name="title">
        Formulir - MagangApp
    </x-slot>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-green-800">
                Manajemen Formulir Magang
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola formulir yang dapat diunduh oleh mahasiswa.
            </p>
        </div>

        <a href="{{ route('admin.formulir.create') }}"
           class="px-5 py-2 text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
            + Tambah Formulir
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden bg-white border border-green-100 shadow-md rounded-xl">
        <table class="min-w-full">
            <thead class="text-white bg-green-700">
                <tr>
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-left">Nama Formulir</th>
                    <th class="p-4 text-center">Prodi</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formulir as $index => $f)
                    <tr class="transition border-b hover:bg-green-50">
                        <td class="p-4 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="p-4">
                            {{ $f->nama }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $f->prodi?->nama ?? 'Umum' }}
                        </td>
                        <td class="p-4 text-center">
                            @if($f->is_active)
                                <span class="px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                    Aktif
                                            Manajemen Formulir Dinonaktifkan
                            @else
                                        <p class="mt-1 text-sm text-gray-500">
                                            Fitur manajemen formulir telah dinonaktifkan oleh administrator.
                                </span>
                            @endif
                        </td>
                        <td class="p-4 space-x-2 text-center">
                            <a href="{{ route('admin.formulir.edit',$f->id) }}"
                                    {{-- Manajemen formulir dinonaktifkan --}}
                                Edit
                            </a>

                            <form action="{{ route('admin.formulir.destroy',$f->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Yakin hapus formulir ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-sm text-white transition bg-red-500 rounded-lg shadow hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                                            <tr>
                                                <td colspan="5" class="p-6 text-center text-gray-500">Manajemen formulir telah dinonaktifkan.</td>
                                            </tr>