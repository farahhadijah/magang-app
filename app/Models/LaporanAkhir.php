<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;
use App\Models\User;
use Carbon\Carbon;

class LaporanAkhir extends Model
{
    protected $table = 'laporan_akhir';

    protected $fillable = [
        'id_pkl',
        'path_file',
        'status_approve',
        'catatan_dosen',
        'approved_by',
        'approved_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Approve laporan akhir
     */
    public function approve($dosenId)
    {
        $this->update([
            'status_approve' => 'approved',
            'approved_by'    => $dosenId,
            'approved_at'    => Carbon::now(),
        ]);

        // Auto selesaikan PKL
        $this->pkl->selesaikanPkl();
    }

    /**
     * Reject laporan akhir
     */
    public function reject($dosenId, $catatan = null)
    {
        $this->update([
            'status_approve' => 'rejected',
            'approved_by'    => $dosenId,
            'approved_at'    => Carbon::now(),
            'catatan_dosen'  => $catatan,
        ]);

        // Kembalikan status PKL jadi aktif
        $this->pkl->update([
            'status' => 'aktif'
        ]);
    }
}