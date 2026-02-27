<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Permohonan PKL</title>

<style>

@page {
    size: 210mm 330mm; /* F4 */
    margin: 10mm 20mm 20mm 25mm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    margin: 0;
    padding: 0;
}

/* ================== KOP ================== */

.kop table {
    width: 100%;
    border-collapse: collapse;
}

.kop td {
    vertical-align: middle;
}

/* Kolom logo */
.logo {
    width: 18%;
    text-align: center;
}

.logo img {
    width: 120px; /* sedikit diperbesar */
}

/* Kolom teks */
.text {
    width: 82%;
    text-align: center;
    line-height: 1.4;
}

.text h2 {
    margin: 0 0 2px 0;
    font-size: 17pt;
    font-weight: bold;
}

.text h1 {
    margin: 0 0 4px 0;
    font-size: 26pt;
    letter-spacing: 2px;
    font-weight: bold;
}

.text .sk {
    margin: 0;
    font-size: 12pt;
    font-weight: bold;
}

.text .sub {
    margin: 0;
    font-size: 10pt;
}

/* ================== GARIS ================== */

.garis-tebal {
    border-top: 4px solid black;
    margin: 6px 0 2px 0;
}

.garis-tipis {
    border-top: 1px solid black;
    margin: 0 0 15px 0;
}

/* ================== HEADER ================== */

.header-surat {
    position: relative;
    line-height: 1.6;
}

.tanggal {
    position: absolute;
    right: 0;
    top: 0;
}

/* ================== ISI ================== */

.isi p {
    text-align: justify;
    margin: 8px 0;
}

.biodata {
    margin-top: 10px;
}

.biodata td {
    padding: 2px 0;
    vertical-align: top;
}

/* ================== TTD ================== */

.ttd {
    width: 40%;
    margin-left: auto;
    margin-top: 60px;
    text-align: center;
}

</style>
</head>

<body>

<!-- ================== KOP ================== -->

<div class="kop">
<table>
<tr>
<td class="logo">
    <img src="{{ base_path('public/img/logounisla.png') }}">
</td>
<td class="text">
    <h2>UNIVERSITAS ISLAM LAMONGAN</h2>
    <h1>(UNISLA)</h1>
    <p class="sk">
        SK. Mendiknas Nomor : 146 / D / O / 2000 Jo 120 / D / O / 2003
    </p>
    <p class="sub">
        Jl. Veteran No. 53 A Telp./Fax. (0322) 324706, 317116 Lamongan
    </p>
    <p class="sub">
        Email : rektorat@unisla.ac.id &nbsp;&nbsp; http://unisla.ac.id
    </p>
</td>
</tr>
</table>
</div>

<div class="garis-tebal"></div>
<div class="garis-tipis"></div>

<div class="isi">

<!-- ================== HEADER ================== -->

<div class="header-surat">

<div class="tanggal">
    Lamongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

<p>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $noSurat }}</p>
<p>Lampiran&nbsp;: 1 (satu) Lembar</p>
<p>Sifat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Penting</p>
<p>Perihal&nbsp;&nbsp;&nbsp;&nbsp;: <u>Permohonan Praktek Kerja Nyata</u></p>

</div>

<br>

<p>
Kepada Yth. :<br>
{{ $pengajuan->tempatPkl->nama_tempat }}<br>
Di tempat
</p>

<p>Assalaamu’alaikum Wr. Wb.</p>

<p><b>Dengan Hormat,</b></p>

<p>
Dengan ini kami beritahukan, sebagaimana agenda akademik Semester Gasal 
{{ $pengajuan->mahasiswa->prodi->fakultas->nama }} 
Program Studi {{ $pengajuan->mahasiswa->prodi->nama }} 
bahwa mahasiswa kami yang telah duduk di semester {{ $pengajuan->semester }} 
atau sekurang-kurangnya telah menempuh 80 SKS, diwajibkan untuk melaksanakan 
Praktek Kerja Nyata (PKN). Adapun program PKN tersebut merupakan muatan 
kurikulum wajib yang harus ditempuh dengan tujuan untuk memberikan bekal 
ketrampilan teoritis dan praktis di lapangan sesuai dengan disiplin ilmu 
pada Program Studi {{ $pengajuan->mahasiswa->prodi->nama }}.
</p>

<p>
Oleh karena itu, guna memberikan pengalaman lapangan yang cukup kami memohon 
dengan hormat Bapak/Ibu/Saudara untuk berkenan memberikan ijin melaksanakan 
kegiatan PKN bagi mahasiswa kami yang tersebut di bawah ini :
</p>

<table class="biodata" width="100%">
<tr>
<td width="180">Nama</td>
<td width="10">:</td>
<td>{{ $pengajuan->mahasiswa->nama }}</td>
</tr>

<tr>
<td>NIM</td>
<td>:</td>
<td>{{ $pengajuan->mahasiswa->nim }}</td>
</tr>

<tr>
<td>Fakultas</td>
<td>:</td>
<td>{{ $pengajuan->mahasiswa->prodi->fakultas->nama }}</td>
</tr>

<tr>
<td>Program Studi</td>
<td>:</td>
<td>{{ $pengajuan->mahasiswa->prodi->nama }}</td>
</tr>

<tr>
<td>Semester</td>
<td>:</td>
<td>{{ $pengajuan->semester }}</td>
</tr>

<tr>
<td>Alamat Asal</td>
<td>:</td>
<td>{{ $pengajuan->alamat_asal }}</td>
</tr>

<tr>
<td>No. HP</td>
<td>:</td>
<td>{{ $pengajuan->mahasiswa->no_hp }}</td>
</tr>
</table>

<p>
Adapun pelaksanaan PKN ini kami mohonkan adalah selama 60 hari kerja 
terhitung sejak tanggal disetujuinya Surat Permohonan ini.
</p>

<p>
Demikian surat permohonan kami, atas perhatian, kerjasama dan ijin yang 
diberikan kami haturkan terima kasih yang sedalam-dalamnya.
</p>

<p>Wassalaamu’alaikum Wr. Wb.</p>

<!-- ================== TTD ================== -->

<div class="ttd">
<p>Rektor,</p>

<br><br><br><br>

<p><b>Dr. H. ABDUL GHOFUR, S.E., M.Si.</b></p>
<p>NIDN. 0723116803</p>
</div>

<br>

<p><b>Tembusan :</b></p>
<ol>
<li>Yth. Dekan {{ $pengajuan->mahasiswa->prodi->fakultas->nama }}</li>
<li>Yth. Kepala BAASIK</li>
<li>Yth. Kaprodi {{ $pengajuan->mahasiswa->prodi->nama }}</li>
<li>Arsip</li>
</ol>

</div>

</body>
</html>