<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Mitra extends Model
{
    protected $table = 'mitra';
    protected $fillable = [
    'user_id',
    'tempat_pkl_id',
    'jabatan',
    'no_hp'
];
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi ke Tempat PKL
    public function tempatPkl()
    {
        return $this->belongsTo(TempatPkl::class);
    }
}