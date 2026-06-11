<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Fakultas;
use App\Models\Prodi;
class SiakadService
{
    /**
     * Ambil data nilai mahasiswa dari API SIAKAD.
     */
    public function getNilaiMahasiswa(string $nim): array
    {
        $cacheKey = "siakad_nilai_{$nim}";

        /**
         * Ambil dari cache jika tersedia
         */
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->send(
                'GET',
                env('SIAKAD_BASE_URL') . '/nilai',
                [
                    'json' => [
                        'nim' => $nim,
                        'tahunsms' => '20252',
                    ],
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

            $data = [
                'success' => true,
                'message' => 'Berhasil ambil data',
                'data' => $response->json('data', []),
            ];

            /**
             * Cache hanya jika sukses
             */
            Cache::put(
                $cacheKey,
                $data,
                now()->addMinutes(10)
            );

            return $data;

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
    public function clearCache(string $nim): void
    {
        Cache::forget("siakad_nilai_{$nim}");
    }
    public function findMahasiswaByNim(string $nim): ?array
    {
        try {

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->get(
                env('SIAKAD_BASE_URL') . '/daftarmhs'
            );

            if (!$response->successful()) {

                Log::error('Gagal ambil daftar mahasiswa SIAKAD', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return null;
            }

            // Ambil isi array mahasiswa dari key data
            $data = $response->json('data', []);

            $mahasiswa = collect($data)
                ->firstWhere('NIM', $nim);

            if (!$mahasiswa) {
                return null;
            }

            return [
                'nim'            => $mahasiswa['NIM'],
                'nama'           => $mahasiswa['NAMAMHS'],
                'angkatan'       => (int) substr($mahasiswa['KELASMHS'], 0, 4),
                'jenis_kelamin'  => $mahasiswa['JENISKELAMIN'] ?? null,
                'kelas'          => $mahasiswa['KELASMHS'] ?? null,
            ];

        } catch (\Exception $e) {

            Log::error('Error cek mahasiswa SIAKAD', [
                'nim' => $nim,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function findDosenByNidn(string $nidn): ?array
    {
        try {

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->get(
                env('SIAKAD_BASE_URL') . '/daftardosen'
            );

            if (!$response->successful()) {

                Log::error('Gagal ambil daftar dosen SIAKAD', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json('data', []);

            $dosen = collect($data)
                ->firstWhere('NIDN', $nidn);

            if (!$dosen) {
                return null;
            }

            return [
                'nidn' => $dosen['NIDN'],
                'nama' => $dosen['NAMA'],
            ];

        } catch (\Exception $e) {

            Log::error('Error cek dosen SIAKAD', [
                'nidn' => $nidn,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function syncProdiDanFakultas(): void
    {
        try {

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
            ])->timeout(30)
            ->get(
                env('SIAKAD_BASE_URL') . '/daftarprodi'
            );

            if (!$response->successful()) {

                Log::error('Gagal sinkronisasi prodi', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return;
            }

            $items = $response->json('data', []);

            foreach ($items as $item) {

                /*
                |--------------------------------------------------------------------------
                | Fakultas
                |--------------------------------------------------------------------------
                */

                $fakultas = Fakultas::firstOrCreate(
                    [
                        'nama' => trim(
                            $item['NAMAFAKULTAS']
                        )
                    ],
                    [
                        'is_active' => true
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Prodi
                |--------------------------------------------------------------------------
                */

                Prodi::updateOrCreate(
                    [
                        'kode' => trim(
                            $item['KODEPRODI']
                        )
                    ],
                    [
                        'nama' => trim(
                            $item['NAMAPRODI']
                        ),
                        'fakultas_id' => $fakultas->id,
                        'is_active' => true,
                    ]
                );
            }

        } catch (\Exception $e) {

            Log::error(
                'Error sinkronisasi prodi/fakultas',
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
}