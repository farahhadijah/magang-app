<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;

        if (! $dosen || ! $dosen->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }

        return $dosen->prodi_id;
    }

    // Halaman daftar mahasiswa yang sudah memiliki PKL aktif
    public function index()
    {
        $prodiId = $this->getProdiId();

        $mahasiswas = Mahasiswa::where('prodi_id', $prodiId)
            ->whereHas('pengajuanPkl.pkl', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with([
                'prodi',
                'pengajuanPkl.pkl' => function ($q) {
                    $q->where('status', 'aktif')
                        ->with(['dosen', 'suratBalasan']);
                },
            ])
            ->orderBy('nama')
            ->paginate(9);

        return view(
            'kaprodi.mahasiswa.index',
            compact('mahasiswas')
        );
    }

    // Halaman detail mahasiswa
    public function detail(Mahasiswa $mahasiswa)
    {
        $prodiId = $this->getProdiId();

        abort_unless(
            $mahasiswa->prodi_id === $prodiId,
            403
        );

        $mahasiswa->load([
            'prodi',
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
        $prodiId = $this->getProdiId();

        $angkatan = Mahasiswa::where('prodi_id', $prodiId)
            ->whereDoesntHave('pengajuanPkl', function ($q) {
                $q->whereNotIn('status', [
                    'ditolak_tu',
                    'ditolak_kaprodi',
                ]);
            })
            ->select('angkatan')
            ->selectRaw('COUNT(*) as total_mahasiswa')
            ->groupBy('angkatan')
            ->orderByDesc('angkatan')
            ->paginate(10);

        return view(
            'kaprodi.mahasiswa.angkatan',
            compact('angkatan')
        );
    }

    // Halaman kedua: mahasiswa berdasarkan angkatan
    public function belumMengajukan($angkatan)
    {
        $prodiId = $this->getProdiId();

        $mahasiswas = Mahasiswa::where('prodi_id', $prodiId)
            ->where('angkatan', $angkatan)
            ->whereDoesntHave('pengajuanPkl', function ($q) {
                $q->whereNotIn('status', [
                    'ditolak_tu',
                    'ditolak_kaprodi',
                ]);
            })
            ->with([
                'prodi',
                'pengajuanPkl',
            ])
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view(
            'kaprodi.mahasiswa.belum',
            compact('mahasiswas', 'angkatan')
        );
    }
}
