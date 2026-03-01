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
            empty($row['id_fakultas'])
        ) {
            return null;
        }

        // Pastikan fakultas ada
        $fakultas = Fakultas::find($row['id_fakultas']);
        if (!$fakultas) {
            return null;
        }

        return Prodi::updateOrCreate(
            ['kode' => strtoupper(trim($row['kode']))],
            [
                'nama' => trim($row['nama']),
                'id_fakultas' => $fakultas->id,
                'is_active' => 1,
            ]
        );
    }
}