<?php

namespace App\Imports;

use App\Models\Prodi;
use App\Models\Fakultas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['kode']) ||
            empty($row['nama']) ||
            empty($row['fakultas_id'])
        ) {
            return null;
        }

        // Pastikan fakultas ada
        $fakultas = Fakultas::find($row['fakultas_id']);
        if (!$fakultas) {
            return null;
        }

        return Prodi::updateOrCreate(
            ['kode' => strtoupper(trim($row['kode']))],
            [
                'nama' => trim($row['nama']),
                'fakultas_id' => $fakultas->id,
                'is_active' => 1,
            ]
        );
    }
}