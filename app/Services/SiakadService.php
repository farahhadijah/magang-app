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
            ])->send(
                'GET',
                env('SIAKAD_BASE_URL') . '/nilai',
                [
                    'json' => [
                        'nim' => $nim,
                        'tahunsms' => '20252',
                    ]
                ]
            );

            /**
             * Request gagal
             */
            if (!$response->successful()) {

                Log::error('Gagal ambil nilai SIAKAD', [
                    'nim' => $nim,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari SIAKAD',
                    'data' => [],
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil ambil data',
                'data' => $response->json('data', []),
            ];

        } catch (\Exception $e) {

            Log::error('Error koneksi SIAKAD', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Server SIAKAD sedang bermasalah',
                'data' => [],
            ];
        }
    }

    /**
     * Ambil nilai D/E.
     */
    public function getNilaiBermasalah(string $nim): array
{
    $resp = $this->getNilaiMahasiswa($nim);

    if (
        !is_array($resp) ||
        !isset($resp['success']) ||
        $resp['success'] === false
    ) {
        return [];
    }

    $items = $resp['data'] ?? [];

    return collect($items)
        ->filter(fn($item) => is_array($item))
        ->filter(fn($item) =>
            in_array(
                strtoupper($item['NILAI'] ?? ''),
                ['D', 'E']
            )
        )
        ->values()
        ->toArray();
}

    /**
     * Apakah mahasiswa punya nilai D/E.
     */
    public function hasNilaiDE(string $nim): bool
    {
        // gunakan getNilaiMahasiswa untuk mengecek status koneksi/response
        $resp = $this->getNilaiMahasiswa($nim);

        if (!is_array($resp)) {
            return true;
        }

        if (isset($resp['success']) && $resp['success'] === false) {
            // jika API gagal, anggap masih punya masalah sehingga tidak boleh ajukan
            return true;
        }

        // ambil daftar matakuliah bermasalah
        $items = $this->getNilaiBermasalah($nim);
        return count($items) > 0;
    }

    /**
     * Apakah boleh ajukan PKL.
     */
    public function canAjukanPKL(string $nim): bool
    {
        /**
         * Hanya boleh jika benar-benar aman.
         */
        return !$this->hasNilaiDE($nim);
    }

    /**
 * Cek apakah API SIAKAD sedang aktif.
 */
public function isApiAvailable(string $nim): bool
{
    $resp = $this->getNilaiMahasiswa($nim);

    return is_array($resp)
        && isset($resp['success'])
        && $resp['success'] === true;
}
}