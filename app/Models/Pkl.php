<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;
use App\Models\User;
use App\Models\Logbook;
use App\Models\Dosen;
use App\Models\SuratPengantar;
use App\Models\LaporanAkhir;
use App\Models\NilaiPkl;
use Carbon\Carbon;


class Pkl extends Model
{
    public $timestamps = true;
    protected $table = 'pkl';
    protected $fillable = [
        'id_pengajuan_pkl',
        'id_dosen',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    public function dosen()
{
    return $this->belongsTo(Dosen::class, 'id_dosen');
}
    public function logbook()
{
    return $this->hasMany(Logbook::class, 'id_pkl');
}


    public function suratPengantar()
    {
        return $this->hasOne(SuratPengantar::class, 'id_pkl');
    }

    public function laporanAkhir()
    {
        return $this->hasOne(LaporanAkhir::class, 'id_pkl');
    }

    public function nilaiPkl()
    {
        return $this->hasOne(NilaiPkl::class, 'id_pkl');
    }
    /**
 * Ambil batas akhir 30 hari magang
 */
public function batasMagang()
{
    // Use Asia/Jakarta timezone for all date calculations
    return Carbon::parse($this->tgl_mulai, 'Asia/Jakarta')->copy()->addDays(30);
}

/**
 * Cek apakah masih dalam fase logbook (30 hari pertama)
 */
public function isFaseLogbook()
{
    return Carbon::now('Asia/Jakarta')->lte($this->batasMagang());
}
/**
 * Official single check whether PKL is ready for laporan akhir upload.
 * Rules (all must be true):
 * 1) PKL sudah berjalan minimal 30 hari (timezone Asia/Jakarta)
 * 2) Jumlah logbook minimal 30
 * 3) Semua logbook memiliki status_approve = 'approved'
 */
public function isSiapUploadLaporan()
{
    $now = Carbon::now('Asia/Jakarta');

    // 1) Pastikan sudah lewat 30 hari
    $batas = $this->batasMagang();
    if ($now->lte($batas)) {
        return false;
    }

    // 2) minimal 30 logbook
    $totalLogbook = $this->logbook()->count();
    if ($totalLogbook < 30) {
        return false;
    }

    // 3) semua harus approved
    $approvedLogbook = $this->logbook()
        ->where('status_approve', 'approved')
        ->count();

    if ($approvedLogbook !== $totalLogbook) {
        return false;
    }

    // PKL harus berstatus aktif
    return $this->status === 'aktif';
}
    

}
?>