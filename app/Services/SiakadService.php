<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SiakadService
{
    /**
     * Ambil data nilai mahasiswa dari API SIAKAD.
     */
    public function getNilaiMahasiswa(string $nim): array
    {
        try {

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->send('GET',

                env('SIAKAD_BASE_URL') . '/nilai',

                [
                    'json' => [
                        'nim' => $nim,

                        /**
                         * semester aktif
                         * bisa kamu ubah nanti menjadi dinamis
                         */
                        'tahunsms' => '20252',
                    ]
                ]
            );

            /**
             * Jika request gagal
             */
            if (!$response->successful()) {

                Log::error('Gagal ambil nilai SIAKAD', [
                    'nim' => $nim,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [];
            }

            /**
             * Ambil array data
             */
            return $response->json('data', []);

        } catch (\Exception $e) {

            Log::error('Error koneksi SIAKAD', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Ambil matakuliah nilai D/E.
     */
    public function getNilaiBermasalah(string $nim): array
    {
        return collect(
            $this->getNilaiMahasiswa($nim)
        )
        ->filter(function ($item) {

            return in_array(
                strtoupper($item['NILAI'] ?? ''),
                ['D', 'E']
            );

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
     * Hitung total biaya remedial.
     */
    public function hitungBiayaRemedial(string $nim): int
    {
        $total = 0;

        foreach ($this->getNilaiBermasalah($nim) as $mk) {

            /**
             * Deteksi praktikum dari nama MK
             */
            $isPraktikum = str_contains(
                strtolower($mk['NAMAMK'] ?? ''),
                'praktikum'
            );

            if ($isPraktikum) {
                $total += 500000;
            } else {
                $total += 300000;
            }
        }

        return $total;
    }
}