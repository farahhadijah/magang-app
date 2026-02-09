<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class Logbook extends Model
{
    public $timestamps = true;
    protected $table = 'logbook';
    protected $casts = [
        'tgl' => 'date', // otomatis jadi Carbon
    ];
    protected $fillable = ['id_pkl', 'tgl', 'kegiatan', 'status_approve'];

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
    public function mahasiswa()
{
    return $this->hasOneThrough(
        Mahasiswa::class, // model tujuan
        Pkl::class,       // model penghubung
        'id',             // PK di Pkl (foreign key di logbook = id_pkl)
        'id',             // PK di mahasiswa
        'id_pkl',         // FK di Logbook
        'id_mhs'          // FK di Pkl
    );
}

}
?>