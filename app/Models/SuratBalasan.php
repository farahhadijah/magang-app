<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratBalasan extends Model
{
    protected $table = 'surat_balasan';

    protected $fillable = [
        'id_pkl',
        'path_file',
    ];

    public function pkl(): BelongsTo
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }
}