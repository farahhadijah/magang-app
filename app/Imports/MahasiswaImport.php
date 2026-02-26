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
        // =============================
        // STEP 6 — VALIDASI HEADER
        // =============================

        if ($rows->isEmpty()) {
            throw new \Exception('File kosong.');
        }

        $expected = ['nim','nama','angkatan','no_hp','kode_prodi'];
        $actual = array_keys($rows->first()->toArray());

        if ($actual !== $expected) {
            throw new \Exception(
                'Format header tidak sesuai template resmi.'
            );
        }

        // =============================
        // STEP 7 — VALIDASI PER BARIS
        // =============================

        foreach ($rows as $index => $row) {

            $barisExcel = $index + 2; 
            // +2 karena:
            // baris 1 = header
            // index mulai dari 0

            if (!$row['nim'] || !$row['nama']) {
                throw new \Exception(
                    "Baris {$barisExcel} data tidak lengkap."
                );
            }

            if (!is_numeric($row['angkatan']) || strlen($row['angkatan']) != 4) {
                throw new \Exception(
                    "Baris {$barisExcel} angkatan harus 4 digit."
                );
            }

            $prodi = Prodi::where('kode', $row['kode_prodi'])->first();

            if (!$prodi) {
                throw new \Exception(
                    "Baris {$barisExcel} kode prodi tidak valid."
                );
            }

            // =============================
            // SIMPAN KE DATABASE
            // =============================

            Mahasiswa::create([
                'nim'        => $row['nim'],
                'nama'       => $row['nama'],
                'angkatan'   => $row['angkatan'],
                'no_hp'      => $row['no_hp'],
                'prodi_id'   => $prodi->id,
            ]);
        }
    }
}