<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class NilaiPkl extends Model
{
    protected $table = 'nilai_pkl';

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
?>