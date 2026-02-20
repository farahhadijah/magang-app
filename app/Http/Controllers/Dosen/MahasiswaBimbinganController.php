<?php
namespace App\Http\Controllers\Dosen;
use App\Http\Controllers\Controller;
class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        // nanti: ambil mahasiswa bimbingan berdasarkan dosen login
        return view('dosen.mahasiswa-bimbingan');
    }
}