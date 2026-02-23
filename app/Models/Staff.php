<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Staff extends Model
{
    use HasFactory;
    protected $fillable = [
        'nip', 'nama', 'prodi_id', 'jabatan', 'no_hp', 'is_active'
    ];
    protected $attributes = [
        'is_active' => true,
    ];
    public function prodi()
{
    return $this->belongsTo(Prodi::class);
}
    public function user() {
        return $this->hasOne(User::class);
    }
}