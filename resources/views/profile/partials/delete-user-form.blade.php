<section
    class="p-6 space-y-6 bg-white border border-red-200 shadow-sm  rounded-xl"
>

    {{-- HEADER --}}
    <header class="pb-3 border-b border-red-200">

        <h2 class="text-lg font-semibold text-red-700">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-red-600">
            Jika akun kamu dihapus, seluruh data dan riwayat akan
            <span class="font-semibold">hilang secara permanen</span>.
            Pastikan kamu sudah menyimpan data penting terlebih dahulu.
        </p>

    </header>


    {{-- BUTTON OPEN MODAL --}}
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600  hover:bg-red-700 focus:ring-red-500"
    >
        <i class="mr-1 fa-solid fa-trash"></i>
        Hapus Akun
    </x-danger-button>


    {{-- MODAL --}}
    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >

        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="p-6 space-y-6"
        >

            @csrf
            @method('delete')


            {{-- TITLE --}}
            <div>

                <h2 class="text-lg font-semibold text-red-700">
                    Konfirmasi Penghapusan Akun
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Untuk melanjutkan penghapusan akun,
                    silakan masukkan kata sandi kamu sebagai konfirmasi.
                </p>

            </div>


            {{-- PASSWORD --}}
            <div>

                <x-input-label
                    for="password"
                    value="Kata Sandi"
                    class="text-red-700"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full mt-1 border-red-300  focus:border-red-500 focus:ring-red-500"
                    placeholder="Masukkan kata sandi"
                />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2 text-red-600"
                />

            </div>


            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-200">

                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="hover:bg-gray-100"
                >
                    Batal
                </x-secondary-button>


                <x-danger-button
                    class="bg-red-600  hover:bg-red-700 focus:ring-red-500"
                >
                    Ya, Hapus Akun
                </x-danger-button>

            </div>

        </form>

    </x-modal>

</section>
