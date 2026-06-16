<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Dosen;
class SiakadService
{
    public function getNilaiMahasiswa(string $nim): array
    {
        $cacheKey = "siakad_nilai_{$nim}";
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
            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
            ])->timeout(30)
            ->get(env('SIAKAD_BASE_URL') . '/daftarprodi');

            if (!$response->successful()) {
                Log::error('Gagal sinkronisasi prodi/fakultas', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return null;
            }

            return $response->json('data', []);
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

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(60)
            ->get(env('SIAKAD_BASE_URL') . '/daftarmhs');

            if (!$response->successful()) {

                Log::error('Gagal sinkronisasi mahasiswa', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return 0;
            }

            $items = $response->json('data', []);

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

            $response = Http::withHeaders([
                'x-api-key' => env('SIAKAD_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(60)
            ->get(env('SIAKAD_BASE_URL') . '/daftardosen');

            if (!$response->successful()) {

                Log::error('Gagal sinkronisasi dosen', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return 0;
            }

            $items = $response->json('data', []);

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