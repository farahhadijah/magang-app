<div class="space-y-5">

    {{-- Judul --}}
    <div>
        <label for="judul" class="block mb-1 text-sm font-medium text-gray-700">
            Judul Informasi
        </label>

        <input type="text" id="judul" name="judul" value="{{ old('judul', $informasi->judul ?? '') }}" required
            maxlength="150" placeholder="Contoh: Manual Book Mahasiswa"
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

        @error('judul')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Jenis --}}
    <div>
        <label for="jenis" class="block mb-1 text-sm font-medium text-gray-700">
            Jenis Informasi
        </label>

        <select id="jenis" name="jenis" required
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

            <option value="">-- Pilih Jenis --</option>

            <option value="flowchart" @selected(old('jenis', $informasi->jenis ?? '') === 'flowchart')>
                Flowchart PKL
            </option>

            <option value="format_laporan" @selected(old('jenis', $informasi->jenis ?? '') === 'format_laporan')>
                Format Laporan PKL
            </option>

            <option value="manual_book" @selected(old('jenis', $informasi->jenis ?? '') === 'manual_book')>
                Manual Book
            </option>

        </select>

        @error('jenis')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Target Role --}}
    <div>
        <label for="target_role" class="block mb-1 text-sm font-medium text-gray-700">
            Ditampilkan Untuk
        </label>

        <select id="target_role" name="target_role" required
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

            <option value="">-- Pilih Role --</option>

            <option value="admin" @selected(old('target_role', $informasi->target_role ?? '') === 'admin')>
                Admin
            </option>

            <option value="mahasiswa" @selected(old('target_role', $informasi->target_role ?? '') === 'mahasiswa')>
                Mahasiswa
            </option>

            <option value="dosen" @selected(old('target_role', $informasi->target_role ?? '') === 'dosen')>
                Dosen
            </option>

            <option value="staff_tu" @selected(old('target_role', $informasi->target_role ?? '') === 'staff_tu')>
                Tata Usaha
            </option>

            <option value="mitra" @selected(old('target_role', $informasi->target_role ?? '') === 'mitra')>
                Mitra
            </option>

            <option value="pimpinan" @selected(old('target_role', $informasi->target_role ?? '') === 'pimpinan')>
                Pimpinan
            </option>

        </select>

        <p class="mt-1 text-xs text-gray-500">
            Informasi hanya akan ditampilkan pada dashboard role yang dipilih.
        </p>

        @error('target_role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block mb-1 text-sm font-medium text-gray-700">
            Deskripsi
        </label>

        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi singkat mengenai informasi..."
            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('deskripsi', $informasi->deskripsi ?? '') }}</textarea>

        @error('deskripsi')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- File --}}
    <div>
        <label for="file" class="block mb-1 text-sm font-medium text-gray-700">
            File
        </label>

        <input type="file" id="file" name="file" accept=".pdf,.doc,.docx"
            class="w-full rounded-lg border border-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100">

        <p class="mt-1 text-xs text-gray-500">
            Format: PDF, DOC, atau DOCX. Maksimal 5 MB.
        </p>

        @if (isset($informasi) && $informasi->file_path)
            <p class="mt-2 text-sm text-gray-600">
                File saat ini:
                <a href="{{ Storage::url($informasi->file_path) }}" target="_blank"
                    class="text-green-600 hover:underline">
                    Lihat file
                </a>
            </p>
        @endif

        @error('file')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Status --}}
    <div>
        <label class="inline-flex items-center">

            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $informasi->is_active ?? true))
                class="text-green-600 rounded border-gray-300 focus:ring-green-500">

            <span class="ml-2 text-sm text-gray-700">
                Aktifkan informasi
            </span>

        </label>
    </div>


    {{-- Tombol --}}
    <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">

        <a href="{{ route('admin.informasi.index') }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50">
            Batal
        </a>

        <button type="submit"
            class="px-5 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
            {{ isset($informasi) ? 'Simpan Perubahan' : 'Simpan Informasi' }}
        </button>

    </div>

</div>
