<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Verifikasi Pengajuan PKL</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: Arial, sans-serif; color: #333333;">

    <div style="max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">

        {{-- Header --}}
        <div style="background-color: #1e40af; padding: 25px; text-align: center; color: #ffffff;">
            <h2 style="margin: 0; font-size: 22px;">
                SIBOLANG
            </h2>

            <p style="margin: 8px 0 0; font-size: 14px;">
                Sistem Informasi Logbook Magang
            </p>
        </div>

        {{-- Isi Email --}}
        <div style="padding: 30px;">

            <p style="margin-top: 0;">
                Yth. {{ $namaKaprodi }},
            </p>

            <p>
                Sistem SIBOLANG menginformasikan bahwa terdapat
                <strong>{{ $jumlahPengajuan }} pengajuan PKL</strong>
                yang menunggu verifikasi Ketua Program Studi.
            </p>

            <div style="background-color: #eff6ff; border-left: 4px solid #1e40af; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px;">
                    <strong>Informasi Pengajuan:</strong><br>
                    Jumlah pengajuan dengan status menunggu verifikasi:
                    <strong>{{ $jumlahPengajuan }} pengajuan</strong>
                </p>
            </div>

            <p>
                Mohon untuk melakukan pemeriksaan dan verifikasi pengajuan
                melalui aplikasi SIBOLANG.
            </p>

            {{-- Button Akses Website --}}
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $urlSibolang }}" target="_blank"
                    style="display: inline-block; background-color: #1e40af; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-size: 15px; font-weight: bold;">
                    Buka SIBOLANG
                </a>
            </div>

            <p>
                Terima kasih atas perhatian dan kerja samanya.
            </p>

            <p style="margin-bottom: 0;">
                Hormat kami,<br>
                <strong>Sistem SIBOLANG</strong><br>
                Universitas Islam Lamongan
            </p>

        </div>

        {{-- Footer --}}
        <div style="background-color: #f4f6f9; padding: 15px; text-align: center;">
            <p style="margin: 0; font-size: 12px; color: #777777;">
                Email ini dikirim secara otomatis oleh sistem SIBOLANG.
                Mohon tidak membalas email ini.
            </p>
        </div>

    </div>

</body>

</html>
