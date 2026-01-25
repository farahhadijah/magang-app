<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip', 'nama', 'jabatan', 'no_hp', 'is_active'
    ];
    protected $attributes = [
        'is_active' => true,
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
}