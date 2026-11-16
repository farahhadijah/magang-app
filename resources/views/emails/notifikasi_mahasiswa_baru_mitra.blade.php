<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Mahasiswa PKL Baru - SIBOLANG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e1e4e8;
        }

        .header {
            border-bottom: 2px solid #16a34a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #16a34a;
            margin: 0;
            font-size: 22px;
        }

        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 18px 20px;
            margin: 20px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 8px 12px;
            border: 1px solid #dcfce7;
            font-size: 14px;
        }

        .info-table td.label {
            background-color: #f8fafc;
            font-weight: bold;
            width: 38%;
            color: #334155;
        }

        .button {
            display: inline-block;
            background-color: #16a34a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
            font-size: 13px;
            color: #666666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Pemberitahuan Mahasiswa PKL Baru</h2>
            <p style="margin: 5px 0 0; color: #64748b; font-size: 13px;">
                SIBOLANG UNISLA
            </p>
        </div>

        <p>Yth. <strong>{{ $namaTempat }}</strong>,</p>

        <p>
            Kami menginformasikan bahwa pengajuan Praktik Kerja Lapangan (PKL) untuk mahasiswa berikut telah disetujui
            oleh Kaprodi dan saat ini mahasiswa tersebut telah berstatus <strong>aktif</strong> melaksanakan PKL di
            instansi/perusahaan Anda:
        </p>

        <div class="info-box">
            <h3 style="margin: 0 0 10px 0; color: #15803d; font-size: 16px;">Detail Mahasiswa PKL:</h3>
            <table class="info-table" style="background-color: #ffffff;">
                <tr>
                    <td class="label">Nama Mahasiswa</td>
                    <td><strong>{{ $namaMahasiswa }}</strong></td>
                </tr>
                <tr>
                    <td class="label">NIM</td>
                    <td>{{ $nimMahasiswa }}</td>
                </tr>
                <tr>
                    <td class="label">Program Studi</td>
                    <td>{{ $prodiMahasiswa }}</td>
                </tr>
                <tr>
                    <td class="label">Dosen Pembimbing</td>
                    <td>{{ $namaDosen }}</td>
                </tr>
                @if (!empty($tglMulai))
                    <tr>
                        <td class="label">Tanggal Mulai PKL</td>
                        <td>{{ $tglMulai }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <p>
            Karena instansi Anda telah memiliki akun mitra di <strong>SIBOLANG</strong>, Anda dapat langsung masuk
            menggunakan akun yang sudah ada untuk memantau logbook kegiatan, memberikan tugas, dan mengisi penilaian
            akhir mahasiswa.
        </p>

        <p style="text-align: center;">
            <a href="{{ url('/login') }}" class="button" target="_blank">Akses Web Aplikasi SIBOLANG</a>
        </p>

        <p style="font-size: 13px; color: #555;">
            Atau akses langsung melalui tautan berikut pada browser Anda:<br>
            <a href="{{ url('/login') }}" style="color: #16a34a; word-break: break-all;">{{ url('/login') }}</a>
        </p>

        <div class="footer">
            <p>Terima kasih atas kesediaan dan kerja sama yang baik dalam mendukung kegiatan PKL mahasiswa
                kami.<br><strong>Tim SIBOLANG UNISLA</strong></p>
        </div>
    </div>
</body>

</html>
