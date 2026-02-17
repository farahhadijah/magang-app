<?php
namespace App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;
use Carbon\Carbon;
class LogbookController extends Controller
{
    /**
     * Ambil PKL aktif mahasiswa login
     */
    private function getActivePkl()
    {
        $user = auth()->user();
        if (!$user || !$user->mahasiswa) {
            return null;
        }
        return Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
                $q->where('id_mhs', $user->mahasiswa->id);
            })
            ->where('status', 'aktif')
            ->latest()
            ->first();
    }
    /**
     * Index: tampilkan semua logbook mahasiswa
     */
    public function index()
    {
        $pkl = $this->getActivePkl();
        $logbooks = $pkl
            ? $pkl->logbooks()->orderBy('tgl', 'desc')->get()
            : collect();
        $hasToday = false;
        if ($pkl) {
            $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();
            $hasToday = $pkl->logbooks()
                ->whereDate('tgl', $todayJakarta)
                ->exists();
        }
        return view('mahasiswa.logbook.index', compact('logbooks', 'hasToday', 'pkl'));
    }
    /**
     * Form tambah logbook
     */
    public function create()
    {
        $pkl = $this->getActivePkl();
        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }
        // 🔒 Pastikan masih dalam 30 hari pertama
        if (!$pkl->isFaseLogbook()) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Masa pengisian logbook (30 hari pertama) telah berakhir.');
        }
        // Cegah lebih dari satu logbook per hari (gunakan timezone lokal)
        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();
        $hasToday = $pkl->logbooks()
            ->whereDate('tgl', $todayJakarta)
            ->exists();
        if ($hasToday) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Anda sudah membuat logbook untuk hari ini.');
        }
        return view('mahasiswa.logbook.create', compact('pkl'));
    }
    /**
     * Simpan logbook baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl'      => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ]);
        // Ensure 'tgl' is not after today in Asia/Jakarta timezone
        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();
        try {
            $tglInput = Carbon::createFromFormat('Y-m-d', $request->tgl, 'Asia/Jakarta')->toDateString();
        } catch (\Exception $e) {
            return back()->withErrors(['tgl' => 'Format tanggal tidak valid.'])->withInput();
        }
        if ($tglInput > $todayJakarta) {
            return back()->withErrors(['tgl' => 'Tanggal tidak boleh setelah hari ini.'])->withInput();
        }
        $pkl = $this->getActivePkl();
        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }
        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
        // 🔒 Pastikan masih dalam 30 hari pertama
        if (!$pkl->isFaseLogbook()) {
            return back()->with('error', 'Masa pengisian logbook telah berakhir.');
        }
        $tgl = Carbon::parse($request->tgl);
        $mulai = Carbon::parse($pkl->tgl_mulai);
        $akhirMagang = $pkl->batasMagang();
        // Validasi hanya 30 hari pertama
        if ($tgl->lt($mulai) || $tgl->gt($akhirMagang)) {
            return back()->with('error', 'Logbook hanya dapat diisi dalam 30 hari pertama masa magang.');
        }
        // Cek duplikat tanggal
        $exists = Logbook::where('id_pkl', $pkl->id)
            ->whereDate('tgl', $request->tgl)
            ->exists();
        if ($exists) {
            return back()->with('error', 'Logbook untuk tanggal tersebut sudah ada.');
        }
        Logbook::create([
            'id_pkl'         => $pkl->id,
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan_dosen'  => null,
        ]);
        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }
    /**
     * Edit form
     */
    public function edit(Logbook $logbook)
    {
        $user = auth()->user();
        if (!$user || !$user->mahasiswa) {
            abort(403);
        }
        if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($logbook->pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
        if (!$logbook->pkl->isFaseLogbook()) {
            abort(403, 'Masa pengisian logbook telah berakhir.');
        }
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
        }
        return view('mahasiswa.logbook.edit', compact('logbook'));
    }
    /**
     * Update logbook
     */
    public function update(Request $request, Logbook $logbook)
    {
        $user = auth()->user();
        if (!$user || !$user->mahasiswa) {
            abort(403);
        }
        if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($logbook->pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
        if (!$logbook->pkl->isFaseLogbook()) {
            abort(403, 'Masa pengisian logbook telah berakhir.');
        }
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
        }
        $request->validate([
            'tgl'      => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ]);
        // Ensure 'tgl' is not after today in Asia/Jakarta timezone
        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();
        try {
            $tglInput = Carbon::createFromFormat('Y-m-d', $request->tgl, 'Asia/Jakarta')->toDateString();
        } catch (\Exception $e) {
            return back()->withErrors(['tgl' => 'Format tanggal tidak valid.'])->withInput();
        }
        if ($tglInput > $todayJakarta) {
            return back()->withErrors(['tgl' => 'Tanggal tidak boleh setelah hari ini.'])->withInput();
        }
        $pkl = $logbook->pkl;
        $tgl = Carbon::parse($request->tgl);
        $mulai = Carbon::parse($pkl->tgl_mulai);
        $akhirMagang = $pkl->batasMagang();
        if ($tgl->lt($mulai) || $tgl->gt($akhirMagang)) {
            return back()->with('error', 'Logbook hanya dapat diisi dalam 30 hari pertama masa magang.');
        }
        $exists = Logbook::where('id_pkl', $pkl->id)
            ->whereDate('tgl', $request->tgl)
            ->where('id', '!=', $logbook->id)
            ->exists();
        if ($exists) {
            return back()->with('error', 'Logbook untuk tanggal tersebut sudah ada.');
        }
        $logbook->update([
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan_dosen'  => null,
        ]);
        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }
    /**
     * Hapus logbook
     */
    public function destroy(Logbook $logbook)
    {
        $user = auth()->user();
        if (!$user || !$user->mahasiswa) {
            abort(403);
        }
        if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook yang sudah disetujui tidak dapat dihapus.');
        }
        if (!$logbook->pkl->isFaseLogbook()) {
            abort(403, 'Masa pengisian logbook telah berakhir.');
        }
        $logbook->delete();
        return back()->with('success', 'Logbook berhasil dihapus.');
    }
}