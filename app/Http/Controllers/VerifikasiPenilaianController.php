<?php

namespace App\Http\Controllers;


class VerifikasiPenilaianController extends Controller
{
    public function show($token)
    {
        // Verification via QR/barcode has been disabled. Return 404 for safety.
        abort(404);
    }
}