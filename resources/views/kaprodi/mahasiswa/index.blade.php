<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Data Mahasiswa PKL</h2>
    </x-slot>

    <div class="py-6">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">NIM</th>
                    <th class="p-3 border">Prodi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mhs)
                <tr>
                    <td class="p-3 border">{{ $loop->iteration }}</td>
                    <td class="p-3 border">{{ $mhs->nama }}</td>
                    <td class="p-3 border">{{ $mhs->nim }}</td>
                    <td class="p-3 border">{{ $mhs->prodi ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
