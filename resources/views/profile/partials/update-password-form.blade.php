<section
    class="p-6 bg-white border border-green-200 shadow-sm  rounded-xl"
>

    {{-- HEADER --}}
    <header class="pb-3 mb-6 border-b border-green-200">

        <h2 class="text-lg font-semibold text-green-800">
            Ubah Kata Sandi
        </h2>

        <p class="mt-1 text-sm text-green-600">
            Gunakan kata sandi yang kuat dan sulit ditebak demi keamanan akun kamu.
        </p>

    </header>


    {{-- FORM --}}
    <form method="post"
          action="{{ route('password.update') }}"
          class="space-y-6">

        @csrf
        @method('put')


        {{-- PASSWORD LAMA --}}
        <div>
            <x-input-label
                for="update_password_current_password"
                value="Kata Sandi Saat Ini"
                class="text-green-800"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="block w-full mt-1 border-green-300  focus:border-green-500 focus:ring-green-500"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2 text-red-600"
            />
        </div>


        {{-- PASSWORD BARU --}}
        <div>
            <x-input-label
                for="update_password_password"
                value="Kata Sandi Baru"
                class="text-green-800"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="block w-full mt-1 border-green-300  focus:border-green-500 focus:ring-green-500"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2 text-red-600"
            />
        </div>


        {{-- KONFIRMASI --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                value="Konfirmasi Kata Sandi"
                class="text-green-800"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="block w-full mt-1 border-green-300  focus:border-green-500 focus:ring-green-500"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2 text-red-600"
            />
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
            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700"
                >
                    ✅ Kata sandi berhasil diperbarui.
                </p>

            @endif

        </div>

    </form>

</section>
