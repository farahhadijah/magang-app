<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar PKL</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 40px;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop img {
            position: absolute;
            left: 40px;
            top: 30px;
            width: 80px;
        }

        .kop h2 {
            margin: 0;
        }

        .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }

        .no-border {
            border: none !important;
        }

        .ttd {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="kop">
    <img src="{{ base_path('public/img/unisla.png') }}">
    <h2>UNIVERSITAS ISLAM LAMONGAN</h2>
    <h3>(UNISLA)</h3>
    <p>Jl. Veteran No. 53 A Telp./Fax. (0322) 324706, 317116 Lamongan</p>
    <p>Email : rektorat@unisla.ac.id &nbsp;&nbsp; http://unisla.ac.id</p>
</div>

<table class="no-border">
    <tr>
        <td class="no-border" width="15%">Nomor</td>
        <td class="no-border">: {{ $noSurat }}</td>
        <td class="no-border right">
            Lamongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </td>
    </tr>
    <tr>
        <td class="no-border">Lampiran</td>
        <td class="no-border">: 1 (satu) lembar</td>
        <td class="no-border"></td>
    </tr>
    <tr>
        <td class="no-border">Sifat</td>
        <td class="no-border">: Penting</td>
        <td class="no-border"></td>
    </tr>
    <tr>
        <td class="no-border">Perihal</td>
        <td class="no-border">: Permohonan Praktek Kerja Nyata</td>
        <td class="no-border"></td>
    </tr>
</table>

<br>

<p>
Kepada Yth:<br>
{{ $pengajuan->tempatPkl->nama_tempat }}<br>
No. HP: {{ $pengajuan->tempatPkl->no_hp }}<br>
Di Tempat
</p>

<p>
Assalamu'alaikum Wr. Wb.
</p>

<p>
Dengan hormat,<br>
Dengan ini kami memberitahukan bahwa mahasiswa berikut:
</p>

<table>
    <tr>
        <th>No</th>
        <th>Nama Mahasiswa</th>
        <th>NIM</th>
        <th>Prodi</th>
        <th>No. HP</th>
    </tr>
    <tr>
        <td>1</td>
        <td>{{ $pengajuan->mahasiswa->nama }}</td>
        <td>{{ $pengajuan->mahasiswa->nim }}</td>
        <td>{{ $pengajuan->mahasiswa->prodi->nama }}</td>
        <td>{{ $pengajuan->mahasiswa->no_hp }}</td>
    </tr>
</table>

<p>
Akan melaksanakan PKL selama 60 hari kerja terhitung sejak tanggal disetujuinya surat ini.
</p>

<p>
Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
</p>

<p>
Wassalamu'alaikum Wr. Wb.
</p>

<div class="ttd">
    <p>Rektor,</p>
    <br><br><br>
    <p><b>Dr.H.ABDUL GHOFUR, S.E., M.Si.</b></p>
    <p>NIDN. 0723116803</p>
</div>

</body>
</html>
