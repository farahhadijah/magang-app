<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notifikasi Pengajuan PKL</title>
</head>

<body style="margin: 0; padding: 20px; background-color: #f4f7f6; font-family: Arial, sans-serif;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden;">

        <div style="background: #198754; color: white; padding: 20px; text-align: center;">
            <h2 style="margin: 0;">SIBOLANG</h2>
            <p style="margin: 8px 0 0;">Sistem Informasi Logbook Magang</p>
        </div>

        <div style="padding: 25px; color: #333333;">

            <p>Yth. {{ $namaStaff }},</p>

            <p>
                Terdapat <strong>{{ $jumlahPengajuan }} pengajuan PKL</strong>
                yang saat ini menunggu verifikasi Tata Usaha.
            </p>

            <p>
                Silakan masuk ke aplikasi SIBOLANG untuk memeriksa
                dokumen dan memverifikasi pengajuan mahasiswa.
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ url('/login') }}"
                    style="display: inline-block; background: #198754; color: #ffffff;
                          padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                    Masuk ke SIBOLANG
                </a>
            </div>

            <p style="font-size: 13px; color: #666666;">
                Email ini dikirim otomatis oleh sistem SIBOLANG.
                Mohon tidak membalas email ini.
            </p>

        </div>

        <div
            style="background: #f4f7f6; padding: 15px; text-align: center;
                    font-size: 12px; color: #777777;">
            Universitas Islam Lamongan
        </div>

    </div>

</body>

</html>
