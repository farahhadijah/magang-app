<?php

namespace App\Http\Controllers;

use App\Services\SiakadService;
use Illuminate\Http\Request;

class DebugSiakadController extends Controller
{
    public function nilai(Request $request, SiakadService $siakad)
    {
        $nim = $request->query('nim', '112310070');
        $tahunsms = $request->query('tahunsms', '20261');

        $response = $siakad->debugNilaiMahasiswa(
            $nim,
            $tahunsms
        );

        return response()->json([
            'debug' => [
                'nim' => $nim,
                'tahunsms' => $tahunsms,
            ],

            'http_status' => $response['status'],
            'successful' => $response['successful'],

            'siakad_response' => $response['json'],

            'raw_response' => $response['body'],
        ]);
    }
}