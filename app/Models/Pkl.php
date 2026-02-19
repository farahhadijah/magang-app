<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPkl;
use App\Models\Logbook;
use App\Models\Dosen;
use App\Models\SuratPengantar;
use App\Models\LaporanAkhir;
use App\Models\NilaiPkl;
use App\Models\Mahasiswa;
use Carbon\Carbon;
class Pkl extends Model
{
    protected $table = 'pkl';
    protected $fillable = [
        'id_pengajuan_pkl',
        'id_dosen',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];
    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
    ];
    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'id_pkl');
    }
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            PengajuanPkl::class,
            'id',
            'id',
            'id_pengajuan_pkl',
            'id_mhs'
        );
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
    public function totalLogbook()
    {
        return $this->logbooks()->count();
    }
    public function totalLogbookApproved()
    {
        return $this->logbooks()
            ->where('status_approve', 'approved')
            ->count();
    }
    public function semuaLogbookApproved()
    {
        return $this->totalLogbook() > 0 &&
               $this->totalLogbook() === $this->totalLogbookApproved();
    }
    public function isSiapUploadLaporan()
    {
        if ($this->status !== 'aktif') {
            return false;
        }
        if ($this->totalLogbook() < 30) {
            return false;
        }
        return $this->semuaLogbookApproved();
    }
    public function ajukanLaporan()
    {
        $this->update([
            'status' => 'menunggu_laporan'
        ]);
    }
    public function selesaikanPkl()
    {
        $this->update([
            'status'       => 'selesai',
            'tgl_selesai'  => Carbon::now(),
        ]);
    }
}