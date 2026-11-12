@if ($manualBook)
    {{-- PANGGIL COMPONENT JS DOCUMENT VIEWER --}}
    <div x-data="documentViewer()"
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

        {{-- MODAL PREVIEW MANUAL BOOK --}}
        <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal()"
            class="fixed inset-0 z-[99999] flex justify-center items-center p-0 sm:p-4 bg-black/70 backdrop-blur-sm">

            {{-- Overlay --}}
            <div class="absolute inset-0" @click="closeModal()"></div>

            {{-- Card Modal --}}
            <div x-show="isOpen" x-transition
                class="relative z-10 flex flex-col w-full h-full sm:h-[90vh] max-w-6xl overflow-hidden bg-white sm:rounded-2xl shadow-2xl">

                {{-- Header --}}
                <div class="flex justify-between items-center px-4 py-3 bg-white border-b shrink-0 sm:px-6 sm:py-4">
                    <div class="pr-4">
                        <h2 class="text-base font-bold text-gray-800 sm:text-lg"
                            x-text="title || 'Manual Book Sibolang'"></h2>
                        <p class="mt-1 text-xs text-gray-500">
                            <span x-show="isPdf()">PDF</span>
                            <span x-show="isImage()">Gambar</span>
                            <span x-show="isOffice()">Office</span>
                        </p>
                    </div>

                    <button type="button" @click="closeModal()"
                        class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-gray-500 bg-gray-100 rounded-full transition hover:bg-red-100 hover:text-red-600">
                        <i class="text-lg fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="overflow-hidden flex-1 w-full bg-gray-100">

                    {{-- PDF (MENGGUNAKAN TAG OBJECT AGAR CEPAT & MOBILE FRIENDLY) --}}
                    <template x-if="isPdf()">
                        <object x-bind:data="fileUrl" type="application/pdf" class="w-full h-full border-0">
                            <!-- Fallback -->
                            <div
                                class="flex flex-col justify-center items-center w-full h-full p-6 text-center bg-gray-100">
                                <div class="mb-3 text-4xl text-gray-400">📄</div>
                                <p class="text-sm text-gray-600 sm:text-base mb-4">
                                    Browser Anda mungkin tidak mendukung pratinjau PDF langsung.
                                </p>
                                <a :href="fileUrl" target="_blank"
                                    class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition hover:opacity-90 bg-green-600">
                                    Buka / Unduh PDF
                                </a>
                            </div>
                        </object>
                    </template>

                    {{-- IMAGE --}}
                    <template x-if="isImage()">
                        <div class="flex overflow-auto justify-center items-center w-full h-full p-3 bg-gray-200">
                            <img :src="fileUrl" :alt="title"
                                class="object-contain max-w-full max-h-full rounded-lg shadow-md">
                        </div>
                    </template>

                    {{-- OFFICE --}}
                    <template x-if="isOffice()">
                        <iframe x-bind:src="officeViewerUrl()" class="w-full h-full border-0" allowfullscreen></iframe>
                    </template>

                    {{-- FILE TIDAK DIDUKUNG --}}
                    <template x-if="isUnknown()">
                        <div
                            class="flex flex-col justify-center items-center w-full h-full p-10 text-center bg-gray-100">
                            <div class="flex justify-center items-center mb-4 w-16 h-16 bg-gray-200 rounded-full">
                                <i class="text-2xl text-gray-500 fa-solid fa-file-circle-question"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Dokumen tidak dapat dipreview</h4>
                            <p class="mt-2 max-w-md text-sm text-gray-500">Format dokumen ini tidak mendukung preview
                                langsung.</p>
                            <a :href="fileUrl" target="_blank"
                                class="inline-flex gap-2 items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="fa-solid fa-up-right-from-square"></i>
                                Buka Dokumen
                            </a>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>
@endif
