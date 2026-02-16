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
    public $timestamps = true;

    protected $table = 'pkl';

    protected $fillable = [
        'id_pengajuan_pkl',
        'id_dosen',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

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

    /**
     * 🔥 Relasi Mahasiswa lewat PengajuanPkl
     * pkl.id_pengajuan_pkl → pengajuan_pkl.id → pengajuan_pkl.id_mhs → mahasiswa.id
     */
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            PengajuanPkl::class,
            'id',               // Foreign key di pengajuan_pkl
            'id',               // Foreign key di mahasiswa
            'id_pengajuan_pkl', // Local key di pkl
            'id_mhs'            // Local key di pengajuan_pkl
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

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil batas akhir 30 hari magang
     */
    public function batasMagang()
    {
        return Carbon::parse($this->tgl_mulai, 'Asia/Jakarta')
            ->copy()
            ->addDays(30);
    }

    /**
     * Cek apakah masih dalam fase logbook (30 hari pertama)
     */
    public function isFaseLogbook()
    {
        return Carbon::now('Asia/Jakarta')
            ->lte($this->batasMagang());
    }

    /**
     * Cek apakah siap upload laporan akhir
     */
    public function isSiapUploadLaporan()
    {
        $now = Carbon::now('Asia/Jakarta');

        // 1️⃣ Pastikan sudah lewat 30 hari
        if ($now->lte($this->batasMagang())) {
            return false;
        }

        // 2️⃣ Minimal 30 logbook
        $totalLogbook = $this->logbooks()->count();
        if ($totalLogbook < 30) {
            return false;
        }

        // 3️⃣ Semua logbook harus approved
        $approvedLogbook = $this->logbooks()
            ->where('status_approve', 'approved')
            ->count();

        if ($approvedLogbook !== $totalLogbook) {
            return false;
        }

        // 4️⃣ Status PKL harus aktif
        return $this->status === 'aktif';
    }
}