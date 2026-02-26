<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception('File kosong.');
        }

        foreach ($rows as $index => $row) {

            $barisExcel = $index + 2;

            if (
                empty($row['nim']) ||
                empty($row['nama']) ||
                empty($row['angkatan']) ||
                empty($row['kode_prodi'])
            ) {
                throw new \Exception("Baris {$barisExcel} data tidak lengkap.");
            }

            if (!is_numeric($row['angkatan']) || strlen($row['angkatan']) != 4) {
                throw new \Exception("Baris {$barisExcel} angkatan harus 4 digit.");
            }

            $prodi = Prodi::where('kode', strtoupper(trim($row['kode_prodi'])))->first();

            if (!$prodi) {
                throw new \Exception("Baris {$barisExcel} kode prodi tidak valid.");
            }

            Mahasiswa::updateOrCreate(
                ['nim' => trim($row['nim'])],
                [
                    'nama'       => trim($row['nama']),
                    'angkatan'   => $row['angkatan'],
                    'no_hp'      => trim($row['no_hp'] ?? null),
                    'prodi_id'   => $prodi->id,
                    'is_active'  => 1,
                ]
            );
        }
    }
}