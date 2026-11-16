<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Terapkan scope data mahasiswa berdasarkan jabatan aktor:
     * - Dekan / Wadek : Mahasiswa satu fakultas (berdasarkan prodi.fakultas_id)
     * - Kaprodi       : Mahasiswa satu program studi (berdasarkan mahasiswa.prodi_id)
     */
    private function applyMahasiswaScope($query, $user)
    {
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');

        if (in_array($jabatan, ['dekan', 'wadek'])) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            if ($fakultasId) {
                $query->whereHas('prodi', function ($q) use ($fakultasId) {
                    $q->where('prodi.fakultas_id', $fakultasId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($jabatan === 'kaprodi') {
            $prodiId = $dosen?->prodi_id;
            if ($prodiId) {
                $query->where('mahasiswa.prodi_id', $prodiId);
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            abort(403, 'Anda tidak memiliki hak akses ke data mahasiswa ini.');
        }

        return $query;
    }

    /**
     * Otorisasi hak akses detail mahasiswa per aktor.
     */
    private function authorizeMahasiswaAccess(Mahasiswa $mahasiswa, $user): void
    {
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');

        if (in_array($jabatan, ['dekan', 'wadek'])) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            $mahasiswaFakultasId = $mahasiswa->prodi?->fakultas_id;

            if (! $fakultasId || (int) $mahasiswaFakultasId !== (int) $fakultasId) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat data mahasiswa di luar fakultas Anda.');
            }
        } elseif ($jabatan === 'kaprodi') {
            $prodiId = $dosen?->prodi_id;

            if (! $prodiId || (int) $mahasiswa->prodi_id !== (int) $prodiId) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat data mahasiswa di luar program studi Anda.');
            }
        } else {
            abort(403, 'Anda tidak memiliki hak akses ke data mahasiswa ini.');
        }
    }

    // Halaman daftar mahasiswa yang sudah memiliki PKL aktif
    public function index(Request $request)
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        // Khusus Dekan dan Wadek: Pengelompokan awal berdasarkan Program Studi
        if ($isPimpinanFakultas) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            if (! $fakultasId) {
                abort(403, 'Pimpinan Fakultas belum terhubung dengan fakultas.');
            }

            // Jika belum memilih prodi (prodi_id tidak ada), tampilkan pengelompokan prodi
            if (! $request->filled('prodi_id')) {
                $search = trim((string) $request->input('search', ''));
                $prodiQuery = Prodi::where('fakultas_id', $fakultasId)
                    ->withCount([
                        'mahasiswa as total_pkl_aktif' => function ($q) {
                            $q->whereHas('pengajuanPkl.pkl', function ($pklQ) {
                                $pklQ->where('status', 'aktif');
                            });
                        }
                    ]);

                if ($search !== '') {
                    $prodiQuery->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('kode', 'like', "%{$search}%");
                    });
                }

                $prodiList = $prodiQuery->orderBy('nama')->get();

                return view(
                    'kaprodi.mahasiswa.index',
                    compact('prodiList', 'isPimpinanFakultas', 'isKaprodi', 'search')
                );
            }

            // Jika prodi_id dipilih, validasi bahwa prodi tersebut berada dalam fakultas
            $selectedProdi = Prodi::where('id', $request->prodi_id)
                ->where('fakultas_id', $fakultasId)
                ->firstOrFail();

            $query = Mahasiswa::where('prodi_id', $selectedProdi->id)
                ->whereHas('pengajuanPkl.pkl', function ($q) {
                    $q->where('status', 'aktif');
                })
                ->with([
                    'prodi',
                    'pengajuanPkl.pkl' => function ($q) {
                        $q->where('status', 'aktif')
                            ->with(['dosen', 'suratBalasan']);
                    },
                ]);

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('mahasiswa.nim', 'like', "%{$search}%")
                      ->orWhere('mahasiswa.nama', 'like', "%{$search}%");
                });
            }

            $mahasiswas = $query
                ->orderBy('mahasiswa.nama')
                ->paginate(9)
                ->withQueryString();

            return view(
                'kaprodi.mahasiswa.index',
                compact('mahasiswas', 'selectedProdi', 'isPimpinanFakultas', 'isKaprodi')
            );
        }

        // Alur untuk Kaprodi (langsung tampilkan mahasiswa prodinya)
        $query = Mahasiswa::query()
            ->whereHas('pengajuanPkl.pkl', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with([
                'prodi',
                'pengajuanPkl.pkl' => function ($q) {
                    $q->where('status', 'aktif')
                        ->with(['dosen', 'suratBalasan']);
                },
            ]);

        $this->applyMahasiswaScope($query, $user);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $query
            ->orderBy('mahasiswa.nama')
            ->paginate(9)
            ->withQueryString();

        return view(
            'kaprodi.mahasiswa.index',
            compact('mahasiswas', 'isPimpinanFakultas', 'isKaprodi')
        );
    }

    // Halaman detail mahasiswa
    public function detail(Mahasiswa $mahasiswa)
    {
        $user = auth()->user();

        $this->authorizeMahasiswaAccess($mahasiswa, $user);

        $mahasiswa->load([
            'prodi.fakultas',
            'pengajuanPkl' => function ($q) {
                $q->latest('created_at')
                    ->with([
                        'tempatPkl',
                        'pkl' => function ($q) {
                            $q->where('status', 'aktif')
                                ->with([
                                    'dosen',
                                    'suratBalasan',
                                    'suratPengantar',
                                ]);
                        },
                    ]);
            },
        ]);

        return view(
            'kaprodi.mahasiswa.detail',
            compact('mahasiswa')
        );
    }

    // Halaman pertama: daftar angkatan
    public function daftarAngkatan()
    {
        $user = auth()->user();

        $query = Mahasiswa::query()
            ->whereDoesntHave('pengajuanPkl', function ($q) {
                $q->whereNotIn('status', [
                    'ditolak_tu',
                    'ditolak_kaprodi',
                ]);
            });

        $this->applyMahasiswaScope($query, $user);

        $angkatan = $query
            ->select('mahasiswa.angkatan')
            ->selectRaw('COUNT(*) as total_mahasiswa')
            ->groupBy('mahasiswa.angkatan')
            ->orderByDesc('mahasiswa.angkatan')
            ->paginate(10);

        return view(
            'kaprodi.mahasiswa.angkatan',
            compact('angkatan')
        );
    }

    // Halaman kedua: mahasiswa berdasarkan angkatan
    public function belumMengajukan(Request $request, $angkatan)
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        // Khusus Dekan & Wadek: Setelah klik card angkatan, kelompokkan berdasarkan Program Studi
        if ($isPimpinanFakultas) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            if (! $fakultasId) {
                abort(403, 'Pimpinan Fakultas belum terhubung dengan fakultas.');
            }

            // Jika belum memilih prodi (prodi_id tidak ada), tampilkan daftar prodi untuk angkatan ini
            if (! $request->filled('prodi_id')) {
                $search = trim((string) $request->input('search', ''));
                $prodiQuery = Prodi::where('fakultas_id', $fakultasId)
                    ->withCount([
                        'mahasiswa as total_belum_pkl' => function ($q) use ($angkatan) {
                            $q->where('angkatan', $angkatan)
                              ->whereDoesntHave('pengajuanPkl', function ($pQ) {
                                  $pQ->whereNotIn('status', ['ditolak_tu', 'ditolak_kaprodi']);
                              });
                        }
                    ]);

                if ($search !== '') {
                    $prodiQuery->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('kode', 'like', "%{$search}%");
                    });
                }

                $prodiList = $prodiQuery->orderBy('nama')->get();

                return view(
                    'kaprodi.mahasiswa.belum',
                    compact('prodiList', 'angkatan', 'isPimpinanFakultas', 'isKaprodi', 'search')
                );
            }

            // Jika prodi_id dipilih, validasi prodi dan tampilkan mahasiswa di prodi tersebut
            $selectedProdi = Prodi::where('id', $request->prodi_id)
                ->where('fakultas_id', $fakultasId)
                ->firstOrFail();

            $query = Mahasiswa::where('prodi_id', $selectedProdi->id)
                ->where('mahasiswa.angkatan', $angkatan)
                ->whereDoesntHave('pengajuanPkl', function ($q) {
                    $q->whereNotIn('status', [
                        'ditolak_tu',
                        'ditolak_kaprodi',
                    ]);
                })
                ->with([
                    'prodi',
                    'pengajuanPkl',
                ]);

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('mahasiswa.nim', 'like', "%{$search}%")
                      ->orWhere('mahasiswa.nama', 'like', "%{$search}%");
                });
            }

            $mahasiswas = $query
                ->orderBy('mahasiswa.nama')
                ->paginate(15)
                ->withQueryString();

            return view(
                'kaprodi.mahasiswa.belum',
                compact('mahasiswas', 'selectedProdi', 'angkatan', 'isPimpinanFakultas', 'isKaprodi')
            );
        }

        // Alur untuk Kaprodi (langsung tampilkan mahasiswa prodinya)
        $query = Mahasiswa::query()
            ->where('mahasiswa.angkatan', $angkatan)
            ->whereDoesntHave('pengajuanPkl', function ($q) {
                $q->whereNotIn('status', [
                    'ditolak_tu',
                    'ditolak_kaprodi',
                ]);
            })
            ->with([
                'prodi',
                'pengajuanPkl',
            ]);

        $this->applyMahasiswaScope($query, $user);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $query
            ->orderBy('mahasiswa.nama')
            ->paginate(15)
            ->withQueryString();

        return view(
            'kaprodi.mahasiswa.belum',
            compact('mahasiswas', 'angkatan', 'isPimpinanFakultas', 'isKaprodi')
        );
    }
}
