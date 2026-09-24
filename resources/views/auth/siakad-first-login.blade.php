<x-guest-layout>

    <x-slot name="title">
        First Login Mahasiswa
    </x-slot>

    <div class="flex flex-col items-center px-0 pt-5 bg-green-50">

        <div class="p-8 w-full max-w-xl bg-white rounded-xl border border-green-200 shadow-lg">

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-green-900">
                    Lengkapi Data Mahasiswa
                </h2>

                <p class="mt-2 text-sm text-green-700">
                    Silakan lengkapi data sebelum menggunakan Sibolang.
                </p>
            </div>

            @if ($errors->any())
                <div class="p-4 mb-4 text-red-700 bg-red-50 rounded-lg border border-red-300">
                    <div class="font-semibold">
                        Mohon periksa kembali data yang Anda masukkan:
                    </div>

                    <ul class="mt-2 ml-5 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('siakad.first-login.store') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Nama
                    </label>

                    <input type="text" value="{{ $mahasiswa['nama'] }}"
                        class="mt-1 w-full bg-gray-100 rounded-lg border" readonly>
                </div>

                {{-- NIM --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        NIM
                    </label>

                    <input type="text" value="{{ $mahasiswa['nim'] }}"
                        class="mt-1 w-full bg-gray-100 rounded-lg border" readonly>
                </div>

                {{-- Angkatan --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Angkatan
                    </label>

                    <input type="text" value="{{ $mahasiswa['angkatan'] }}"
                        class="mt-1 w-full bg-gray-100 rounded-lg border" readonly>
                </div>

                {{-- No HP --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Nomor HP
                    </label>

                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Contoh: 081234567890">

                    @error('no_hp')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Password Baru
                    </label>

                    <input type="password" name="password"
                        class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">

                    @error('password')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-4">
                    <label class="block mb-1 font-medium text-green-800">
                        Konfirmasi Password
                    </label>

                    <input type="password" name="password_confirmation"
                        class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <button type="submit" class="px-4 py-2 w-full text-white bg-green-600 rounded-lg">
                    Simpan & Lanjutkan
                </button>

            </form>

        </div>

    </div>

</x-guest-layout>
