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
        'catatan_tu',
        'catatan_kaprodi',
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
    public function pkl()
    {
        return $this->hasOne(Pkl::class, 'id_pengajuan_pkl');
    }
    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_pengajuan_pkl');
    }
    /* ================= HELPER TU ================= */
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
        return $this->status === 'pending_kaprodi'
            && $this->verifikasi()
                ->where('level', 'tu')
                ->where('status', 'approved')
                ->exists()
            && ! $this->verifikasi()
                ->where('level', 'kaprodi')
                ->exists();
    }
    /* ================= QUERY SCOPE ================= */
    public function scopeUntukTu($query)
    {
        return $query->where('status', 'pending_tu');
    }
    /* ================= UI HELPER ================= */

    /* ================= QUERY SCOPE ================= */
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
}