<x-guest-layout>

    <x-slot name="title">
        First Login Dosen
    </x-slot>

    <div class="flex flex-col items-center px-0 pt-5 bg-green-50">

        <div class="p-8 w-full max-w-xl bg-white rounded-xl border border-green-200 shadow-lg">

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-green-900">
                    Lengkapi Data Dosen
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

            <form method="POST" action="{{ route('siakad.dosen.first-login.store') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Nama
                    </label>

                    <input type="text" value="{{ $dosen['nama'] }}" class="mt-1 w-full bg-gray-100 rounded-lg border"
                        readonly>
                </div>

                {{-- NIDN --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        NIDN
                    </label>

                    <input type="text" value="{{ $dosen['nidn'] }}" class="mt-1 w-full bg-gray-100 rounded-lg border"
                        readonly>
                </div>

                {{-- Keahlian --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Keahlian <span class="text-sm font-normal text-gray-500">(Opsional)</span>
                    </label>

                    <input type="text" name="keahlian" value="{{ old('keahlian') }}"
                        class="mt-1 w-full rounded-lg border">
                </div>

                {{-- Jabatan --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Jabatan
                    </label>

                    <select name="jabatan" required class="mt-1 w-full rounded-lg border">
                        <option value="dosen" @selected(old('jabatan', $dosen->jabatan ?? 'dosen') === 'dosen')>
                            Dosen
                        </option>

                        <option value="kaprodi" @selected(old('jabatan', $dosen->jabatan) === 'kaprodi')>
                            Kaprodi
                        </option>
                    </select>

                    @error('jabatan')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Nomor HP
                    </label>

                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="mt-1 w-full rounded-lg border">

                    @error('no_hp')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Password Baru
                    </label>

                    <input type="password" name="password" class="mt-1 w-full rounded-lg border">

                    @error('password')
                        <div class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-4">
                    <label class="block font-medium text-green-800">
                        Konfirmasi Password
                    </label>

                    <input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border">
                </div>

                <button type="submit" class="px-4 py-2 w-full text-white bg-green-600 rounded-lg">
                    Simpan & Lanjutkan
                </button>

            </form>

        </div>

    </div>

</x-guest-layout>
