<?php

namespace App\Imports;

use App\Models\Fakultas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FakultasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama'])) {
            return null;
        }

        return Fakultas::updateOrCreate(
            ['nama' => trim($row['nama'])],
            [
                'is_active' => 1,
            ]
        );
    }
}