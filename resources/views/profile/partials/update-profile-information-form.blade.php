<section
    class="p-6 bg-white border border-green-200 shadow-sm  rounded-xl"
>

    {{-- HEADER --}}
    <header class="pb-3 mb-6 border-b border-green-200">

        <h2 class="text-lg font-semibold text-green-800">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-green-600">
            Perbarui data akun dan email kamu.
        </p>

    </header>


    {{-- FORM VERIFIKASI --}}
    <form id="send-verification"
          method="post"
          action="{{ route('verification.send') }}">
        @csrf
    </form>


    {{-- FORM --}}
    <form method="post"
          action="{{ route('profile.update') }}"
          class="space-y-6">

        @csrf
        @method('patch')


        {{-- NAMA --}}
        <div>
            <x-input-label
                for="name"
                value="Nama Lengkap"
                class="text-green-800"
            />

            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block w-full mt-1 border-green-300  focus:border-green-500 focus:ring-green-500"
                :value="old('name', $user->name)"
                required
                autofocus
            />

            <x-input-error
                class="mt-2 text-red-600"
                :messages="$errors->get('name')"
            />
        </div>


        {{-- EMAIL --}}
        <div>
            <x-input-label
                for="email"
                value="Email"
                class="text-green-800"
            />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="block w-full mt-1 border-green-300  focus:border-green-500 focus:ring-green-500"
                :value="old('email', $user->email)"
                required
            />

            <x-input-error
                class="mt-2 text-red-600"
                :messages="$errors->get('email')"
            />


            {{-- BELUM VERIF --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                <div
                    class="p-3 mt-3 border rounded-lg  border-amber-200 bg-amber-50"
                >

                    <p class="text-sm text-amber-800">
                        ⚠️ Email belum diverifikasi.
                    </p>

                    <button
                        form="send-verification"
                        class="mt-1 text-sm text-green-700 underline  hover:text-green-900"
                    >
                        Kirim ulang email verifikasi
                    </button>


                    @if (session('status') === 'verification-link-sent')

                        <p class="mt-2 text-sm font-medium text-green-700">
                            ✅ Link verifikasi berhasil dikirim.
                        </p>

                    @endif

                </div>

            @endif
        </div>


        {{-- BUTTON --}}
        <div class="flex items-center gap-4">

            <button
                type="submit"
                class="px-5 py-2 font-medium text-white transition bg-green-700 rounded-lg  hover:bg-green-800"
            >
                Simpan Perubahan
            </button>


            {{-- STATUS --}}
            @if (session('status') === 'profile-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700"
                >
                    ✅ Data berhasil disimpan.
                </p>

            @endif

        </div>

    </form>

</section>
