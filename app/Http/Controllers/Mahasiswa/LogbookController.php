<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
     * Validasi bahwa logbook benar-benar milik mahasiswa login.
     */
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

    /**
     * Index: tampilkan semua logbook mahasiswa.
     */
    public function index()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            abort(403, 'PKL sudah selesai atau belum dimulai.');
        }

        // Tampilkan logbook terbaru terlebih dahulu
        $logbooks = $pkl->logbooks()
            ->orderBy('tgl', 'desc')
            ->paginate(10);

        // Cek apakah sudah ada logbook untuk hari ini
        $todayJakarta = Carbon::now('Asia/Jakarta')->toDateString();

        $hasToday = $pkl->logbooks()
            ->whereDate('tgl', $todayJakarta)
            ->exists();

        // Cari tanggal PKL yang belum memiliki logbook
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

        /*
         * Ambil link dokumentasi.
         *
         * Secara konsep hanya logbook pertama yang
         * memiliki link dokumentasi.
         */
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

    /**
     * Form tambah logbook.
     */
    public function create()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }

        /*
         * Logbook pertama wajib memiliki link dokumentasi.
         */
        $isFirstLogbook = !$pkl->logbooks()->exists();

        return view('mahasiswa.logbook.create', compact(
            'pkl',
            'isFirstLogbook'
        ));
    }

    /**
     * Simpan logbook baru.
     */
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

        /*
         * Pastikan ini memang logbook pertama.
         */
        $isFirstLogbook = !$pkl->logbooks()->exists();

        $rules = [
            'tgl' => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ];

        /*
         * Hanya logbook pertama yang wajib
         * mengisi link Google Drive.
         */
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

        // Pastikan tanggal tidak setelah hari ini
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

        // Tidak boleh sebelum tanggal mulai PKL
        $tgl = Carbon::parse($request->tgl);
        $mulai = Carbon::parse($pkl->tgl_mulai);

        if ($tgl->lt($mulai)) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh sebelum masa PKL dimulai.'
                ])
                ->withInput();
        }

        // Cek duplikat tanggal
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

        /*
         * Link dokumentasi hanya disimpan pada logbook pertama.
         */
        Logbook::create([
            'id_pkl' => $pkl->id,
            'tgl' => $request->tgl,
            'kegiatan' => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan' => null,
            'link_dokumentasi' => $isFirstLogbook
                ? $request->link_dokumentasi
                : null,
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * Form edit isi logbook.
     *
     * Hanya dapat digunakan untuk logbook pending/revisi.
     */
    public function edit(Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        /*
         * Logbook approved tidak boleh mengubah isi.
         */
        if ($logbook->status_approve === 'approved') {
            abort(
                403,
                'Logbook sudah disetujui dan isi logbook tidak dapat diubah.'
            );
        }

        return view('mahasiswa.logbook.edit', compact('logbook'));
    }

    /**
     * Update isi logbook.
     *
     * Tidak menangani link dokumentasi.
     */
    public function update(Request $request, Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        /*
         * Logbook approved tidak dapat diedit.
         */
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

        // Pastikan tanggal tidak setelah hari ini
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

        // Tidak boleh sebelum mulai PKL
        if ($tgl->lt($mulai)) {
            return back()
                ->withErrors([
                    'tgl' => 'Tanggal tidak boleh sebelum masa PKL dimulai.'
                ])
                ->withInput();
        }

        // Cek duplikat tanggal
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

        /*
         * Setelah isi logbook diperbaiki:
         * - status kembali pending
         * - catatan revisi dihapus
         *
         * Link dokumentasi tidak disentuh.
         */
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

    /**
     * ============================================================
     * DOKUMENTASI GOOGLE DRIVE
     * ============================================================
     */

    /**
     * Form untuk memperbaiki / mengubah link dokumentasi.
     *
     * Berbeda dengan edit logbook.
     */
    public function editDokumentasi()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            abort(403, 'Belum ada PKL aktif.');
        }

        /*
        * Dokumentasi terikat pada logbook pertama.
        * Tidak perlu mencari berdasarkan link_dokumentasi,
        * karena halaman ini memang digunakan untuk memperbaiki
        * atau mengisi link dokumentasi.
        */
        $logbookDokumentasi = $pkl->logbooks()
            ->orderBy('id')
            ->first();

        /*
        * Jika belum ada logbook sama sekali,
        * mahasiswa harus membuat logbook terlebih dahulu.
        */
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

    /**
     * Update link dokumentasi Google Drive.
     *
     * Fungsi ini sengaja dipisahkan dari update logbook.
     *
     * Walaupun logbook sudah approved,
     * link dokumentasi tetap dapat diperbaiki.
     */
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

        /*
        * Dokumentasi disimpan pada logbook pertama.
        */
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

        /*
        * Hanya link dokumentasi yang diubah.
        * Status approval dan kegiatan tidak berubah.
        */
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

    /**
     * Hapus logbook.
     */
    public function destroy(Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        if ($logbook->status_approve === 'approved') {
            abort(
                403,
                'Logbook yang sudah disetujui tidak dapat dihapus.'
            );
        }

        /*
         * Jangan izinkan logbook pertama yang memiliki
         * dokumentasi dihapus.
         *
         * Karena dokumentasi PKL terikat pada logbook pertama.
         */
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
