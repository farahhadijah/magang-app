<x-app-layout>
    <x-slot name="title">
        Tugas Mahasiswa - MagangApp
    </x-slot>

<div class="px-3 py-6 mx-auto space-y-6 max-w-7xl sm:px-4">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-lg font-bold text-gray-800 sm:text-xl">
            Daftar Tugas Mahasiswa
        </h2>

        <a href="{{ route('mitra.tugas.create') }}"
           class="px-4 py-2 text-sm font-medium text-center text-white bg-green-500 rounded-lg hover:bg-green-600">
            + Buat Tugas
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="p-3 text-sm text-green-800 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Tugas (Desktop) --}}
    @if($tugas->isEmpty())
        <div class="p-6 text-center text-gray-600 bg-white rounded-lg shadow">
            Belum ada tugas yang dibuat.
        </div>
    @else
        <div class="hidden overflow-hidden bg-white rounded-lg shadow md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs divide-y divide-gray-200 sm:text-sm">

                    <thead class="bg-green-100">
                        <tr>
                            <th class="px-3 py-3 text-left text-gray-700 sm:px-6">No</th>
                            <th class="px-3 py-3 text-left text-gray-700 sm:px-6">Mahasiswa</th>
                            <th class="px-3 py-3 text-left text-gray-700 sm:px-6">Judul Tugas</th>
                            <th class="px-3 py-3 text-left text-gray-700 sm:px-6">Deadline</th>
                            <th class="px-3 py-3 text-left text-gray-700 sm:px-6">Status</th>
                            <th class="px-3 py-3 text-center text-gray-700 sm:px-6">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tugas as $index => $item)
                            <tr class="hover:bg-gray-50">

                                <td class="px-3 py-3 text-[0.6rem] md:text-xs text-gray-700 sm:px-6">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-3 py-3 text-[0.6rem] md:text-xs font-semibold text-gray-900 sm:px-6">
                                    {{ $item->pkl->mahasiswa->nama ?? '-' }}
                                </td>

                                <td class="px-3 py-3 text-[0.6rem] md:text-xs text-gray-700 sm:px-6">
                                    {{ $item->judul }}
                                </td>

                                <td class="px-3 py-3 text-[0.6rem] md:text-xs text-gray-700 sm:px-6">
                                    {{ $item->deadline 
                                        ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') 
                                        : '-' }}
                                </td>

                                <td class="px-3 py-3 sm:px-6">
                                    @php
                                        $submit = $item->submit->first();
                                    @endphp

                                    @if(!$submit)

                                    <span class="px-3 py-1 text-[0.6rem] md:text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Belum Dikumpulkan
                                    </span>

                                    @elseif($submit->status == 'pending' && !$submit->revisi)

                                    <span class="px-3 py-1 text-[0.6rem] md:text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                        Pending
                                    </span>

                                    @elseif($submit->revisi)

                                    <span class="px-3 py-1 text-[0.6rem] md:text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Revisi
                                    </span>

                                    @elseif($submit->status == 'selesai')

                                    <span class="px-3 py-1 text-[0.6rem] md:text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Selesai
                                    </span>

                                    {{-- @endif --}}
                                    @else
                                        <span class="px-3 py-1 text-[0.6rem] md:text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                            Belum Dikumpulkan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-center sm:px-6">

                                    <div class="flex items-center justify-center gap-2">

                                        @php $isFinished = optional($item->pkl)->status === 'selesai'; @endphp

                                        <a href="{{ route('mitra.tugas.show', $item->id) }}"
                                        class="px-3 py-1 text-xs text-white {{ $isFinished ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }} rounded text-[0.6rem] md:text-xs"
                                        {{ $isFinished ? 'aria-disabled=true tabindex=-1' : '' }}>
                                            Lihat
                                        </a>

                                        @if($isFinished)
                                            <button
                                                class="px-3 py-1 text-xs text-white bg-gray-400 rounded cursor-not-allowed text-[0.6rem] md:text-xs"
                                                disabled>
                                                Edit
                                            </button>

                                            <button
                                                class="px-3 py-1 text-xs text-white bg-gray-400 rounded cursor-not-allowed text-[0.6rem] md:text-xs"
                                                disabled>
                                                Hapus
                                            </button>
                                        @else
                                            <a href="{{ route('mitra.tugas.edit', $item->id) }}"
                                            class="px-3 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600 text-[0.6rem] md:text-xs">
                                                Edit
                                            </a>

                                            <form action="{{ route('mitra.tugas.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus tugas ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-3 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600 text-[0.6rem] md:text-xs">
                                                    Hapus
                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- MOBILE CARD - RESPONSIVE VERSION --}}
        <div class="space-y-3 md:hidden">
            @foreach($tugas as $index => $item)
                @php
                    $submit = $item->submit->first();

                    if (!$submit) {
                        $statusClass = 'text-red-800 bg-red-100';
                    } elseif ($submit->status == 'pending' && ! $submit->revisi) {
                        $statusClass = 'text-yellow-800 bg-yellow-100';
                    } elseif ($submit->revisi) {
                        $statusClass = 'text-red-800 bg-red-100';
                    } elseif ($submit->status == 'selesai') {
                        $statusClass = 'text-green-800 bg-green-100';
                    } else {
                        $statusClass = 'text-red-800 bg-red-100';
                    }
                @endphp
                
                <div class="p-4 bg-white border border-gray-100 rounded-lg shadow">

                    {{-- Header: Nomor dan Nama Mahasiswa --}}
                    <div class="pb-2 mb-3 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900">
                                    {{ $item->judul }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $item->pkl->mahasiswa->nama ?? '-' }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded">
                                #{{ $index + 1 }}
                            </span>
                        </div>
                    </div>

                    {{-- Body: Informasi Tugas --}}
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Deadline</span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $item->deadline 
                                    ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') 
                                    : '-' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Status</span>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusClass }}">
                                @if(!$submit)
                                    Belum Dikumpulkan
                                @elseif($submit->status == 'pending' && !$submit->revisi)
                                    Pending
                                @elseif($submit->revisi)
                                    Revisi
                                @elseif($submit->status == 'selesai')
                                    Selesai
                                @else
                                    Belum Dikumpulkan
                                @endif
                            </span>
                        </div>

                        {{-- Tambahan info jika ada revisi atau pending --}}
                        @if($submit && $submit->revisi && $submit->catatan_revisi)
                            <div class="p-2 mt-2 border border-red-100 rounded bg-red-50">
                                <p class="text-xs font-medium text-red-700">Catatan Revisi:</p>
                                <p class="text-xs text-red-600 mt-0.5">{{ $submit->catatan_revisi }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route('mitra.tugas.show', $item->id) }}"
                           class="flex-1 py-2 text-sm font-medium text-center text-white transition bg-blue-600 rounded hover:bg-blue-700">
                            Lihat
                        </a>

                        @if($submit && $submit->status == 'selesai')
                            <button class="flex-1 py-2 text-sm font-medium text-center text-white bg-gray-400 rounded cursor-not-allowed" disabled>
                                Edit
                            </button>
                        @else
                            <a href="{{ route('mitra.tugas.edit', $item->id) }}"
                               class="flex-1 py-2 text-sm font-medium text-center text-white transition bg-yellow-500 rounded hover:bg-yellow-600">
                                Edit
                            </a>
                        @endif

                        <form action="{{ route('mitra.tugas.destroy', $item->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Hapus tugas ini?')" 
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full py-2 text-sm font-medium text-center text-white transition bg-red-500 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>

</x-app-layout>