@extends('layouts.dosen')

@section('content')
<div class="max-w-3xl py-6 mx-auto space-y-6">

    {{-- Header --}}
    <h2 class="flex items-center gap-2 text-2xl font-bold text-green-900">
        <i class="fa-solid fa-pencil"></i> Form Penilaian PKL
    </h2>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="flex items-center gap-2 p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg shadow">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('dosen.penilaian.store', $mahasiswaId) }}" class="p-6 space-y-6 transition border border-green-200 shadow-lg bg-green-50 rounded-xl hover:shadow-xl">
        @csrf

        {{-- Nilai --}}
        <div class="flex flex-col gap-1">
            <label class="font-medium text-green-800">Nilai (0 - 100)</label>
            <input type="number" name="nilai" min="0" max="100" required
                   class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
            @error('nilai')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        {{-- Catatan --}}
        <div class="flex flex-col gap-1">
            <label class="font-medium text-green-800">Catatan</label>
            <textarea name="catatan" rows="4"
                      class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Nilai
            </button>

            <a href="{{ route('dosen.penilaian.index') }}"
               class="flex items-center gap-2 px-4 py-2 font-medium text-green-800 transition border border-green-300 rounded-lg hover:bg-green-100">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>

</div>
@endsection
