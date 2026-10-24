<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { color: #16a34a; font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Surat Pengantar PKL Siap Diambil</div>
        
        <p>Halo <strong>{{ $pengajuan->mahasiswa->nama }}</strong>,</p>
        
        <p>Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani oleh Rektor.</p>
        
        <p>Silakan mengambil surat fisik tersebut di <strong>Tata Usaha (TU)</strong> pada jam kerja.</p>
        
        <div class="footer">
            <p>Terima kasih,<br><strong>Tim TU SIBOLANG UNISLA</strong></p>
        </div>
    </div>
</body>
</html>