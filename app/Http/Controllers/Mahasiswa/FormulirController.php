<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Formulir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormulirController extends Controller
{
    public function index()
    {
        // Fitur download formulir dinonaktifkan sepenuhnya.
        abort(404);
    }

    public function download($id)
    {
        // Fitur download formulir dinonaktifkan sepenuhnya.
        abort(404);
    }
}