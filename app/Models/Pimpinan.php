<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    protected $table = 'pimpinan';

    protected $fillable = [
        'nip',
        'nama',
        'no_hp',
        'fakultas_id',
        'is_active',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}