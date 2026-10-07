@if ($manualBook)
    <div x-data="informasiModal"
        class="z-[99999999999999999] relative p-5 bg-white rounded-xl border border-gray-200 shadow-sm mt-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">

            <div class="flex gap-4 items-center">
                <div class="flex flex-shrink-0 justify-center items-center w-12 h-12 text-white bg-green-700 rounded-xl">
                    <i class="text-xl fa-solid fa-book-open"></i>
                </div>

                <div>
                    <h2 class="text-base font-bold text-gray-800">
                        Manual Book
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Panduan penggunaan sistem Sibolang
                    </p>
                </div>
            </div>

            <button type="button"
                @click="openModal(
                    '{{ Storage::url($manualBook->file_path) }}',
                    @js($manualBook->judul)
                )"
                class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-semibold text-white bg-green-700 rounded-lg transition hover:bg-green-600">
                <i class="fa-solid fa-book-open"></i>
                Buka Manual Book
            </button>

        </div>

        {{-- MODAL --}}
        <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal()"
            class="flex fixed inset-0 z-50 justify-center items-center p-4">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60" @click="closeModal()"></div>

            {{-- Card Modal --}}
            <div x-show="isOpen" x-transition
                class="relative z-10 flex flex-col w-full max-w-5xl max-h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl">

                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <div class="pr-4">
                        <h2 class="text-lg font-bold text-gray-800" x-text="title"></h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Manual Book Sibolang
                        </p>
                    </div>

                    <button type="button" @click="closeModal()"
                        class="flex justify-center items-center w-9 h-9 text-gray-500 rounded-full transition hover:bg-gray-100 hover:text-gray-700">
                        <i class="text-lg fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Isi --}}
                <div class="overflow-auto bg-gray-100">

                    {{-- PDF --}}
                    <template x-if="isPdf()">
                        <iframe :src="fileUrl" class="w-full h-[75vh]" frameborder="0"></iframe>
                    </template>

                    {{-- IMAGE --}}
                    <template x-if="isImage()">
                        <div class="flex justify-center items-center p-6 min-h-[50vh]">
                            <img :src="fileUrl" :alt="title"
                                class="object-contain max-w-full max-h-[75vh] rounded-lg">
                        </div>
                    </template>

                    {{-- OFFICE --}}
                    <template x-if="isOffice()">
                        <iframe :src="viewerSrc()" class="w-full h-[75vh]" frameborder="0"></iframe>
                    </template>

                    {{-- FILE TIDAK DIDUKUNG --}}
                    <template x-if="isUnknown()">
                        <div class="flex flex-col justify-center items-center p-10 min-h-[300px] text-center">
                            <i class="mb-4 text-5xl text-gray-400 fa-solid fa-file"></i>

                            <p class="text-gray-600">
                                File tidak dapat ditampilkan langsung.
                            </p>

                            <a :href="fileUrl" target="_blank"
                                class="px-5 py-2 mt-4 text-sm font-semibold text-white rounded-lg"
                                style="background-color: #1f8a1b;">
                                Buka File
                            </a>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>
@endif
