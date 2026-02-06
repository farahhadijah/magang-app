<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Detail Pengajuan PKL
        </h2>
    </x-slot>

    <div class="py-6 space-y-4">

        <div class="p-6 bg-white rounded shadow">
            <p><strong>Nama:</strong> Budi Santoso</p>
            <p><strong>NIM:</strong> 20201234</p>
            <p><strong>Instansi:</strong> PT Maju Jaya</p>
            <p><strong>Alamat Instansi:</strong> Jakarta</p>
            <p><strong>Periode PKL:</strong> Feb – Mei 2026</p>
        </div>

        <div class="flex gap-4">

            <!-- Approve -->
            <form method="POST" action="{{ route('staff.pengajuan.approve', $id) }}">
                @csrf
                <button
                    class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                    Setujui
                </button>
            </form>

            <!-- Reject -->
            <form method="POST" action="{{ route('staff.pengajuan.reject', $id) }}">
                @csrf
                <input type="text"
                       name="catatan"
                       placeholder="Catatan penolakan"
                       class="p-2 border rounded"
                       required>

                <button
                    class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                    Tolak
                </button>
            </form>

        </div>

        <a href="{{ route('staff.pengajuan.index') }}"
           class="text-gray-600 hover:underline">
            ← Kembali
        </a>
    </div>
</x-app-layout>
