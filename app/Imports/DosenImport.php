<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nidn']) ||
            empty($row['nama']) ||
            empty($row['kode_prodi'])
        ) {
            return null;
        }

        $prodi = Prodi::where('kode', strtoupper(trim($row['kode_prodi'])))->first();

        if (!$prodi) {
            return null;
        }

        $dosen = Dosen::updateOrCreate(
            ['nidn' => trim($row['nidn'])],
            [
                'nama'      => trim($row['nama']),
                'keahlian'  => trim($row['keahlian'] ?? null),
                'no_hp'     => trim($row['no_hp'] ?? null),
                'prodi_id'  => $prodi->id,
                'is_active' => 1,
            ]
        );

        return $dosen;
    }
}