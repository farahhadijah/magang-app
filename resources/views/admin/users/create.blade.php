<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tambah User
        </h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="mb-4">
                <label>Nama</label>
                <input type="text" name="nama" class="w-full border" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border" required>
            </div>

            <div class="mb-4">
                <label>Role</label>
                <select name="role" class="w-full border" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="dosen">Dosen</option>
                    <option value="kaprodi">Kaprodi</option>
                    <option value="staff_tu">Staff TU</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="w-full border" required>
            </div>

            <div class="mb-4">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border" required>
            </div>

            <button class="px-4 py-2 text-white bg-indigo-600">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>
