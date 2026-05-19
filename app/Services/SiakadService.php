<?php

namespace App\Services;

class SiakadService
{
    /**
     * Simulasi ambil nilai mahasiswa dari API SIAKAD.
     */
    public function getNilaiMahasiswa(string $nim): array
    {

        $dummy = [

            // mahasiswa punya nilai D/E
            '112310090' => [
                [
                    'kode_mk' => 'IF202',
                    'nama_mk' => 'Basis Data',
                    'sks'      => 3,
                    'nilai'    => 'D',
                    'jenis'    => 'Teori',
                ],
                [
                    'kode_mk' => 'IF404',
                    'nama_mk' => 'Pemrograman Web',
                    'sks'      => 1,
                    'nilai'    => 'E',
                    'jenis'    => 'Praktikum',
                ],
            ],

            // mahasiswa aman
            '112310067' => [
                [
                    'kode_mk' => 'IF101',
                    'nama_mk' => 'Algoritma',
                    'sks'      => 3,
                    'nilai'    => 'B',
                    'jenis'    => 'Teori',
                ],
            ],
        ];

        return $dummy[$nim] ?? [];
    }

    /**
     * Ambil hanya nilai D/E.
     */
    public function getNilaiBermasalah(string $nim): array
    {
        return collect(
            $this->getNilaiMahasiswa($nim)
        )
        ->filter(function ($item) {
            return in_array($item['nilai'], ['D', 'E']);
        })
        ->values()
        ->toArray();
    }

    /**
     * Cek apakah mahasiswa punya nilai D/E.
     */
    public function hasNilaiDE(string $nim): bool
    {
        return count(
            $this->getNilaiBermasalah($nim)
        ) > 0;
    }

    /**
     * Apakah mahasiswa boleh ajukan PKL.
     */
    public function canAjukanPKL(string $nim): bool
    {
        return ! $this->hasNilaiDE($nim);
    }

    /**
     * Hitung biaya remedial.
     */
    public function hitungBiayaRemedial(string $nim): int
    {
        $total = 0;

        foreach ($this->getNilaiBermasalah($nim) as $mk) {

            if ($mk['jenis'] === 'praktikum') {
                $total += 500000;
            } else {
                $total += 300000;
            }
        }

        return $total;
    }
}