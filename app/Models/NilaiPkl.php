<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;
class NilaiPkl extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'id_pkl',
        'nilai_angka',
        'nilai_huruf',
        'keterangan',
        'tgl_input',
        'status_approval',      // NEW: pending, approved, rejected
        'tgl_approval',    // NEW: catatan jika reject
    ];
    protected $table = 'nilai_pkl';
    protected $casts = [
        'tgl_approval' => 'datetime',
    ];
    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
