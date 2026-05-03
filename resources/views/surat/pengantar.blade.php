<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Permohonan PKL</title>

<style>

@page {
    size: 210mm 330mm;
    margin: 15mm 20mm 20mm 20mm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 10.5pt;
    margin: 0;
}

/* ================= KOP ================= */
.kop table {
    width: 100%;
}

.logo {
    width: 18%;
    text-align: center;
}

.logo img {
    width: 100px;
}

.text {
    width: 82%;
    text-align: center;
    line-height: 1.3;
}

.text h2 {
    font-size: 15pt;
    margin: 0;
}

.text h1 {
    font-size: 22pt;
    margin: 0;
}

.text .sk {
    font-size: 10pt;
    margin: 2px 0;
}

.text .sub {
    font-size: 9.5pt;
    margin: 0;
}

/* ================= GARIS ================= */
.garis-tebal {
    border-top: 3px solid black;
    margin: 6px 0 2px 0;
}

.garis-tipis {
    border-top: 1px solid black;
    margin-bottom: 12px;
}

/* ================= HEADER ================= */
.header-surat {
    position: relative;
    line-height: 1.6;
}

.tanggal {
    position: absolute;
    right: 0;
    top: 0;
}

/* ================= ISI ================= */
.isi p {
    margin: 7px 0;
    text-align: justify;
    line-height: 1.6;
}

/* BIODATA */
.biodata td {
    padding: 2px 0;
    vertical-align: top;
}

/* ================= TTD ================= */
.ttd {
    width: 40%;
    margin-left: auto;
    margin-top: 50px; /* pas, tidak terlalu jauh */
    text-align: center;
}

</style>
</head>

<body>

<!-- KOP -->
<div class="kop">
<table>
<tr>
<td class="logo">
    <img src="{{ base_path('public/img/logounisla.png') }}">
</td>
<td class="text">
    <h2>UNIVERSITAS ISLAM LAMONGAN</h2>
    <h1>(UNISLA)</h1>
    <p class="sk">SK. Mendiknas Nomor : 146 / D / O / 2000 Jo 120 / D / O / 2003</p>
    <p class="sub">Jl. Veteran No. 53 A Telp./Fax. (0322) 324706, 317116 Lamongan</p>
    <p class="sub">Email : rektorat@unisla.ac.id &nbsp; http://unisla.ac.id</p>
</td>
</tr>
</table>
</div>

<div class="garis-tebal"></div>
<div class="garis-tipis"></div>

<div class="isi">

<!-- HEADER -->
<div class="header-surat">

<div class="tanggal">
    Lamongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

<table>
<tr><td width="90">Nomor</td><td width="10">:</td><td>{{ $noSurat }}</td></tr>
<tr><td>Lampiran</td><td>:</td><td>1 (satu) lembar</td></tr>
<tr><td>Sifat</td><td>:</td><td>Penting</td></tr>
<tr>
<td>Perihal</td>
<td>:</td>
<td><u>Permohonan Praktek Kerja Nyata</u></td>
</tr>
</table>

</div>

<div style="margin-top:10px;"></div>

<p>
Kepada Yth.<br>
{{ $pengajuan->tempatPkl->nama_tempat }}<br>
Di tempat
</p>

<p>Assalaamu’alaikum Wr. Wb.</p>

<p><b>Dengan hormat,</b></p>

<p>
Dengan ini kami beritahukan bahwa sesuai agenda akademik Semester Gasal 
{{ $pengajuan->mahasiswa->prodi->fakultas->nama }}, Program Studi 
{{ $pengajuan->mahasiswa->prodi->nama }}, mahasiswa yang telah menempuh 
semester {{ $pengajuan->semester }} atau minimal 80 SKS diwajibkan 
melaksanakan Praktek Kerja Nyata (PKN).
</p>

<p>
Program ini bertujuan untuk memberikan pengalaman serta keterampilan 
teoritis dan praktis sesuai dengan bidang keilmuan mahasiswa.
</p>

<p>
Sehubungan dengan hal tersebut, kami memohon kesediaan Bapak/Ibu untuk 
memberikan izin pelaksanaan PKN bagi mahasiswa berikut:
</p>

<table class="biodata" width="100%">
<tr><td width="150">Nama</td><td width="10">:</td><td>{{ $pengajuan->mahasiswa->nama }}</td></tr>
<tr><td>NIM</td><td>:</td><td>{{ $pengajuan->mahasiswa->nim }}</td></tr>
<tr><td>Fakultas</td><td>:</td><td>{{ $pengajuan->mahasiswa->prodi->fakultas->nama }}</td></tr>
<tr><td>Program Studi</td><td>:</td><td>{{ $pengajuan->mahasiswa->prodi->nama }}</td></tr>
<tr><td>Semester</td><td>:</td><td>{{ $pengajuan->semester }}</td></tr>
<tr><td>Alamat</td><td>:</td><td>{{ $pengajuan->alamat_asal }}</td></tr>
<tr><td>No. HP</td><td>:</td><td>{{ $pengajuan->mahasiswa->no_hp }}</td></tr>
</table>

<p>
Pelaksanaan PKN direncanakan selama ±60 hari kerja sejak disetujuinya surat ini.
</p>

<p>
Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerja sama 
yang diberikan, kami ucapkan terima kasih.
</p>

<p>Wassalaamu’alaikum Wr. Wb.</p>

<!-- TTD -->
<div class="ttd">
<p>Rektor,</p>

<br><br><br>

<p><b>Dr. H. ABDUL GHOFUR, S.E., M.Si.</b></p>
<p>NIDN. 0723116803</p>
</div>

<br>

<p><b>Tembusan :</b></p>
<ol>
<li>Dekan {{ $pengajuan->mahasiswa->prodi->fakultas->nama }}</li>
<li>Kepala BAASIK</li>
<li>Kaprodi {{ $pengajuan->mahasiswa->prodi->nama }}</li>
<li>Arsip</li>
</ol>

</div>

</body>
</html>