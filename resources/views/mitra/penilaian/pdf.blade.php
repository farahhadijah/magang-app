<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: serif;
            font-size: 14px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid black;
            padding:8px;
        }

        .no-border td{
            border:none;
            padding:2px;
        }

        .center{
            text-align:center;
        }

    </style>

</head>
@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp
<body>

    <h2 class="center">
        NILAI UJIAN INDIVIDU
    </h2>

    <br>

    <table class="no-border">

        <tr>
            <td width="200">Nama / NIM</td>
            <td>
                :
                {{ $pkl->mahasiswa->nama }}
                /
                {{ $pkl->mahasiswa->nim }}
            </td>
        </tr>

        <tr>
            <td>Program Studi</td>
            <td>
                :
                {{ $pkl->mahasiswa->prodi->nama ?? 'Teknik Informatika' }}
            </td>
        </tr>

    </table>

    <br><br>

    <table>

        <thead>

            <tr>
                <th width="40">No</th>
                <th>Aspek</th>
                <th width="120">Penilaian</th>
            </tr>

        </thead>

        <tbody>

            <tr>
                <td>1</td>
                <td>Kedisiplinan</td>
                <td class="center">{{ $penilaian->kedisiplinan }}</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Kreativitas</td>
                <td class="center">{{ $penilaian->kreativitas }}</td>
            </tr>

            <tr>
                <td>3</td>
                <td>Ketekunan</td>
                <td class="center">{{ $penilaian->ketekunan }}</td>
            </tr>

            <tr>
                <td>4</td>
                <td>Kerjasama</td>
                <td class="center">{{ $penilaian->kerjasama }}</td>
            </tr>

            <tr>
                <td>5</td>
                <td>Kejujuran</td>
                <td class="center">{{ $penilaian->kejujuran }}</td>
            </tr>

            <tr>
                <td>6</td>
                <td>Kesopanan</td>
                <td class="center">{{ $penilaian->kesopanan }}</td>
            </tr>

            <tr>
                <td>7</td>
                <td>Semangat Kerja</td>
                <td class="center">{{ $penilaian->semangat_kerja }}</td>
            </tr>

            <tr>
                <td>8</td>
                <td>Kedalaman Materi</td>
                <td class="center">{{ $penilaian->kedalaman_materi }}</td>
            </tr>

        </tbody>

    </table>

    <br>

    <h3>
        Nilai Rata-Rata :
        {{ $penilaian->rata_rata }}
        ({{ $penilaian->grade }})
    </h3>

    <br><br><br>

    <div style="width:300px; float:right; text-align:center;">

        Lamongan,
        {{ now()->translatedFormat('d F Y') }}

        <br><br>

        Pembimbing Lapangan

        <br><br>

        <img
            src="data:image/svg+xml;base64,{{ base64_encode(
                QrCode::format('svg')
                    ->size(120)
                    ->generate(
                        route(
                            'verifikasi.penilaian',
                            $penilaian->verification_token
                        )
                    )
            ) }}"
            width="120"
        >

        <br><br>

        <div style="font-size:12px;">
            Scan QR untuk verifikasi dokumen
        </div>

        <br><br>

        __________________

    </div>

</body>
</html>