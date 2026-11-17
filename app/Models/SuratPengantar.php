<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pkl;

class SuratPengantar extends Model
{
    protected $table = 'surat_pengantar';
    protected $fillable = [
        'id_pkl',
        'no_surat',
        'tgl_terbit',
        'path_file',
    ];
    public $timestamps = true;

    public function pkl()
    {
        return $this->belongsTo(Pkl::class, 'id_pkl');
    }

    /**
     * Generate format nomor surat pengantar: ...../UNISLA/PP/{BulanRomawi}/{Tahun}
     */
    public static function generateNomorSurat(?\DateTimeInterface $date = null): string
    {
        $date = $date ?? now();
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        $month = (int) $date->format('n');
        $romawi = $bulanRomawi[$month] ?? 'I';
        $year = $date->format('Y');

        return "...../UNISLA/PP/{$romawi}/{$year}";
    }
}
