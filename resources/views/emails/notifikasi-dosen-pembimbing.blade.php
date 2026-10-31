<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Mahasiswa Bimbingan Baru</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #059669;">Halo, {{ $namaDosen }}</h2>

        <p>Anda baru saja ditunjuk sebagai <strong>Dosen Pembimbing PKL</strong> oleh Kaprodi untuk mahasiswa berikut:
        </p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 40%;"><strong>Nama
                        Mahasiswa</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $namaMahasiswa }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;"><strong>NIM</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $nimMahasiswa }}</td>
            </tr>
            @if ($prodiMahasiswa)
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;"><strong>Program Studi</strong>
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $prodiMahasiswa }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;"><strong>Tempat PKL</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $namaTempat }}</td>
            </tr>
        </table>

        <p>Silakan login ke aplikasi MagangApp untuk memantau logbook dan aktivitas bimbingan mahasiswa tersebut.</p>

        <p style="margin-top: 30px; font-size: 12px; color: #888;">
            Email ini dikirim secara otomatis oleh sistem MagangApp. Mohon tidak membalas email ini.
        </p>
    </div>
</body>

</html>
