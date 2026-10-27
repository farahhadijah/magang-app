<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Logbook SIBOLANG</title>
</head>

<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial,sans-serif; color:#374151;">
    <div style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:8px; overflow:hidden;">
        <div style="background:#15803d; padding:24px; text-align:center; color:#ffffff;">
            <h2 style="margin:0;">SIBOLANG</h2>
            <p style="margin:8px 0 0;">Sistem Informasi Logbook Magang</p>
        </div>

        <div style="padding:28px;">
            <h3 style="color:#166534; margin-top:0;">
                Logbook Menunggu Verifikasi
            </h3>

            <p>Yth. Bapak/Ibu Dosen Pembimbing,</p>

            <p>
                Terdapat logbook mahasiswa yang perlu diverifikasi.
                Silakan masuk ke SIBOLANG untuk melakukan pemeriksaan
                dan persetujuan logbook.
            </p>

            <p>
                <strong>Mahasiswa:</strong>
                {{ $logbook->pkl->pengajuanPkl->mahasiswa->nama ?? 'Mahasiswa PKL' }}
            </p>

            <p>
                <strong>Tanggal Logbook:</strong>
                {{ $logbook->tgl->format('d-m-Y') }}
            </p>

            <div style="text-align:center; margin:28px 0;">
                <a href="{{ url('/login') }}"
                    style="display:inline-block; background:#15803d; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:6px;">
                    Buka SIBOLANG
                </a>
            </div>

            <p style="font-size:13px; color:#6b7280;">
                Email ini dikirim secara otomatis oleh sistem SIBOLANG.
            </p>
        </div>

        <div style="background:#f9fafb; padding:16px; text-align:center; font-size:12px; color:#6b7280;">
            Universitas Islam Lamongan
        </div>
    </div>
</body>

</html>
