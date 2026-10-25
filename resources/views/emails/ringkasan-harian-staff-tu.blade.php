<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ringkasan Tugas Staff TU</title>
</head>

<body>
    <h2>Ringkasan Tugas Harian SIBOLANG</h2>

    <p>Yth. {{ $namaStaff }},</p>

    <p>
        Berikut ringkasan tugas yang masih menunggu penanganan
        per tanggal {{ $tanggal }}.
    </p>

    <table cellpadding="10" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th align="left">Jenis Tugas</th>
                <th align="center">Jumlah Tertunda</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pengajuan PKL menunggu verifikasi</td>
                <td align="center">{{ $jumlahPengajuan }}</td>
            </tr>
            <tr>
                <td>Surat pengantar perlu diproses</td>
                <td align="center">{{ $jumlahSurat }}</td>
            </tr>
            <tr>
                <td>Nilai PKL menunggu persetujuan</td>
                <td align="center">{{ $jumlahNilai }}</td>
            </tr>
            <tr>
                <th align="left">Total tugas tertunda</th>
                <th align="center">
                    {{ $jumlahPengajuan + $jumlahSurat + $jumlahNilai }}
                </th>
            </tr>
        </tbody>
    </table>

    <p>
        Mohon menindaklanjuti tugas tersebut melalui dashboard
        Staff TU pada aplikasi SIBOLANG.
    </p>

    <p>
        Email ini dikirim secara otomatis. Jumlah tugas dapat berubah
        apabila ada tugas yang diselesaikan atau tugas baru yang masuk.
    </p>

    <p>
        Hormat kami,<br>
        Sistem SIBOLANG UNISLA
    </p>
</body>

</html>
