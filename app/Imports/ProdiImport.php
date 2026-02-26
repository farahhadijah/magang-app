<?php

namespace App\Imports;

use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode']) || empty($row['nama'])) {
            return null;
        }

        return Prodi::updateOrCreate(
            ['kode' => strtoupper(trim($row['kode']))],
            [
                'nama' => trim($row['nama']),
                'is_active' => 1,
            ]
        );
    }
}