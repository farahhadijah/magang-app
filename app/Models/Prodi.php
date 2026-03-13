<?php
namespace App\Models;
use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    protected $table = 'prodi';
    protected $fillable = [
        'kode',
        'nama',
        'fakultas_id',
        'is_active'
    ];
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }
     public function staff()
    {
        return $this->hasMany(Staff::class);
    }
     public function dosen()
    {
        return $this->hasMany(Dosen::class);
    }
    public function fakultas()
{
    return $this->belongsTo(Fakultas::class);
}
}