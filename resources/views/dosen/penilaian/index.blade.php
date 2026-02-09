@extends('layouts.dosen')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <h2 class="flex items-center gap-2 text-2xl font-bold text-green-900">
        <i class="fa-solid fa-clipboard-check"></i> Penilaian PKL
    </h2>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="flex items-center gap-2 p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="overflow-x-auto transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-xl">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">No</th>
                    <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Nama Mahasiswa</th>
                    <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">NIM</th>
                    <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- dummy data --}}
                <tr class="transition border-b hover:bg-green-50">
                    <td class="px-4 py-3">1</td>
                    <td class="px-4 py-3 font-medium text-green-800">Andi Saputra</td>
                    <td class="px-4 py-3">20201234</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('dosen.penilaian.create', 1) }}"
                           class="inline-flex items-center gap-2 px-3 py-1 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="fa-solid fa-pencil"></i> Beri Nilai
                        </a>
                    </td>
                </tr>

                {{-- nanti foreach data --}}
            </tbody>
        </table>
    </div>

</div>
@endsection
