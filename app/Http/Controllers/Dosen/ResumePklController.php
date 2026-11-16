<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumePklController extends Controller
{
    /**
     * Terapkan batasan lingkup (scope) data resume PKL berdasarkan jabatan:
     * - Dekan / Wadek : Mahasiswa satu fakultas
     * - Kaprodi       : Mahasiswa satu program studi
     * - Dosen         : Mahasiswa bimbingan sendiri
     */
    private function applyScope($query, $user)
    {
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');

        if (in_array($jabatan, ['dekan', 'wadek'])) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            if ($fakultasId) {
                $query->whereHas('pengajuanPkl.mahasiswa.prodi', function ($q) use ($fakultasId) {
                    $q->where('prodi.fakultas_id', $fakultasId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($jabatan === 'kaprodi') {
            $prodiId = $dosen?->prodi_id;
            if ($prodiId) {
                $query->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($prodiId) {
                    $q->where('mahasiswa.prodi_id', $prodiId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            $dosenId = $dosen?->id ?? null;
            $query->where('pkl.id_dosen', $dosenId);
        }

        return $query;
    }

    /**
     * Otorisasi hak akses detail resume PKL per aktor.
     */
    private function authorizeResumeAccess(Pkl $pkl, $user): void
    {
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');

        if (in_array($jabatan, ['dekan', 'wadek'])) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            $pkl->loadMissing('pengajuanPkl.mahasiswa.prodi');
            $mahasiswaFakultasId = $pkl->pengajuanPkl?->mahasiswa?->prodi?->fakultas_id;

            if (! $fakultasId || (int) $mahasiswaFakultasId !== (int) $fakultasId) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat resume mahasiswa di luar fakultas Anda.');
            }
        } elseif ($jabatan === 'kaprodi') {
            $prodiId = $dosen?->prodi_id ?? null;
            $pkl->loadMissing('pengajuanPkl.mahasiswa');
            $mahasiswaProdiId = $pkl->pengajuanPkl?->mahasiswa?->prodi_id;

            if (! $prodiId || (int) $mahasiswaProdiId !== (int) $prodiId) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat resume mahasiswa di luar program studi Anda.');
            }
        } else {
            $dosenId = $dosen?->id ?? null;
            if ((int) $pkl->id_dosen !== (int) $dosenId) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat resume mahasiswa bimbingan dosen lain.');
            }
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        $search = trim((string) $request->input('search', ''));

        $query = Pkl::query()
            ->where('pkl.status', 'selesai')
            ->whereHas('pengajuanPkl.mahasiswa');

        $this->applyScope($query, $user);

        if ($search !== '') {
            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($search) {
                    $q->where(
                        'mahasiswa.angkatan',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
        }

        $angkatan = $query
            ->join(
                'pengajuan_pkl',
                'pkl.id_pengajuan_pkl',
                '=',
                'pengajuan_pkl.id'
            )
            ->join(
                'mahasiswa',
                'pengajuan_pkl.id_mhs',
                '=',
                'mahasiswa.id'
            )
            ->select(
                'mahasiswa.angkatan',
                DB::raw('COUNT(pkl.id) as jumlah_mahasiswa')
            )
            ->groupBy('mahasiswa.angkatan')
            ->orderByDesc('mahasiswa.angkatan')
            ->get();

        return view(
            'dosen.resume.index',
            compact(
                'angkatan',
                'isKaprodi',
                'isPimpinanFakultas',
                'jabatan',
                'search'
            )
        );
    }

    public function mahasiswa(Request $request, $tahun)
    {
        $user = auth()->user();
        $dosen = $user->dosen;
        $jabatan = strtolower($dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        // Khusus Dekan dan Wadek: Pengelompokan awal setelah klik angkatan adalah berdasarkan Program Studi
        if ($isPimpinanFakultas) {
            $fakultasId = $dosen?->prodi?->fakultas_id;
            if (! $fakultasId) {
                abort(403, 'Pimpinan Fakultas belum terhubung dengan fakultas.');
            }

            // Jika belum memilih prodi (prodi_id tidak ada), tampilkan pengelompokan prodi untuk angkatan ini
            if (! $request->filled('prodi_id')) {
                $search = trim((string) $request->input('search', ''));
                $prodiQuery = Prodi::where('fakultas_id', $fakultasId)
                    ->withCount([
                        'mahasiswa as total_resume_pkl' => function ($q) use ($tahun) {
                            $q->where('angkatan', $tahun)
                              ->whereHas('pengajuanPkl.pkl', function ($pklQ) {
                                  $pklQ->where('status', 'selesai');
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
                    'dosen.resume.mahasiswa',
                    compact('prodiList', 'tahun', 'isKaprodi', 'isPimpinanFakultas', 'jabatan', 'search')
                );
            }

            // Jika prodi_id dipilih, validasi bahwa prodi tersebut berada dalam fakultas Dekan/Wadek
            $selectedProdi = Prodi::where('id', $request->prodi_id)
                ->where('fakultas_id', $fakultasId)
                ->firstOrFail();

            $search = trim((string) $request->input('search', ''));

            $query = Pkl::with([
                'dosen',
                'pengajuanPkl.mahasiswa.prodi',
                'pengajuanPkl.tempatPkl',
                'nilaiPkl',
                'laporanAkhir',
                'logbooks',
                'penilaianMitra',
                'suratPengantar',
                'suratBalasan',
            ])
                ->where('pkl.status', 'selesai')
                ->whereHas('pengajuanPkl.mahasiswa', function ($q) use ($tahun, $selectedProdi) {
                    $q->where('mahasiswa.angkatan', $tahun)
                      ->where('mahasiswa.prodi_id', $selectedProdi->id);
                });

            if ($search !== '') {
                $query->whereHas(
                    'pengajuanPkl.mahasiswa',
                    function ($q) use ($search) {
                        $q->where(function ($inner) use ($search) {
                            $inner->where(
                                'mahasiswa.nim',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'mahasiswa.nama',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                    }
                );
            }

            $pkls = $query
                ->orderByDesc('pkl.tgl_selesai')
                ->paginate(10)
                ->withQueryString();

            return view(
                'dosen.resume.mahasiswa',
                compact(
                    'pkls',
                    'selectedProdi',
                    'tahun',
                    'isKaprodi',
                    'isPimpinanFakultas',
                    'jabatan',
                    'search'
                )
            );
        }

        // Alur untuk Kaprodi & Dosen Biasa (langsung tampilkan mahasiswa dalam lingkupnya)
        $search = trim((string) $request->input('search', ''));

        $query = Pkl::with([
            'dosen',
            'pengajuanPkl.mahasiswa.prodi',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
            'suratPengantar',
            'suratBalasan',
        ])
            ->where('pkl.status', 'selesai')
            ->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($tahun) {
                    $q->where(
                        'mahasiswa.angkatan',
                        $tahun
                    );
                }
            );

        $this->applyScope($query, $user);

        if ($search !== '') {
            $query->whereHas(
                'pengajuanPkl.mahasiswa',
                function ($q) use ($search) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where(
                            'mahasiswa.nim',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'mahasiswa.nama',
                            'like',
                            '%' . $search . '%'
                        );
                    });
                }
            );
        }

        $pkls = $query
            ->orderByDesc('pkl.tgl_selesai')
            ->paginate(10)
            ->withQueryString();

        return view(
            'dosen.resume.mahasiswa',
            compact(
                'pkls',
                'tahun',
                'isKaprodi',
                'isPimpinanFakultas',
                'jabatan',
                'search'
            )
        );
    }

    public function show(Pkl $pkl)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        $this->authorizeResumeAccess($pkl, $user);

        $pkl->load([
            'dosen',
            'pengajuanPkl.mahasiswa.prodi.fakultas',
            'pengajuanPkl.tempatPkl',
            'nilaiPkl',
            'laporanAkhir',
            'logbooks',
            'penilaianMitra',
            'suratPengantar',
            'suratBalasan',
        ]);

        return view(
            'dosen.resume.show',
            compact(
                'pkl',
                'isKaprodi',
                'isPimpinanFakultas',
                'jabatan'
            )
        );
    }

    public function logbook(Pkl $pkl)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->dosen->jabatan ?? '');
        $isPimpinanFakultas = in_array($jabatan, ['dekan', 'wadek']);
        $isKaprodi = $jabatan === 'kaprodi';

        $this->authorizeResumeAccess($pkl, $user);

        $pkl->load([
            'pengajuanPkl.mahasiswa.prodi',
            'pengajuanPkl.tempatPkl',
        ]);

        $logbooksQuery = $pkl->logbooks()
            ->where('status_approve', 'approved')
            ->orderByDesc('tgl');

        $totalApproved = (clone $logbooksQuery)->count();

        $logbooks = $logbooksQuery
            ->paginate(20)
            ->withQueryString();

        $logbookDrive = $pkl->logbooks()
            ->whereNotNull('link_dokumentasi')
            ->where('link_dokumentasi', '!=', '')
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return view(
            'dosen.resume.logbook',
            compact(
                'pkl',
                'isKaprodi',
                'isPimpinanFakultas',
                'jabatan',
                'logbooks',
                'totalApproved',
                'logbookDrive'
            )
        );
    }
}
