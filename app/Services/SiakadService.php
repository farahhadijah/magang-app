<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SiakadService
{
    /**
     * Melakukan HTTP request menggunakan native PHP streams (tanpa CURL)
     */
    private function httpRequest(string $method, string $url, ?array $data = null, int $timeout = 15): array
    {
        try {
            $headers = [
                'x-api-key: '.env('SIAKAD_API_KEY'),
                'Accept: application/json',
                'Content-Type: application/json',
            ];

            $context_options = [
                'http' => [
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                    'timeout' => $timeout,
                    'ignore_errors' => true,
                ],
            ];

            // Jika ada data, jadikan JSON dan masukkan ke body
            if ($data !== null) {
                $json_data = json_encode($data);
                $context_options['http']['content'] = $json_data;
                $context_options['http']['header'] .= "\r\nContent-Length: ".strlen($json_data);
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
                    'json' => [],
                ];
            }

            return [
                'successful' => $status_code >= 200 && $status_code < 300,
                'status' => $status_code,
                'body' => $response,
                'json' => json_decode($response, true) ?? [],
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
                'json' => [],
            ];
        }
    }

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
            env('SIAKAD_BASE_URL').'/nilai',
            $payload,
            15
        );

        if (! $response['successful']) {
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

    public function getNilaiMahasiswaPadaSemester(
        string $nim,
        string $tahunSms
    ): array {
        $cacheKey = "siakad_nilai_{$nim}_{$tahunSms}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $payload = [
            'nim' => $nim,
            'tahunsms' => $tahunSms,
        ];
        $response = $this->httpRequest(
            'GET',
            env('SIAKAD_BASE_URL') . '/nilai',
            $payload,
            15
        );
        if (! $response['successful']) {
            Log::error('Gagal mengambil nilai mahasiswa berdasarkan semester', [
                'nim' => $nim,
                'tahun_sms' => $tahunSms,
                'status' => $response['status'],
                'response' => $response['body'],
            ]);
            return [
                'success' => false,
                'message' => 'Gagal mengambil data dari SIAKAD',
                'data' => [],
            ];
        }
        $data = $this->getJsonValue($response, 'data', []);
        if (! is_array($data)) {
            Log::warning('Format data nilai SIAKAD tidak valid', [
                'nim' => $nim,
                'tahun_sms' => $tahunSms,
            ]);
            return [
                'success' => false,
                'message' => 'Format data SIAKAD tidak valid',
                'data' => [],
            ];
        }
        $result = [
            'success' => true,
            'message' => 'Berhasil mengambil data',
            'data' => $data,
        ];
        Cache::put(
            $cacheKey,
            $result,
            now()->addMinutes(10)
        );
        return $result;
    }

    public function getNilaiBermasalah(string $nim): array
    {
        $resp = $this->getNilaiMahasiswa($nim);
        if (
            ! is_array($resp) ||
            ! isset($resp['success']) ||
            $resp['success'] === false
        ) {
            return [];
        }

        $items = $resp['data'] ?? [];

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->filter(fn ($item) => in_array(
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
        if (! is_array($resp)) {
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
        return ! $this->hasNilaiDE($nim);
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
                env('SIAKAD_BASE_URL').'/daftarmhs',
                null,
                30
            );

            if (! $response['successful']) {
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

            if (! $mahasiswa) {
                return null;
            }

            return [
                'nim' => $mahasiswa['NIM'],
                'nama' => $mahasiswa['NAMAMHS'],
                'angkatan' => (int) substr($mahasiswa['KELASMHS'], 0, 4),
                'jenis_kelamin' => $mahasiswa['JENISKELAMIN'] ?? null,
                'kelas' => $mahasiswa['KELASMHS'] ?? null,
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
                env('SIAKAD_BASE_URL').'/daftardosen',
                null,
                30
            );

            if (! $response['successful']) {
                Log::error('Gagal ambil daftar dosen SIAKAD', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);

                return null;
            }

            $data = $this->getJsonValue($response, 'data', []);

            $dosen = collect($data)
                ->firstWhere('NIDN', $nidn);

            if (! $dosen) {
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
                env('SIAKAD_BASE_URL').'/daftarprodi',
                null,
                30
            );

            if (! $response['successful']) {
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

    public function syncMahasiswa(int $prodiId, int $tahunmasuk): int
    {
        try {
            /*
            * Cari Prodi lokal berdasarkan ID yang dipilih admin.
            * Kolom kode menyimpan KODEPRODI dari SIAKAD.
            */
            $prodi = Prodi::where('id', $prodiId)
                ->where('is_active', true)
                ->first();

            if (! $prodi) {
                Log::error('Prodi tidak ditemukan saat sinkronisasi mahasiswa', [
                    'prodi_id' => $prodiId,
                ]);

                return 0;
            }

            /*
            * Data yang dikirim ke API SIAKAD.
            *
            * Contoh:
            * kodeprodi  = 55201
            * tahunmasuk = 2024
            */
            $payload = [
                'kodeprodi' => $prodi->kode,
                'tahunmasuk' => (string) $tahunmasuk,
            ];

            /*
            * Endpoint /daftarmhs menggunakan GET
            * dengan JSON body.
            */
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL').'/daftarmhs',
                $payload,
                60
            );

            if (! $response['successful']) {
                Log::error('Gagal sinkronisasi mahasiswa', [
                    'prodi_id' => $prodiId,
                    'kodeprodi' => $prodi->kode,
                    'tahunmasuk' => $tahunmasuk,
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);

                return 0;
            }

            /*
            * Ambil data mahasiswa dari response API.
            */
            $items = $this->getJsonValue($response, 'data', []);

            if (! is_array($items)) {
                Log::error('Format data mahasiswa SIAKAD tidak valid', [
                    'prodi_id' => $prodiId,
                    'kodeprodi' => $prodi->kode,
                    'tahunmasuk' => $tahunmasuk,
                ]);

                return 0;
            }

            $total = 0;

            foreach ($items as $item) {
                /*
                * Pastikan data wajib tersedia.
                */
                if (
                    empty($item['NIM']) ||
                    empty($item['NAMAMHS']) ||
                    empty($item['KELASMHS'])
                ) {
                    continue;
                }

                $nim = trim($item['NIM']);
                $nama = trim($item['NAMAMHS']);
                $kelas = trim($item['KELASMHS']);

                /*
                * Angkatan tetap diambil dari KELASMHS.
                *
                * Contoh:
                * 2024A → 2024
                * 2023P → 2023
                */
                $angkatan = (int) substr($kelas, 0, 4);

                /*
                * Simpan/update mahasiswa.
                *
                * Yang paling penting:
                * prodi_id sekarang otomatis diisi dengan
                * ID Prodi lokal yang dipilih admin.
                */
                Mahasiswa::updateOrCreate(
                    [
                        'nim' => $nim,
                    ],
                    [
                        'nama' => $nama,
                        'angkatan' => $angkatan,
                        'prodi_id' => $prodi->id,
                        'is_active' => true,
                    ]
                );

                $total++;
            }

            Log::info('Sinkronisasi mahasiswa berhasil', [
                'prodi_id' => $prodi->id,
                'prodi' => $prodi->nama,
                'kodeprodi' => $prodi->kode,
                'tahunmasuk' => $tahunmasuk,
                'jumlah' => $total,
            ]);

            return $total;

        } catch (\Exception $e) {
            Log::error('Error sinkronisasi mahasiswa', [
                'prodi_id' => $prodiId,
                'tahunmasuk' => $tahunmasuk,
                'message' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    public function syncDosen(int $prodiId): int
    {
        try {
            $prodi = Prodi::where('id', $prodiId)
                ->where('is_active', true)
                ->first();

            if (! $prodi) {
                Log::error('Prodi tidak ditemukan saat sinkronisasi dosen', [
                    'prodi_id' => $prodiId,
                ]);

                return 0;
            }

            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL').'/daftardosen',
                [
                    'kodeprodi' => (string) $prodi->kode,
                ],
                60
            );

            if (! $response['successful']) {
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
                        'nidn' => trim($item['NIDN']),
                    ],
                    [
                        'nama' => trim($item['NAMA']),
                        'prodi_id' => $prodi->id,
                        'jabatan' => 'dosen',
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
    public function debugNilaiMahasiswa(string $nim, string $tahunsms): array
    {
        $payload = [
            'nim' => $nim,
            'tahunsms' => $tahunsms,
        ];

        return $this->httpRequest(
            'GET',
            env('SIAKAD_BASE_URL').'/nilai',
            $payload,
            15
        );
    }

    public function getTahunSemesterAktif(): ?string
    {
        $cacheKey = 'siakad_tahun_semester_aktif';
        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $response = $this->httpRequest(
                'GET',
                env('SIAKAD_BASE_URL') . '/setting/ta',
                null,
                15
            );
            if (! $response['successful']) {
                Log::error('Gagal mengambil tahun semester aktif SIAKAD', [
                    'status' => $response['status'],
                    'response' => $response['body'],
                ]);
                return null;
            }
            $tahunSms = data_get($response['json'], 'data.TAHUNSMS');
            if (
                ! is_string($tahunSms) ||
                ! preg_match('/^\d{4}[12]$/', $tahunSms)
            ) {
                Log::warning('Format TAHUNSMS SIAKAD tidak valid', [
                    'tahun_sms' => $tahunSms,
                ]);
                return null;
            }
            return $tahunSms;
        });
    }

    public function getTahunSemesterValidasi(): ?string
    {
        $tahunSmsAktif = $this->getTahunSemesterAktif();
        if (! $tahunSmsAktif) {
            return null;
        }
        $tahun = (int) substr($tahunSmsAktif, 0, 4);
        $semester = (int) substr($tahunSmsAktif, 4, 1);
        if ($semester === 1) {
            $tahun--;
            $semester = 2;
        } else {
            $semester = 1;
        }
        return sprintf('%04d%d', $tahun, $semester);
    }

    public function hasKrsValidasi(string $nim): ?bool
    {
        $tahunSmsValidasi = $this->getTahunSemesterValidasi();
        if (! $tahunSmsValidasi) {
            return null;
        }
        $response = $this->getNilaiMahasiswaPadaSemester(
            $nim,
            $tahunSmsValidasi
        );
        if (
            ! isset($response['success']) ||
            $response['success'] !== true
        ) {
            return null;
        }
        $data = $response['data'] ?? [];
        return count($data) > 0;
    }
}
