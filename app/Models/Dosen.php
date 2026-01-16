<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'prodi_id',
        'keahlian',
        'no_hp',
        'is_active',
    ];

    /**
     * DEFAULT VALUE
     * mencegah error is_active NULL
     */
    protected $attributes = [
        'is_active' => true,
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'dosen_id');
    }
}