<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengajuan PKL SIBOLANG</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f6f8; margin: 0; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 25px; border: 1px solid #e1e4e8;">

        <div style="border-bottom: 2px solid #16a34a; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #16a34a; margin: 0; font-size: 22px;">
                Informasi Pengajuan PKL
            </h2>
            <p style="margin: 5px 0 0; color: #64748b; font-size: 13px;">
                SIBOLANG UNISLA
            </p>
        </div>

        <p>Yth. <strong>{{ $pengajuan->mahasiswa->nama ?? 'Mahasiswa' }}</strong>,</p>

        <p>
            Kami menyampaikan informasi terbaru mengenai pengajuan Praktik Kerja Lapangan (PKL) Anda di
            <strong>{{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</strong>.
        </p>

        @if ($pengajuan->status === 'ditolak_tu')
            <div
                style="background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0 0 8px; color: #c2410c; font-size: 17px;">
                    <strong>Dokumen Perlu Diperbaiki</strong>
                </p>
                <p style="margin: 0;">
                    Pengajuan PKL Anda dikembalikan oleh Tata Usaha karena terdapat dokumen yang perlu diperbaiki.
                </p>
            </div>

            @if ($pengajuan->catatan_tu)
                <p><strong>Catatan Tata Usaha:</strong></p>
                <div
                    style="background-color: #f8fafc; border-left: 4px solid #f59e0b; padding: 12px 15px; margin: 10px 0 20px; white-space: pre-line;">
                    {{ $pengajuan->catatan_tu }}
                </div>
            @endif

            <p>Silakan masuk ke SIBOLANG untuk memeriksa dan memperbaiki dokumen Anda.</p>
        @elseif ($pengajuan->status === 'ditolak_kaprodi')
            <div
                style="background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0 0 8px; color: #c2410c; font-size: 17px;">
                    <strong>Pengajuan PKL Belum Disetujui</strong>
                </p>
                <p style="margin: 0;">
                    Mohon maaf, pengajuan PKL Anda belum dapat disetujui oleh Kaprodi.
                </p>
            </div>

            @if ($pengajuan->catatan_kaprodi)
                <p><strong>Catatan Kaprodi:</strong></p>
                <div
                    style="background-color: #f8fafc; border-left: 4px solid #f59e0b; padding: 12px 15px; margin: 10px 0 20px; white-space: pre-line;">
                    {{ $pengajuan->catatan_kaprodi }}
                </div>
            @endif

            <p>Silakan masuk ke SIBOLANG untuk melihat informasi pengajuan Anda.</p>
        @elseif ($pengajuan->status === 'disetujui')
            <div
                style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0 0 8px; color: #15803d; font-size: 17px;">
                    <strong>Selamat! Pengajuan PKL Disetujui</strong>
                </p>
                <p style="margin: 0;">
                    Pengajuan PKL Anda telah disetujui oleh Kaprodi dan PKL Anda sudah berstatus <strong>aktif</strong>.
                </p>
            </div>

            <p>Silakan masuk ke SIBOLANG untuk melihat informasi dan tahapan selanjutnya.</p>
        @endif

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ url('/login') }}"
                style="display: inline-block; background-color: #16a34a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                Buka SIBOLANG
            </a>
        </p>

        <p style="font-size: 13px; color: #555555;">
            Jika tombol tidak dapat dibuka, akses tautan berikut melalui browser Anda:
            <br>
            <a href="{{ url('/login') }}" style="color: #16a34a; word-break: break-all;">
                {{ url('/login') }}
            </a>
        </p>

        <div
            style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #eaeaea; font-size: 13px; color: #666666;">
            <p>
                Terima kasih,<br>
                <strong>Tim SIBOLANG UNISLA</strong>
            </p>
            <p style="margin-bottom: 0; color: #94a3b8;">
                Sistem Informasi Logbook PKL dan Magang<br>
                Universitas Islam Lamongan
            </p>
        </div>

    </div>
</body>

</html>
