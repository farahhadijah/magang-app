<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Imports\MahasiswaImport;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Services\SiakadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class MahasiswaController extends Controller
{
    public function indexProdi()
    {
        // 1. Ambil SEMUA prodi aktif untuk dropdown sinkronisasi
        $prodiForSync = Prodi::query()
            ->where('is_active', 1)
            ->orderBy('nama')
            ->get();

        // 2. Filter prodi yang HANYA punya mahasiswa untuk ditampilkan di tabel
        $prodi = $prodiForSync->map(function ($p) {
            $p->jumlah_mahasiswa = Mahasiswa::where('prodi_id', $p->id)
                ->where('is_active', 1)
                ->count();

            $p->jumlah_angkatan = Mahasiswa::where('prodi_id', $p->id)
                ->where('is_active', 1)
                ->distinct()
                ->count('angkatan');

            return $p;
        })->filter(function ($p) {
            return $p->jumlah_mahasiswa > 0;
        })->values();

        // 3. Kirim KEDUA variabel ke view
        return view('admin.mahasiswa.index', compact('prodi', 'prodiForSync'));
    }

    public function angkatanByProdi($prodiId)
    {
        $prodi = Prodi::findOrFail($prodiId);

        $angkatans = Mahasiswa::query()
            ->where('prodi_id', $prodiId)
            ->where('is_active', 1)
            ->select('angkatan', DB::raw('COUNT(*) as jumlah_mahasiswa'))
            ->groupBy('angkatan')
            ->orderByDesc('angkatan')
            ->get();

        return view('admin.mahasiswa.angkatan', compact('prodi', 'angkatans'));
    }

    public function byAngkatan(Request $request, $prodiId, $angkatan)
    {
        $prodi = Prodi::findOrFail($prodiId);

        $query = Mahasiswa::with('prodi')
            ->where('prodi_id', $prodiId)
            ->where('angkatan', $angkatan);

        // Filter search: nama atau nim
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $mahasiswa = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.mahasiswa.by-angkatan', compact('prodi', 'mahasiswa', 'angkatan'));
    }

    public function create()
    {
        $prodi = Prodi::where('is_active', 1)->get();

        return view('admin.mahasiswa.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:255|unique:mahasiswa,email',
            'angkatan' => 'required|digits:4',
            'prodi_id' => 'nullable|exists:prodi,id',
        ]);
        DB::beginTransaction();
        try {
            $mahasiswa = Mahasiswa::create([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'email' => $request->email,
                'angkatan' => $request->angkatan,
                'no_hp' => $request->no_hp,
                'prodi_id' => $request->prodi_id,
                'is_active' => 1,
            ]);
            DB::commit();

            // ✅ Redirect ke Level 3 (daftar mahasiswa prodi + angkatan)
            if ($mahasiswa->prodi_id) {
                return redirect()
                    ->route('admin.mahasiswa.by-angkatan', [
                        'prodiId' => $mahasiswa->prodi_id,
                        'angkatan' => $mahasiswa->angkatan,
                    ])
                    ->with('success', 'Mahasiswa berhasil ditambahkan.');
            }

            // Fallback: kalau prodi_id kosong, kembali ke Level 1
            return redirect()
                ->route('admin.mahasiswa.index')
                ->with('success', 'Mahasiswa berhasil ditambahkan. Silakan pilih prodi untuk melihat data.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodi = Prodi::where('is_active', 1)->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodi'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // Pastikan ID integer
        $mahasiswaId = (int) $mahasiswa->id;

        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswaId,
            'nama' => 'required|string|max:100',
            'email' => [
                'nullable',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('mahasiswa', 'email')->ignore($mahasiswaId)
            ],
            'angkatan' => 'required|digits:4',
            'prodi_id' => 'nullable|exists:prodi,id',
        ]);

        DB::beginTransaction();
        try {
            $mahasiswa->update([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'email' => $request->email,
                'angkatan' => $request->angkatan,
                'no_hp' => $request->no_hp,
                'prodi_id' => $request->prodi_id,
            ]);
            if ($mahasiswa->user) {
                $mahasiswa->user->update([
                    'username' => $request->nim,
                ]);
            }
            DB::commit();

            // ✅ Redirect ke Level 3 (prodi + angkatan terbaru dari mahasiswa)
            if ($mahasiswa->prodi_id) {
                return redirect()
                    ->route('admin.mahasiswa.by-angkatan', [
                        'prodiId' => $mahasiswa->prodi_id,
                        'angkatan' => $mahasiswa->angkatan,
                    ])
                    ->with('success', 'Mahasiswa berhasil diperbarui.');
            }

            // Fallback
            return redirect()
                ->route('admin.mahasiswa.index')
                ->with('success', 'Mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->update(['is_active' => 0]);
            if ($mahasiswa->user) {
                $mahasiswa->user->update(['is_active' => 0]);
            }
        });

        return back()->with('success', 'Mahasiswa dinonaktifkan.');
    }

    public function resetPassword(Mahasiswa $mahasiswa)
    {
        if (! $mahasiswa->user) {
            return back()->with('error', 'User tidak ditemukan.');
        }
        $mahasiswa->user->update([
            'password' => Hash::make($mahasiswa->nim),
            'first_login' => 1,
        ]);

        return back()->with('success', 'Password berhasil direset ke NIM.');
    }

    public function activate($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update(['is_active' => 1]);
        if ($mahasiswa->user) {
            $mahasiswa->user->update(['is_active' => 1]);
        }

        return back()->with('success', 'Mahasiswa berhasil diaktifkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        $headings = (new HeadingRowImport)->toArray($request->file('file'));
        $header = $headings[0][0] ?? [];
        $required = ['nim', 'nama', 'angkatan', 'no_hp', 'kode_prodi'];
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);
        foreach ($required as $col) {
            if (! in_array($col, $header)) {
                return back()->with(
                    'error',
                    'Format file salah. Kolom wajib: nim, nama, angkatan, no_hp, kode_prodi'
                );
            }
        }
        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return back()->with('success', 'Import mahasiswa berhasil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: '.$e->getMessage());
        }
    }

    public function syncSiakad(Request $request, SiakadService $siakad)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodi,id',
            'angkatan' => 'required|digits:4',
        ]);

        try {
            /*
            * Ambil ID Prodi dan angkatan yang dipilih admin.
            */
            $prodiId = (int) $request->prodi_id;
            $angkatan = (int) $request->angkatan;

            /*
            * Pastikan data Fakultas dan Prodi
            * sudah tersedia/terbarui dari SIAKAD.
            */
            $siakad->syncFakultas();
            $siakad->syncProdi();

            /*
            * Sinkronisasi mahasiswa berdasarkan
            * Prodi + Angkatan yang dipilih.
            */
            $totalMahasiswa = $siakad->syncMahasiswa(
                $prodiId,
                $angkatan
            );

            if ($totalMahasiswa === 0) {
                return back()->with(
                    'error',
                    'Tidak ada mahasiswa yang berhasil disinkronkan dari SIAKAD.'
                );
            }

            return back()->with(
                'success',
                "{$totalMahasiswa} mahasiswa berhasil disinkronkan dari SIAKAD."
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Sinkronisasi gagal: '.$e->getMessage()
            );
        }
    }
}
