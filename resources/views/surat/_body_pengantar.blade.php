<!-- Partial: only the body/content of surat pengantar -->

<!-- KOP -->
<div class="kop">
<table>
<tr>
<td class="logo">
    @php
        $logoPath = public_path('img/logounisla.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    @endphp
    @if($logoData)
        <img src="data:image/png;base64,{{ $logoData }}">
    @else
        <!-- logo not found -->
        <div style="width:100px;height:100px;background:#eee;display:inline-block;"></div>
    @endif
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
Pelaksanaan PKN direncanakan selama 60 hari kerja sejak disetujuinya surat ini.
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
