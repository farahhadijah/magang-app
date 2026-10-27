<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Mail\NotifikasiLogbookDosen;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LogbookController extends Controller
{
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

    private function authorizeLogbook(Logbook $logbook)
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            abort(403);
        }

        if (
            !$logbook->pkl ||
            !$logbook->pkl->pengajuanPkl ||
            $logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id
        ) {
            abort(403, 'Akses ditolak.');
        }

        if ($logbook->pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }
    }
    public function index()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            abort(403, 'PKL sudah selesai atau belum dimulai.');
        }

        $logbooks = $pkl->logbooks()
            ->orderBy('tgl', 'desc')
            ->paginate(10);

        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();

        $hasToday = $pkl->logbooks()
            ->whereDate('tgl', $todayJakarta)
            ->exists();

        $tanggalKosong = collect();

        $mulai = Carbon::parse($pkl->tgl_mulai)->startOfDay();
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $period = CarbonPeriod::create($mulai, $today);

        $tanggalTerisi = $pkl->logbooks()
            ->pluck('tgl')
            ->map(fn ($tgl) => Carbon::parse($tgl)->toDateString())
            ->toArray();

        foreach ($period as $date) {
            if (!in_array($date->toDateString(), $tanggalTerisi)) {
                $tanggalKosong->push($date->copy());
            }
        }

        $logbookDokumentasi = $pkl->logbooks()
            ->orderBy('id')
            ->first();

        return view('mahasiswa.logbook.index', compact(
            'logbooks',
            'hasToday',
            'pkl',
            'tanggalKosong',
            'logbookDokumentasi'
        ));
    }

    public function create()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }

        $isFirstLogbook = !$pkl->logbooks()->exists();

        return view('mahasiswa.logbook.create', compact(
            'pkl',
            'isFirstLogbook'
        ));
    }

    public function store(Request $request)
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }

        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }

        $isFirstLogbook = !$pkl->logbooks()->exists();

        $rules = [
            'tgl' => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ];

        if ($isFirstLogbook) {
            $rules['link_dokumentasi'] = [
                'required',
                'string',
                'max:2000',
                'url',
                'regex:/^https?:\/\/(drive\.google\.com|docs\.google\.com)\//i',
            ];
        }

        $messages = [
            'link_dokumentasi.required' =>
                'Link Google Drive dokumentasi wajib diisi pada logbook pertama.',

            'link_dokumentasi.url' =>
                'Link dokumentasi harus berupa URL yang valid.',

            'link_dokumentasi.regex' =>
                'Link dokumentasi harus berasal dari Google Drive.',

            'link_dokumentasi.max' =>
                'Link dokumentasi terlalu panjang.',
        ];

        $request->validate($rules, $messages);

        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();

        try {
            $tglInput = Carbon::createFromFormat(
                'Y-m-d',
                $request->tgl,
                'Asia/Jakarta'
            )->toDateString();
        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    'tgl' => 'Format tanggal tidak valid.'
                ])
                ->withInput();
        }

        if ($tglInput > $todayJakarta) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh setelah hari ini.'
                ])
                ->withInput();
        }

        $tgl = Carbon::parse($request->tgl);
        $mulai = Carbon::parse($pkl->tgl_mulai);

        if ($tgl->lt($mulai)) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh sebelum masa PKL dimulai.'
                ])
                ->withInput();
        }

        $exists = Logbook::where('id_pkl', $pkl->id)
            ->whereDate('tgl', $request->tgl)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'tgl' => 'Logbook untuk tanggal tersebut sudah ada.'
                ])
                ->withInput();
        }
        $logbook = Logbook::create([
            'id_pkl' => $pkl->id,
            'tgl' => $request->tgl,
            'kegiatan' => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan' => null,
            'link_dokumentasi' => $isFirstLogbook
                ? $request->link_dokumentasi
                : null,
        ]);

        // Hitung hanya logbook yang masih menunggu persetujuan.
    $totalPending = $pkl->logbooks()
        ->where('status_approve', 'pending')
        ->count();

    // Kirim notifikasi jika jumlah pending mencapai 7 atau kelipatannya.
    if ($totalPending > 0 && $totalPending % 7 === 0) {
        $dosen = $pkl->dosen;

        if ($dosen && $dosen->email) {
            try {
                Mail::to($dosen->email)->send(
                    new NotifikasiLogbookDosen($logbook)
                );
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi logbook kepada dosen.', [
                    'logbook_id' => $logbook->id,
                    'dosen_id' => $dosen->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }
    public function edit(Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);
        if ($logbook->status_approve === 'approved') {
            abort(
                403,
                'Logbook sudah disetujui dan isi logbook tidak dapat diubah.'
            );
        }

        return view('mahasiswa.logbook.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        if ($logbook->status_approve === 'approved') {
            abort(
                403,
                'Logbook sudah disetujui dan isi logbook tidak dapat diubah.'
            );
        }

        $request->validate([
            'tgl' => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ]);

        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();

        try {
            $tglInput = Carbon::createFromFormat(
                'Y-m-d',
                $request->tgl,
                'Asia/Jakarta'
            )->toDateString();
        } catch (\Exception $e) {
            return back()
                ->withErrors([
                    'tgl' => 'Format tanggal tidak valid.'
                ])
                ->withInput();
        }

        if ($tglInput > $todayJakarta) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh setelah hari ini.'
                ])
                ->withInput();
        }

        $pkl = $logbook->pkl;

        $tgl = Carbon::parse($request->tgl);
        $mulai = Carbon::parse($pkl->tgl_mulai);

        if ($tgl->lt($mulai)) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh sebelum masa PKL dimulai.'
                ])
                ->withInput();
        }

        $exists = Logbook::where('id_pkl', $pkl->id)
            ->whereDate('tgl', $request->tgl)
            ->where('id', '!=', $logbook->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'tgl' => 'Logbook untuk tanggal tersebut sudah ada.'
                ])
                ->withInput();
        }
        $logbook->update([
            'tgl' => $request->tgl,
            'kegiatan' => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan' => null,
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    public function editDokumentasi()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            abort(403, 'Belum ada PKL aktif.');
        }
        $logbookDokumentasi = $pkl->logbooks()
            ->orderBy('id')
            ->first();
        if (!$logbookDokumentasi) {
            return redirect()
                ->route('mahasiswa.logbook.create')
                ->with(
                    'warning',
                    'Silakan buat logbook pertama terlebih dahulu.'
                );
        }

        return view(
            'mahasiswa.logbook.edit-dokumentasi',
            compact('pkl', 'logbookDokumentasi')
        );
    }

    public function updateDokumentasi(Request $request)
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            abort(403, 'Belum ada PKL aktif.');
        }

        $request->validate([
            'link_dokumentasi' => [
                'required',
                'string',
                'max:2000',
                'url',
                'regex:/^https?:\/\/(drive\.google\.com|docs\.google\.com)\//i',
            ],
        ], [
            'link_dokumentasi.required' =>
                'Link Google Drive wajib diisi.',

            'link_dokumentasi.url' =>
                'Link dokumentasi harus berupa URL yang valid.',

            'link_dokumentasi.regex' =>
                'Link dokumentasi harus berasal dari Google Drive.',

            'link_dokumentasi.max' =>
                'Link dokumentasi terlalu panjang.',
        ]);

        $logbookDokumentasi = $pkl->logbooks()
            ->orderBy('id')
            ->first();

        if (!$logbookDokumentasi) {
            return redirect()
                ->route('mahasiswa.logbook.create')
                ->with(
                    'warning',
                    'Silakan buat logbook pertama terlebih dahulu.'
                );
        }

        $logbookDokumentasi->update([
            'link_dokumentasi' => $request->link_dokumentasi,
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with(
                'success',
                'Link dokumentasi Google Drive berhasil diperbarui.'
            );
    }

    public function destroy(Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        if ($logbook->status_approve === 'approved') {
            abort(
                403,
                'Logbook yang sudah disetujui tidak dapat dihapus.'
            );
        }

        if ($logbook->link_dokumentasi) {
            abort(
                403,
                'Logbook pertama yang memiliki dokumentasi tidak dapat dihapus.'
            );
        }

        $logbook->delete();

        return back()
            ->with('success', 'Logbook berhasil dihapus.');
    }
}
