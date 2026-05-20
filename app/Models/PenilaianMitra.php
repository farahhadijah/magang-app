<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianMitra extends Model
{
    protected $table = 'penilaian_mitra';

    protected $fillable = [

        'id_pkl',

        'kedisiplinan',
        'kreativitas',
        'ketekunan',
        'kerjasama',
        'kejujuran',
        'kesopanan',
        'semangat_kerja',
        'kedalaman_materi',

        'rata_rata',
        'grade',

        'verification_token',

        'file_pdf',
        'file_pdf_signed',

        'tgl_input',
    ];

    protected $casts = [

        'tgl_input' => 'date',

    ];

    public function pkl()
    {
        return $this->belongsTo(
            Pkl::class,
            'id_pkl'
        );
    }
}