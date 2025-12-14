<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class SuratPengantar extends Model
{
    protected $table = 'surat_pengantar';

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
?>