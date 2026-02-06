@extends('layouts.dosen')

@section('content')
<h3>Penilaian PKL</h3>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama Mahasiswa</th>
        <th>NIM</th>
        <th>Aksi</th>
    </tr>

    {{-- contoh dummy --}}
    <tr>
        <td>1</td>
        <td>Andi Saputra</td>
        <td>20201234</td>
        <td>
            <a href="{{ route('dosen.penilaian.create', 1) }}">
                Beri Nilai
            </a>
        </td>
    </tr>
</table>
@endsection
