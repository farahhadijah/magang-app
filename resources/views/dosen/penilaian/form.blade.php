@extends('layouts.dosen')

@section('content')
<h3>Form Penilaian PKL</h3>

<form method="POST" action="{{ route('dosen.penilaian.store', $mahasiswaId) }}">
    @csrf

    <div>
        <label>Nilai (0 - 100)</label><br>
        <input type="number" name="nilai" min="0" max="100" required>
        @error('nilai')
            <small style="color:red">{{ $message }}</small>
        @enderror
    </div>

    <br>

    <div>
        <label>Catatan</label><br>
        <textarea name="catatan" rows="4"></textarea>
    </div>

    <br>

    <button type="submit">Simpan Nilai</button>
    <a href="{{ route('dosen.penilaian.index') }}">Kembali</a>
</form>
@endsection
