<section class="p-6 bg-white rounded-xl border border-green-200 shadow-sm">

    @php
        /** @var \App\Models\User $user */
        $user = auth()->user();
    @endphp

    {{-- HEADER --}}
    <header class="pb-3 mb-6 border-b border-green-200">
        <h2 class="text-lg font-semibold text-green-800">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-green-600">
            @if ($user->canEditEmail())
                Perbarui email akun kamu.
            @else
                Informasi akun kamu.
            @endif
        </p>
    </header>


    {{-- NAMA (READ-ONLY, semua role) --}}
    <div class="mb-6">
        <x-input-label for="nama" value="Nama Lengkap" class="text-green-800" />

        <x-text-input id="nama" type="text"
            class="block mt-1 w-full text-green-900 bg-green-50 border-green-300 cursor-not-allowed" :value="$user->getNama()"
            disabled readonly />

        <p class="mt-1 text-xs text-green-600">
            Nama dikelola oleh admin. Hubungi TU jika perlu perubahan.
        </p>
    </div>


    @if ($user->canEditEmail())

        {{-- ============ FORM EDIT EMAIL ============ --}}
        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">

            @csrf
            @method('patch')

            <div>
                <x-input-label for="email" value="Email" class="text-green-800" />

                <x-text-input id="email" name="email" type="email"
                    class="block mt-1 w-full border-green-300 focus:border-green-500 focus:ring-green-500"
                    :value="old('email', $user->profile_email)" required autocomplete="username" />

                <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

                <p class="mt-1 text-xs text-green-600">
                    Email ini digunakan untuk notifikasi sistem.
                </p>
            </div>

            <div class="flex gap-4 items-center">
                <button type="submit"
                    class="px-5 py-2 font-medium text-white bg-green-700 rounded-lg transition hover:bg-green-800">
                    Simpan Perubahan
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-700">
                        ✅ Data berhasil disimpan.
                    </p>
                @endif
            </div>
        </form>
    @else
        {{-- ============ INFO: TIDAK PUNYA EMAIL ============ --}}
        <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">

            <p class="text-sm font-medium text-amber-800">
                ⚠️ Email tidak dapat diubah dari halaman ini.
            </p>

            <p class="mt-1 text-xs text-amber-700">
                @if ($user->role === 'mitra')
                    Email mitra dikelola melalui data <strong>Tempat PKL</strong> oleh admin.
                @elseif ($user->role === 'pimpinan')
                    Akun pimpinan tidak memiliki email profil. Hubungi admin jika perlu penambahan.
                @else
                    Akun Anda tidak memiliki email profil. Hubungi admin untuk informasi lebih lanjut.
                @endif
            </p>

        </div>

    @endif

</section>
