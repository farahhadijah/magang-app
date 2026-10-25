<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pengajuan PKL - SIBOLANG</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f6f8; margin: 0; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; padding: 25px; border: 1px solid #e1e4e8;">

        <div style="border-bottom: 2px solid #16a34a; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #16a34a; margin: 0; font-size: 22px;">
                Ringkasan Pengajuan PKL
            </h2>
            <p style="margin: 5px 0 0; color: #64748b; font-size: 13px;">
                SIBOLANG UNISLA
            </p>
        </div>

        <p>Yth. Bapak/Ibu <strong>{{ $namaKaprodi }}</strong>,</p>

        <p>
            Berikut ringkasan pengajuan Praktik Kerja Lapangan (PKL)
            yang memerlukan verifikasi Kaprodi pada tanggal
            <strong>{{ $tanggal }}</strong>.
        </p>

        <div
            style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 18px; margin: 20px 0; text-align: center;">
            <p style="margin: 0; color: #166534; font-size: 14px;">
                Pengajuan menunggu verifikasi
            </p>
            <p style="margin: 5px 0 0; color: #15803d; font-size: 32px; font-weight: bold;">
                {{ $jumlahPengajuan }}
            </p>
            <p style="margin: 0; color: #166534; font-size: 13px;">
                pengajuan PKL
            </p>
        </div>

        <p><strong>Daftar pengajuan:</strong></p>

        @foreach ($pengajuans as $index => $pengajuan)
            <div
                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin: 12px 0;">
                <p style="margin: 0 0 6px;">
                    <strong>{{ $index + 1 }}.
                        {{ $pengajuan->mahasiswa->nama ?? 'Nama mahasiswa tidak tersedia' }}</strong>
                </p>
                <p style="margin: 4px 0; font-size: 14px;">
                    Tempat PKL:
                    {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}
                </p>
                <p style="margin: 4px 0; font-size: 13px; color: #b45309;">
                    Status: Menunggu verifikasi Kaprodi
                </p>
            </div>
        @endforeach

        <p>
            Mohon Bapak/Ibu membuka aplikasi SIBOLANG untuk memeriksa
            dan memproses pengajuan tersebut.
        </p>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ url('/login') }}"
                style="display: inline-block; background: #16a34a; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                Buka SIBOLANG
            </a>
        </p>

        <p style="font-size: 13px; color: #555;">
            Jika tombol tidak dapat dibuka, gunakan tautan berikut:
            <br>
            <a href="{{ url('/login') }}" style="color: #16a34a; word-break: break-all;">
                {{ url('/login') }}
            </a>
        </p>

        <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #eaeaea; font-size: 13px; color: #666;">
            <p>
                Terima kasih,<br>
                <strong>Tim SIBOLANG UNISLA</strong>
            </p>
            <p style="color: #94a3b8;">
                Sistem Informasi Logbook PKL dan Magang<br>
                Universitas Islam Lamongan
            </p>
        </div>

    </div>
</body>

</html>
