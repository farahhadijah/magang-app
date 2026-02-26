<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $table = 'formulir';
    protected $fillable = [
        'nama',
        'file_path',
        'prodi_id',
        'is_active'
    ];
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}