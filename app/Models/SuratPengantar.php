<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;
class SuratPengantar extends Model
{
    protected $table = 'surat_pengantar';
    protected $fillable = [
        'id_pkl',
        'no_surat',
        'tgl_terbit',
        'path_file',
    ];
    public $timestamps = true;
    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
?>