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
        'is_active'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}