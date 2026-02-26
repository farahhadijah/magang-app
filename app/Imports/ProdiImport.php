<?php

namespace App\Imports;

use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Prodi::updateOrCreate(
            ['kode' => strtoupper($row['kode'])],
            [
                'nama' => $row['nama'],
                'is_active' => 1,
            ]
        );
    }
}