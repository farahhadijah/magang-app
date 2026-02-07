<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Detail Pengajuan PKL
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- Informasi Mahasiswa --}}
        <div class="p-6 space-y-2 border border-green-200 rounded-lg shadow-sm bg-green-50">
            <p><strong class="text-primary">Nama:</strong> Budi Santoso</p>
            <p><strong class="text-primary">NIM:</strong> 20201234</p>
            <p><strong class="text-primary">Instansi:</strong> PT Maju Jaya</p>
            <p><strong class="text-primary">Alamat Instansi:</strong> Jakarta</p>
            <p><strong class="text-primary">Periode PKL:</strong> Feb – Mei 2026</p>
        </div>

        {{-- Aksi Approve / Reject --}}
        <div class="flex flex-col gap-4 md:flex-row">

            {{-- Approve --}}
            <form method="POST" action="{{ route('staff.pengajuan.approve', $id) }}">
                @csrf
                <button
                    class="w-full px-6 py-2 font-medium text-white transition bg-green-600 rounded-lg md:w-auto hover:bg-green-700">
                    Setujui
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="{{ route('staff.pengajuan.reject', $id) }}" class="flex flex-col w-full gap-2 md:w-auto">
                @csrf
                <input type="text"
                       name="catatan"
                       placeholder="Catatan penolakan"
                       class="p-2 border rounded-md border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400"
                       required>

                <button
                    class="px-6 py-2 font-medium text-white transition rounded-lg bg-amber-600 hover:bg-amber-700">
                    Tolak
                </button>
            </form>

        </div>

        {{-- Kembali --}}
        <a href="{{ route('staff.pengajuan.index') }}"
           class="font-medium text-green-700 transition hover:text-green-900">
            ← Kembali
        </a>

    </div>
</x-app-layout>
