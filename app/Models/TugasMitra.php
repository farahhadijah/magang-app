<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasMitra extends Model
{
    protected $table = 'tugas_mitra';

    protected $fillable = [
        'id_pkl',
        'judul',
        'file',
        'deskripsi',
        'deadline'
    ];

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }

    public function submit()
    {
        return $this->hasMany(TugasMitraSubmit::class, 'id_tugas');
    }
}