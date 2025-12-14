<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class Logbook extends Model
{
    protected $table = 'logbook';

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}
?>
