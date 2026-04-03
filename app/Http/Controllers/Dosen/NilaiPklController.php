<?php
namespace App\Http\Controllers\Dosen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\NilaiPkl;
class NilaiPklController extends Controller
{
    private function getDosenId()
    {
        return auth()->user()->dosen->id ?? null;
    }
    /**
     * List mahasiswa siap dinilai
     */
    public function index()
    {
        $dosenId = $this->getDosenId();

        $pkls = Pkl::where('id_dosen', $dosenId)
            ->where('status', 'aktif')
            ->whereHas('laporanAkhir', function ($q) {
                $q->where('status_approve', 'approved');
            })
            ->with(['pengajuanPkl.mahasiswa', 'laporanAkhir', 'nilaiPkl'])
            ->paginate(15); // 🔥 pagination

        return view('dosen.nilai.index', compact('pkls'));
    }
    /**
     * Form input nilai
     */
    public function create(Pkl $pkl)
    {
        if ($pkl->id_dosen !== $this->getDosenId()) {
            abort(403);
        }
        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
        if (!$pkl->laporanAkhir || $pkl->laporanAkhir->status_approve !== 'approved') {
            abort(403, 'Laporan belum disetujui.');
        }
        if ($pkl->nilaiPkl) {
            return redirect()->route('dosen.nilai.index')
                ->with('warning', 'Nilai sudah diinput.');
        }
        return view('dosen.nilai.create', compact('pkl'));
    }
    /**
     * Simpan nilai
     */
    public function store(Request $request, Pkl $pkl)
    {
        if ($pkl->id_dosen !== $this->getDosenId()) {
            abort(403);
        }
        if ($pkl->nilaiPkl) {
            abort(403, 'Nilai sudah ada.');
        }
        $request->validate([
            'nilai'      => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:2000',
        ]);
        NilaiPkl::create([
            'id_pkl'    => $pkl->id,
            'nilai'     => $request->nilai,
            'keterangan'=> $request->keterangan,
            'tgl_input' => now(),
        ]);

        // 🔥 Set PKL selesai
        $pkl->update([
            'status' => 'selesai',
            'tgl_selesai' => now(),
        ]);
        return redirect()->route('dosen.nilai.index')
            ->with('success', 'Nilai berhasil disimpan dan PKL selesai.');
    }
    public function daftar()
    {
        $dosenId = $this->getDosenId();

        $pkls = Pkl::where('id_dosen', $dosenId)
            ->where('status', 'selesai')
            ->whereHas('nilaiPkl')
            ->with(['pengajuanPkl.mahasiswa', 'nilaiPkl'])
            ->orderByDesc('tgl_selesai')
            ->paginate(10); // 🔥 pagination

        return view('dosen.nilai.daftar', compact('pkls'));
    }
}