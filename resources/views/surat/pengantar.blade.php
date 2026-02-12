<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Permohonan PKL</title>

<style>

/* ================= PAGE ================= */

@page {
    size: 210mm 330mm; /* F4 */
    margin: 10mm 20mm 20mm 25mm; /* ATAS DIPERKECIL */
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    margin: 0;
    padding: 0;
}

/* ================== KOP ================== */

.kop {
    width: 100%;
    padding: 0;
    margin: 0;
}

.kop table {
    width: 100%;
    border: none;
    border-collapse: collapse;
    margin: 0;
    padding: 0;
}

.kop td {
    border: none;
    vertical-align: middle;
    padding: 0;
}

.kop .logo {
    width: 90px;
    text-align: center;
}

.kop .logo img {
    width: 85px;
}

.kop .text {
    text-align: center;
    line-height: 1.3;
}

.kop h2 {
    margin: 0;
    font-size: 16pt;
    font-weight: bold;
}

.kop h1 {
    margin: 0;
    font-size: 22pt;
    letter-spacing: 2px;
    font-weight: bold;
}

.kop p {
    margin: 0;
    font-size: 10.5pt;
    text-align: center; /* PAKSA CENTER */
}

/* ================== GARIS ================== */

.garis-tebal {
    border-top: 3px solid black;
    margin: 5px 0 2px 0;
}

.garis-tipis {
    border-top: 1px solid black;
    margin: 0 0 15px 0;
}

/* ================== HEADER ================== */

.header-surat {
    position: relative;
    line-height: 1.5;
}

.tanggal {
    position: absolute;
    right: 0;
    top: 0;
}

.header-surat p {
    margin: 2px 0;
}

/* ================== ISI ================== */

p {
    text-align: justify;
    margin: 8px 0;
}

table.data {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table.data,
table.data th,
table.data td {
    border: 1px solid black;
}

table.data th,
table.data td {
    padding: 4px;
    text-align: center;
}

/* ================== TTD ================== */

.ttd {
    width: 40%;
    margin-left: auto; /* DORONG KE KANAN */
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
    <img src="{{ base_path('public/img/unisla.png') }}">
</td>

<td class="text">

    <h2>UNIVERSITAS ISLAM LAMONGAN</h2>
    <h1>(UNISLA)</h1>

    <p>SK. Mendiknas Nomor : 146 / D / O / 2000 Jo 120 / D / O / 2003</p>
    <p>Jl. Veteran No. 53 A Telp./Fax. (0322) 324706, 317116 Lamongan</p>
    <p>Email : rektorat@unisla.ac.id &nbsp;&nbsp; http://unisla.ac.id</p>

</td>

</tr>
</table>

</div>

<div class="garis-tebal"></div>
<div class="garis-tipis"></div>

<!-- ================== HEADER ================== -->

<div class="header-surat">

<div class="tanggal">
    Lamongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

<p>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $noSurat }}</p>
<p>Lampiran&nbsp;: 1 (satu) lembar</p>
<p>Sifat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Penting</p>
<p>Perihal&nbsp;&nbsp;&nbsp;&nbsp;: <u>Permohonan Praktek Kerja Nyata</u></p>

</div>

<br>

<p>
Kepada Yth:<br>
Kepala {{ $pengajuan->tempatPkl->nama_tempat }}<br>
Di Tempat
</p>

<p>Assalamu'alaikum Wr. Wb.</p>

<p><b>Dengan Hormat,</b></p>

<p>
Dengan ini kami beritahukan sebagaimana agenda akademik Semester Ganjil Fakultas Sains dan Teknologi 
Program Studi {{ $pengajuan->mahasiswa->prodi->nama }}, bahwa mahasiswa kami yang telah duduk di semester V 
atau sekurang-kurangnya telah menempuh 80 SKS diwajibkan untuk melaksanakan Praktek Kerja Nyata (PKN). 
Adapun program PKN tersebut merupakan muatan kurikulum wajib yang harus ditempuh dengan tujuan untuk 
memberikan bekal keterampilan teoritis dan praktis di lapangan sesuai dengan disiplin ilmu pada Program Studi.
</p>

<p>
Oleh karena itu, guna memberikan pengalaman lapangan yang cukup kami memohon dengan hormat 
Bapak/Ibu/Saudara untuk berkenan memberikan izin melaksanakan kegiatan PKN bagi mahasiswa kami tersebut 
di bawah ini:
</p>

<table class="data">
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
Adapun pelaksanaan PKN ini kami mohonkan adalah selama 60 hari kerja terhitung sejak tanggal 
disetujuinya surat permohonan ini.
</p>

<p>
Demikian surat permohonan ini kami sampaikan, atas perhatian, kerjasama dan izin yang diberikan 
kami haturkan terima kasih yang sedalam-dalamnya.
</p>

<p>Wassalamu'alaikum Wr. Wb.</p>

<!-- ================== TTD ================== -->

<div class="ttd">

<p>Rektor,</p>

<br><br><br><br>

<p><b>Dr. H. ABDUL GHOFUR, S.E., M.Si.</b></p>
<p>NIDN. 0723116803</p>

</div>

<br>

<p><b>Tembusan:</b></p>

<ol>
<li>Yth. Dekan Fakultas Sains dan Teknologi</li>
<li>Yth. Kepala BAAKSI</li>
<li>Yth. Kaprodi {{ $pengajuan->mahasiswa->prodi->nama }}</li>
<li>Arsip</li>
</ol>

</body>
</html>
