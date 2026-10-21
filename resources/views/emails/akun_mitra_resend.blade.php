<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Mitra SIBOLANG</title>
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

        .notice {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 15px 0;
            font-size: 14px;
            color: #1e40af;
        }

        .notice.warning {
            background-color: #fef3c7;
            border-color: #fde68a;
            color: #92400e;
        }

        .account-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 20px 0;
        }

        .account-box p {
            margin: 8px 0;
            font-size: 15px;
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

        code {
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            color: #1e293b;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header" style="text-align: center;">
            <img src="{{ asset('img/logounisla.png') }}" alt="Logo UNISLA" style="max-height: 70px; margin-bottom: 15px;">
            <h2>Akun Mitra SIBOLANG UNISLA</h2>
        </div>

        <p>Yth. <strong>{{ $namaTempat }}</strong>,</p>

        {{-- ================= PARAGRAF PEMBUKA BERDASARKAN JENIS ================= --}}
        @if ($jenis === \App\Models\EmailLog::JENIS_RESEND)
            <p>
                Kami mengirimkan <strong>ulang</strong> informasi akun kemitraan Anda untuk mengakses
                <strong>Sistem Informasi Logbook PKL dan Magang (SIBOLANG)</strong>.
                Email ini dikirim ulang atas permintaan Tim TU.
            </p>
            <div class="notice">
                <strong>Catatan:</strong> Password di bawah ini adalah password baru.
                Password lama sudah tidak berlaku.
            </div>
        @elseif ($jenis === \App\Models\EmailLog::JENIS_EDIT_EMAIL)
            <p>
                Email tujuan akun kemitraan Anda telah <strong>diperbarui</strong> oleh Tim TU.
                Sebagai konfirmasi, berikut informasi kredensial akun Anda untuk mengakses
                <strong>Sistem Informasi Logbook PKL dan Magang (SIBOLANG)</strong>.
            </p>
            <div class="notice">
                <strong>Catatan:</strong> Password di bawah ini adalah password baru.
                Password lama sudah tidak berlaku.
            </div>
        @elseif ($jenis === \App\Models\EmailLog::JENIS_RESET_PASSWORD)
            <p>
                Password akun kemitraan Anda telah <strong>direset</strong> oleh Tim TU
                untuk mengakses <strong>Sistem Informasi Logbook PKL dan Magang (SIBOLANG)</strong>.
                Silakan gunakan kredensial baru di bawah ini.
            </p>
            <div class="notice warning">
                <strong>Perhatian:</strong> Password lama Anda sudah tidak berlaku.
                Wajib ganti password setelah login pertama.
            </div>
        @else
            <p>
                Berikut informasi akun kemitraan Anda untuk mengakses
                <strong>Sistem Informasi Logbook PKL dan Magang (SIBOLANG)</strong>.
            </p>
        @endif

        {{-- ================= INFO KREDENSIAL ================= --}}
        <p>Berikut informasi kredensial akun Anda:</p>

        <div class="account-box">
            <p><strong>Username:</strong> <code>{{ $username }}</code></p>
            <p><strong>Password Sementara:</strong> <code>{{ $password }}</code></p>
        </div>

        <p>Silakan klik tautan tombol di bawah ini untuk mengakses aplikasi web SIBOLANG:</p>

        <p style="text-align: center;">
            <a href="{{ url('/login') }}" class="button" target="_blank">Akses Web Aplikasi SIBOLANG</a>
        </p>

        <p style="font-size: 13px; color: #555;">
            Atau akses langsung melalui tautan berikut pada browser Anda:<br>
            <a href="{{ url('/login') }}" style="color: #16a34a; word-break: break-all;">{{ url('/login') }}</a>
        </p>

        <p style="font-size: 13px; color: #64748b;">
            <em>Catatan: Demi keamanan, Anda akan diminta untuk mengganti password sementara pada saat pertama kali
                login ke sistem.</em>
        </p>

        {{-- ================= FOOTER ================= --}}
        <div class="footer">
            @if (!empty($namaStaff))
                <p style="margin: 0 0 8px;">
                    Dikirim oleh: <strong>{{ $namaStaff }}</strong> (Staff TU)
                </p>
            @endif
            <p>Terima kasih,<br><strong>Tim SIBOLANG UNISLA</strong></p>
        </div>
    </div>
</body>

</html>
