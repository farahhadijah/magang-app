<?php
namespace App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\LaporanAkhir;
use Illuminate\Support\Facades\Storage;
use App\Mail\NotifikasiLaporanAkhirDosen;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class LaporanAkhirController extends Controller
{
    private function getPkl()
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            return null;
        }

        return Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
                $q->where('id_mhs', $user->mahasiswa->id);
            })
            ->whereIn('status', ['aktif', 'selesai'])
            ->latest()
            ->first();
    }

    private function getActivePkl()
    {
        $pkl = $this->getPkl();
        return $pkl && $pkl->status === 'aktif' ? $pkl : null;
    }
    public function index()
    {
        $pkl = $this->getPkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('warning', 'Anda belum memiliki data PKL.');
        }

        $laporan = $pkl->laporanAkhir;

        return view('mahasiswa.laporan.index', compact('pkl', 'laporan'));
    }
    public function create()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            $anyPkl = $this->getPkl();
            $msg = $anyPkl
                ? 'PKL sudah selesai. Laporan tidak dapat diunggah.'
                : 'Belum ada PKL aktif.';

            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('warning', $msg);
        }

        if (!$pkl->isSiapUploadLaporan()) {
            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('warning', 'Belum memenuhi syarat upload laporan akhir.');
        }

        if ($pkl->laporanAkhir && $pkl->laporanAkhir->status_approve === 'approved') {
            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('warning', 'Laporan sudah disetujui dan tidak bisa diubah.');
        }

        return view('mahasiswa.laporan.create', compact('pkl'));
    }
    public function store(Request $request)
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            $anyPkl = $this->getPkl();
            $msg = $anyPkl
                ? 'PKL sudah selesai. Laporan tidak dapat diunggah.'
                : 'Belum ada PKL aktif.';

            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('warning', $msg);
        }

        if (!$pkl->isSiapUploadLaporan()) {
            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('warning', 'Belum memenuhi syarat upload laporan akhir.');
        }

        $request->validate([
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        $existing = $pkl->laporanAkhir;

        if ($existing && $existing->status_approve === 'approved') {
            abort(403, 'Laporan sudah disetujui.');
        }

        $path = $request->file('file')->store('laporan_akhir', 'public');

        if ($existing) {
            Storage::disk('public')->delete($existing->path_file);

            $existing->update([
                'path_file'      => $path,
                'status_approve' => 'pending',
                'catatan_dosen'  => null,
                'approved_by'    => null,
                'approved_at'    => null,
            ]);

            $laporan = $existing;
        } else {
            $laporan = LaporanAkhir::create([
                'id_pkl'         => $pkl->id,
                'path_file'      => $path,
                'status_approve' => 'pending',
            ]);
        }

        $dosen = $pkl->dosen;

        if ($dosen && $dosen->email) {
            try {
                Mail::to($dosen->email)->send(
                    new NotifikasiLaporanAkhirDosen($laporan)
                );
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi laporan akhir kepada dosen.', [
                    'laporan_id' => $laporan->id,
                    'dosen_id'   => $dosen->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('mahasiswa.laporan.index')
            ->with('success', 'Laporan akhir berhasil diupload.');
    }
}