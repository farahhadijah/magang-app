<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PengajuanPkl extends Model
{
    protected $table = 'pengajuan_pkl';
    protected $fillable = [
        'id_mhs',
        'id_tempat_pkl',
        'status',
        'tgl_pengajuan',
        'semester',
        'alamat_asal',
        'catatan_tu',
        'catatan_kaprodi',
        'status_surat',
        'status_surat',
        'pesan_surat',
    ];
    /* ================= RELATION ================= */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mhs');
    }
    public function tempatPkl()
    {
        return $this->belongsTo(TempatPkl::class, 'id_tempat_pkl');
    }
    public function dokumenPengajuan()
    {
        return $this->hasMany(DokumenPengajuan::class, 'id_pengajuan_pkl');
    }
    public function khs()
{
    return $this->dokumenPengajuan()
        ->where('jenis_dokumen', DokumenPengajuan::JENIS_KHS);
}

public function formPkn()
{
    return $this->dokumenPengajuan()
        ->where('jenis_dokumen', DokumenPengajuan::JENIS_FORM_PKN);
}

// public function krsRemedial()
// {
//     return $this->dokumenPengajuan()
//         ->where('jenis_dokumen', DokumenPengajuan::JENIS_KRS_REMEDIAL);
// }
    public function pkl()
    {
        return $this->hasOne(Pkl::class, 'id_pengajuan_pkl');
    }
    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_pengajuan_pkl');
    }
    /* ================= HELPER TU ================= */
    public function dokumenWajibLengkap(): bool
    {
        $wajib = [
            DokumenPengajuan::JENIS_KHS,
            DokumenPengajuan::JENIS_PEMBAYARAN,
            DokumenPengajuan::JENIS_STUDI_TOUR,
            DokumenPengajuan::JENIS_FORM_PKN,
            // DokumenPengajuan::JENIS_KRS_REMEDIAL,
        ];

        foreach ($wajib as $jenis) {
            if (! $this->dokumenPengajuan()
                    ->where('jenis_dokumen', $jenis)
                    ->exists()) {
                return false;
            }
        }

        return true;
    }
    public function bisaDiverifikasiTu(): bool
    {
        // TU dapat melakukan verifikasi ketika status pengajuan masih pending_tu
        // dan belum ada verifikasi TU yang APPROVED. Jika sebelumnya TU
        // menolak (rejected), mahasiswa bisa mengupload ulang dokumen dan TU
        // boleh memverifikasi lagi.
        return $this->status === 'pending_tu'
            && ! $this->verifikasi()
                ->where('level', 'tu')
                ->where('status', 'approved')
                ->exists();
    }
    public function adaDokumenInvalid(): bool
    {
        return $this->dokumenPengajuan()
            ->where('status_verifikasi', 'invalid')
            ->exists();
    }
    public function semuaDokumenValid(): bool
    {
        return $this->dokumenPengajuan()
            ->where('status_verifikasi', '!=', 'valid')
            ->count() === 0;
    }
    public function bisaDisetujuiTu(): bool
    {
        return $this->bisaDiverifikasiTu()
            && $this->dokumenWajibLengkap()
            && $this->semuaDokumenValid();
    }
    public function bisaDikembalikanKeMahasiswa(): bool
    {
        return $this->bisaDiverifikasiTu()
            && $this->adaDokumenInvalid();
    }
    /**
     * Apakah pengajuan telah disetujui/di-verifikasi oleh TU sebelumnya?
     */
    public function sudahDiverifikasiTu(): bool
    {
        return $this->verifikasi()
            ->where('level', 'tu')
            ->where('status', 'approved')
            ->exists();
    }
    /* ================= HELPER KAPRODI ================= */
    public function bisaDiverifikasiKaprodi(): bool
{
    if ($this->status !== 'pending_kaprodi') {
        return false;
    }

    // Pastikan relasi sudah diload untuk mencegah query tambahan
    if (! $this->relationLoaded('verifikasi')) {
        $this->load('verifikasi');
    }

    $verifikasi = $this->verifikasi;

    $sudahDisetujuiTU = $verifikasi
        ->where('level', 'tu')
        ->where('status', 'approved')
        ->isNotEmpty();

    $sudahDiverifikasiKaprodi = $verifikasi
        ->where('level', 'kaprodi')
        ->isNotEmpty();

    return $sudahDisetujuiTU && ! $sudahDiverifikasiKaprodi;
}
    /* ================= QUERY SCOPE ================= */
    public function scopeUntukTu($query)
    {
        return $query->where('status', 'pending_tu');
    }
public function scopeMunculUntukKaprodi($query)
{
    return $query
        ->where('status', 'pending_kaprodi')
        ->whereHas('verifikasi', function ($q) {
            $q->where('level', 'tu')
              ->where('status', 'approved');
        });
}
    public function statusLabel(): array
    {
        return match ($this->status) {
            'draft' => [
                'text' => 'Draft',
                'class' => 'text-gray-700 bg-gray-100',
            ],
            'pending_tu' => [
                'text' => 'Menunggu Verifikasi TU',
                'class' => 'text-amber-800 bg-amber-100',
            ],
            'pending_kaprodi' => [
                'text' => 'Menunggu Verifikasi Kaprodi',
                'class' => 'text-amber-800 bg-amber-100',
            ],
            'ditolak_tu' => [
                'text' => 'Ditolak TU', 'class' => 'text-red-800 bg-red-100',
            ],
            'ditolak_kaprodi' => [
                'text' => 'Ditolak Kaprodi',
                'class' => 'text-red-800 bg-red-100',
            ],
            'disetujui' => [
                'text' => 'Disetujui',
                'class' => 'text-green-800 bg-green-100',
            ],
            default => [
                'text' => ucfirst($this->status),
                'class' => 'text-gray-800 bg-gray-100',
            ],
        };
    }

    public function statusSuratLabel(): array
    {
        return match ($this->status_surat) {
            null => [
                'text' => 'Belum Diproses',
                'class' => 'text-gray-700 bg-gray-100',
            ],
            'diproses' => [
                'text' => 'Sedang Diproses TU',
                'class' => 'text-blue-800 bg-blue-100',
            ],
            'siap_diambil' => [
                'text' => 'Siap Diambil',
                'class' => 'text-green-800 bg-green-100',
            ],
            default => [
                'text' => ucfirst($this->status_surat),
                'class' => 'text-gray-800 bg-gray-100',
            ],
        };
    }
    public function bisaDicetak(): bool
    {
        return $this->status === 'disetujui';
    }
    public function bisaDivalidasi(): bool
    {
        return $this->status === 'disetujui'
            && $this->status_surat !== 'siap_diambil';
    }
}