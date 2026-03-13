<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasMitraSubmit extends Model
{
    protected $table = 'tugas_mitra_submit';

    protected $fillable = [
        'id_tugas',
        'id_pkl',
        'laporan',
        'file',
        'status',
        'revisi',
        'catatan_revisi'
    ];

    public function tugas()
    {
        return $this->belongsTo(TugasMitra::class, 'id_tugas');
    }

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}