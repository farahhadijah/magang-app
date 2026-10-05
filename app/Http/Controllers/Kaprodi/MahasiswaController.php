<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen || !$dosen->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }

        return $dosen->prodi_id;
    }

    public function index()
    {
        $prodiId = $this->getProdiId();

        $mahasiswas = Mahasiswa::where('prodi_id', $prodiId)

            // Hanya mahasiswa yang memiliki PKL aktif
            ->whereHas('pengajuanPkl.pkl', function ($q) {
                $q->where('status', 'aktif');
            })

            ->with([
                'prodi',

                'pengajuanPkl.pkl' => function ($q) {
                    $q->where('status', 'aktif')
                        ->with([
                            'dosen',
                            'suratBalasan',
                        ]);
                },
            ])

            ->orderBy('nama')
            ->paginate(9);

        return view('kaprodi.mahasiswa.index', compact('mahasiswas'));
    }

    public function detail(Mahasiswa $mahasiswa)
    {
        $prodiId = $this->getProdiId();

        // Kaprodi hanya boleh melihat mahasiswa
        // yang berada pada prodinya sendiri.
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

        return view('kaprodi.mahasiswa.detail', compact('mahasiswa'));
    }

    public function belumMengajukan()
    {
        $prodiId = $this->getProdiId();

        $mahasiswas = Mahasiswa::where('prodi_id', $prodiId)

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
            ->paginate(15);

        return view('kaprodi.mahasiswa.belum', compact('mahasiswas'));
    }
}
