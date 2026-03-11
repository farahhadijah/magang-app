<x-app-layout>
<x-slot name="title">
    Tugas - MagangApp
</x-slot>

<div class="max-w-5xl py-6 mx-auto">

    <h2 class="mb-4 text-xl font-semibold text-gray-800">
        Tugas dari Mitra
    </h2>

    <div class="overflow-hidden bg-white rounded-lg shadow">

        <table class="w-full text-sm text-left">
            <thead class="text-gray-700 bg-gray-100">
                <tr>
                    <th class="px-6 py-3">Judul Tugas</th>
                    <th class="px-6 py-3">Deadline</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

            @forelse($tugas as $t)

                @php
                    $submit = $t->submit->where('id_pkl',$t->id_pkl)->first();
                @endphp

                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $t->judul }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}
                    </td>

                    <td class="px-6 py-4">

                        @if(!$submit)

                            <span class="px-3 py-1 text-xs text-gray-700 bg-gray-200 rounded-full">
                                Belum dikumpulkan
                            </span>

                        @elseif($submit->revisi)

                            <span class="px-3 py-1 text-xs text-red-800 bg-red-200 rounded-full">
                                Revisi
                            </span>

                        @elseif($submit->status == 'pending')

                            <span class="px-3 py-1 text-xs text-yellow-800 bg-yellow-200 rounded-full">
                                Pending
                            </span>

                        @elseif($submit->status == 'selesai')

                            <span class="px-3 py-1 text-xs text-green-800 bg-green-200 rounded-full">
                                Selesai
                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-4 text-center">

                        <a href="{{ route('mahasiswa.tugas.show',$t->id) }}"
                           class="px-3 py-1 text-sm text-white transition bg-blue-600 rounded hover:bg-blue-700">
                            Detail
                        </a>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        Belum ada tugas dari mitra
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>

    </div>

</div>

</x-app-layout>