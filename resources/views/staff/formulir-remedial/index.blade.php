<x-app-layout>
    <x-slot name="title">
        Formulir Remedial - Sibolang
    </x-slot>

    <div x-data="pdfViewer" class="py-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-800">
                Formulir Remedial
            </h1>

            <p class="text-sm text-gray-500">
                Kelola formulir pendaftaran remedial berdasarkan fakultas.
            </p>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="px-4 py-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM UPLOAD --}}
        <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">

            <h2 class="mb-4 text-lg font-semibold text-gray-800">
                Upload Formulir
            </h2>

            <form
                action="{{ route('staff.formulir-remedial.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="grid grid-cols-1 gap-4 md:grid-cols-2"
            >
                @csrf

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Fakultas
                    </label>

                    <input
                        type="text"
                        value="{{ $fakultas->nama }}"
                        class="w-full bg-gray-100 border-gray-300 rounded-lg"
                        readonly
                    >
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        Nama Formulir
                    </label>

                    <input
                        type="text"
                        name="nama"
                        placeholder="Contoh: Formulir Remedial FT"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                        required
                    >

                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- File --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-700">
                        File PDF
                    </label>

                    <input
                        type="file"
                        name="file"
                        accept="application/pdf"
                        class="w-full border-gray-300 rounded-lg"
                        required
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal ukuran file 2 MB.
                    </p>

                    @error('file')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="md:col-span-2">
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                    >
                        Upload Formulir
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">

            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Formulir
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                No
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Fakultas
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Nama Formulir
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                File
                            </th>

                            <th class="px-6 py-3 text-center font-semibold text-gray-600">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($formulirs as $index => $formulir)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $formulir->fakultas->nama }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $formulir->nama }}
                                </td>

                                <td class="px-6 py-4">
                                    <button
                                        type="button"
                                        @click="openModal(@js(asset('storage/' . $formulir->path_file)))"
                                        class="text-green-600 hover:underline"
                                    >
                                        Lihat PDF
                                    </button>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('staff.formulir-remedial.destroy', $formulir->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus formulir ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada formulir remedial.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
        {{-- MODAL PDF --}}
        <div
            x-cloak
            x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click.self="closeModal()"
            @keydown.escape.window="closeModal()"
        >
            <div class="relative w-11/12 bg-white shadow-2xl h-[90vh] rounded-2xl">

                {{-- CLOSE --}}
                <button
                    @click="closeModal()"
                    class="absolute z-10 flex items-center justify-center w-8 h-8 text-white bg-red-600 rounded-full -top-3 -right-3 hover:bg-red-700"
                >
                    ✕
                </button>

                {{-- PDF --}}
                <iframe
                    :src="fileUrl"
                    class="w-full h-full rounded-2xl"
                    frameborder="0"
                ></iframe>
            </div>
        </div>
    </div>
</x-app-layout>