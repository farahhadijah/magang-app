@component('mail::message')
# Halo, {{ $pengajuan->mahasiswa->nama }}

Berikut adalah update status terbaru mengenai pengajuan Praktik Kerja Nyata (PKN) Anda di **{{ $pengajuan->nama_instansi }}**:

**Status Saat Ini:** 
@if($pengajuan->status == 'valid')
Disetujui
@elseif($pengajuan->status == 'invalid')
Ditolak / Perlu Perbaikan
@else
{{ $pengajuan->status }}
@endif

@if($pengajuan->catatan_perbaikan)
**Catatan dari Staf BAASIK:**
{{ $pengajuan->catatan_perbaikan }}
@endif

Untuk melihat detail lebih lanjut atau mengunggah ulang dokumen (jika ditolak), silakan login ke dalam sistem SIBOLANG.

@component('mail::button', ['url' => url('/login')])
Cek Dashboard SIBOLANG
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent