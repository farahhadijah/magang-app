<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pengajuan PKL
        </h2>
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-6">

        {{-- Notifikasi --}}
        @foreach (['success', 'error'] as $msg)
            @if (session($msg))
                <div class="p-4 rounded {{ $msg === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ session($msg) }}
                </div>
            @endif
        @endforeach

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="p-4 text-red-800 bg-red-100 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- INFO --}}
        <div class="p-4 text-blue-800 rounded bg-blue-50">
            <p class="text-sm">
                Form ini digunakan untuk <b>mengajukan permohonan PKL</b>.
                Pengajuan akan diverifikasi oleh Staff TU sebelum disetujui secara akademik.
            </p>
        </div>

        <form method="POST"
              action="{{ route('mahasiswa.pengajuan.store') }}"
              enctype="multipart/form-data"
              class="p-6 space-y-6 bg-white rounded-lg shadow">

            @csrf

            {{-- DATA TEMPAT PKL --}}
            <h4 class="text-lg font-semibold">Data Tempat PKL</h4>

            <div class="grid gap-4 md:grid-cols-2">
                <input type="text" name="nama_tempat"
                       placeholder="Nama Instansi / Perusahaan"
                       value="{{ old('nama_tempat') }}" required class="input">

                <select name="jenis_tempat" required class="input">
                    <option value="">-- Jenis Instansi --</option>
                    @foreach (['Pemerintah','Sekolah','PT','CV'] as $jenis)
                        <option value="{{ $jenis }}" @selected(old('jenis_tempat')==$jenis)>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="no_hp"
                       placeholder="No HP / Telepon Instansi"
                       value="{{ old('no_hp') }}" required class="input">

                <textarea name="lokasi_maps" rows="2" required class="input"
                          placeholder="Link lokasi Google Maps">{{ old('lokasi_maps') }}</textarea>
            </div>

            {{-- DOKUMEN --}}
            <div>
                <label class="block font-medium">
                    Surat Pengantar / Permohonan PKL <span class="text-red-500">*</span>
                </label>
                <input type="file" name="dokumen" required class="mt-1">

                <p class="mt-1 text-xs text-gray-500">
                    File ini akan diverifikasi oleh Staff TU (PDF/DOC).
                </p>
            </div>

            {{-- TOMBOL --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn-secondary">
                    Kembali
                </a>

                <button type="submit"
                        class="btn-primary"
                        onclick="this.disabled=true;this.innerText='Mengirim...';this.form.submit();">
                    Ajukan PKL
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
