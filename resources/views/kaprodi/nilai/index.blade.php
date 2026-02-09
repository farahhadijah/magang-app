<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Nilai PKL</h2>
    </x-slot>

    <div class="py-6">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama Mahasiswa</th>
                    <th class="p-3 border">NIM</th>
                    <th class="p-3 border">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pkls as $pkl)
                <tr>
                    <td class="p-3 border">{{ $loop->iteration }}</td>
                    <td class="p-3 border">{{ $pkl->pengajuan->mahasiswa->nama ?? '-' }}</td>
                    <td class="p-3 border">{{ $pkl->pengajuan->mahasiswa->nim ?? '-' }}</td>
                    <td class="p-3 border">{{ $pkl->nilai ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
