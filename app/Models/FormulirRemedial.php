<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulirRemedial extends Model
{
    protected $table = 'formulir_remedial';

    protected $fillable = [
        'fakultas_id',
        'nama',
        'path_file',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}