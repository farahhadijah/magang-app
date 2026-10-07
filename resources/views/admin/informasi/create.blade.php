<x-app-layout>

    <x-slot name="title">
        Tambah Informasi - MagangApp
    </x-slot>

    <div class="p-6">

        <div class="mb-6">
            <a href="{{ route('admin.informasi.index') }}" class="text-sm text-green-600 hover:text-green-800">
                ← Kembali ke Informasi
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Tambah Informasi
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan flowchart, format laporan, atau manual book.
            </p>
        </div>

        <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">

            <form action="{{ route('admin.informasi.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                @include('admin.informasi._form')

            </form>

        </div>

    </div>

</x-app-layout>
