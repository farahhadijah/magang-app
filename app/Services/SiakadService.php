<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class SiakadService
{
    /**
     * Melakukan HTTP request menggunakan native PHP streams (tanpa CURL)
     */
    private function httpRequest(string $method, string $url, ?array $data = null, int $timeout = 15): array
    {
        try {
            $headers = [
                'x-api-key: ' . env('SIAKAD_API_KEY'),
                'Accept: application/json',
                'Content-Type: application/json',
            ];

            $context_options = [
                'http' => [
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                    'timeout' => $timeout,
                    'ignore_errors' => true,
                ]
            ];

            // Jika ada data, jadikan JSON dan masukkan ke body
            if ($data !== null) {
                $json_data = json_encode($data);
                $context_options['http']['content'] = $json_data;
                $context_options['http']['header'] .= "\r\nContent-Length: " . strlen($json_data);
            }

            $context = stream_context_create($context_options);
            $response = @file_get_contents($url, false, $context);

            // Ambil HTTP response headers
            $status_code = 500;
            if (isset($http_response_header) && is_array($http_response_header)) {
                if (preg_match('/HTTP\/\d\.\d (\d+)/', $http_response_header[0], $matches)) {
                    $status_code = (int) $matches[1];
                }
            }

            if ($response === false) {
                return [
                    'successful' => false,
                    'status' => $status_code,
                    'body' => '',
                    'json' => []
                ];
            }

            return [
                'successful' => $status_code >= 200 && $status_code < 300,
                'status' => $status_code,
                'body' => $response,
                'json' => json_decode($response, true) ?? []
            ];

        } catch (\Exception $e) {
            Log::error('HTTP Request Error', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            return [
                'successful' => false,
                'status' => 500,
                'body' => $e->getMessage(),
                'json' => []
            ];
        }
    }

    /**
     * Helper untuk mengakses nested value dari response JSON
     */
    private function getJsonValue(array $response, string $key, $default = null)
    {
        $data = $response['json'] ?? [];
        return (is_array($data) && isset($data[$key])) ? $data[$key] : $default;
    }

    public function getNilaiMahasiswa(string $nim): array
    {
        $cacheKey = "siakad_nilai_{$nim}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $payload = [
            'nim' => $nim,
            'tahunsms' => '20252',
        ];

        $response = $this->httpRequest(
            'GET',
            env('SIAKAD_BASE_URL') . '/nilai',
            $payload,
            15
        );

        if (!$response['successful']) {
            Log::error('Gagal ambil nilai SIAKAD', [
                'nim' => $nim,
                'status' => $response['status'],
                'response' => $response['body'],
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
            'data' => $this->getJsonValue($response, 'data', []),
        ];

        Cache::put(
            $cacheKey,
            $data,
            now()->addMinutes(10)
        );

        return $data;
    }

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

    public function hasNilaiDE(string $nim): bool
    {
        $resp = $this->getNilaiMahasiswa($nim);
        if (!is_array($resp)) {
            return true;
        }
        if (isset($resp['success']) && $resp['success'] === false) {
            return true;
        }
        $items = $this->getNilaiBermasalah($nim);
        return count($items) > 0;
    }

    public function canAjukanPKL(string $nim): bool
    {
        return !$this->hasNilaiDE($nim);
    }

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
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/daftarmhs',
                null,
                30
            );

            if (!$response['successful']) {
                Log::error('Gagal ambil daftar mahasiswa SIAKAD', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return null;
            }

            // Ambil isi array mahasiswa dari key data
            $data = $this->getJsonValue($response, 'data', []);

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
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/daftardosen',
                null,
                30
            );

            if (!$response['successful']) {
                Log::error('Gagal ambil daftar dosen SIAKAD', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return null;
            }

            $data = $this->getJsonValue($response, 'data', []);

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

    public function syncFakultas(): int
    {
        $items = $this->fetchDaftarProdi();

        if ($items === null) {
            return 0;
        }

        $total = 0;

        foreach (collect($items)->pluck('NAMAFAKULTAS')->unique() as $namaFakultas) {
            if (blank(trim((string) $namaFakultas))) {
                continue;
            }

            $this->findOrCreateFakultas((string) $namaFakultas);
            $total++;
        }

        return $total;
    }

    public function syncProdi(): int
    {
        $items = $this->fetchDaftarProdi();

        if ($items === null) {
            return 0;
        }

        $total = 0;

        foreach ($items as $item) {
            $kode = strtoupper(trim((string) ($item['KODEPRODI'] ?? '')));

            if ($kode === '') {
                continue;
            }

            $fakultas = $this->findOrCreateFakultas(
                (string) ($item['NAMAFAKULTAS'] ?? '')
            );

            Prodi::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama' => trim((string) ($item['NAMAPRODI'] ?? '')),
                    'fakultas_id' => $fakultas->id,
                    'is_active' => true,
                ]
            );

            $total++;
        }

        return $total;
    }

    private function fetchDaftarProdi(): ?array
    {
        try {
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/daftarprodi',
                null,
                30
            );

            if (!$response['successful']) {
                Log::error('Gagal sinkronisasi prodi/fakultas', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return null;
            }

            return $this->getJsonValue($response, 'data', []);

        } catch (\Exception $e) {
            Log::error('Error sinkronisasi prodi/fakultas', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function findOrCreateFakultas(string $nama): Fakultas
    {
        $nama = trim($nama);

        $existing = Fakultas::whereRaw(
            'LOWER(TRIM(nama)) = ?',
            [strtolower($nama)]
        )->first();

        if ($existing) {
            return $existing;
        }

        return Fakultas::create([
            'nama' => $nama,
            'is_active' => true,
        ]);
    }

    public function syncMahasiswa(): int
    {
        try {
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/daftarmhs',
                null,
                60
            );

            if (!$response['successful']) {
                Log::error('Gagal sinkronisasi mahasiswa', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return 0;
            }

            $items = $this->getJsonValue($response, 'data', []);

            $total = 0;

            foreach ($items as $item) {
                Mahasiswa::updateOrCreate(
                    [
                        'nim' => trim($item['NIM'])
                    ],
                    [
                        'nama'      => trim($item['NAMAMHS']),
                        'angkatan' => (int) substr($item['KELASMHS'], 0, 4),
                        'prodi_id'  => null,
                        'is_active' => true,
                    ]
                );

                $total++;
            }

            return $total;

        } catch (\Exception $e) {
            Log::error('Error sinkronisasi mahasiswa', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    public function syncDosen(): int
    {
        try {
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/daftardosen',
                null,
                60
            );

            if (!$response['successful']) {
                Log::error('Gagal sinkronisasi dosen', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return 0;
            }

            $items = $this->getJsonValue($response, 'data', []);

            $total = 0;

            foreach ($items as $item) {
                Dosen::updateOrCreate(
                    [
                        'nidn' => trim($item['NIDN'])
                    ],
                    [
                        'nama'      => trim($item['NAMA']),
                        'prodi_id'  => null,
                        'jabatan'   => null,
                        'no_hp'     => null,
                        'is_active' => true,
                    ]
                );

                $total++;
            }

            return $total;

        } catch (\Exception $e) {
            Log::error('Error sinkronisasi dosen', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }
}