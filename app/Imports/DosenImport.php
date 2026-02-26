<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DosenImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows->skip(1) as $row) {

                // Format:
                // 0 = nidn
                // 1 = nama
                // 2 = keahlian
                // 3 = no_hp
                // 4 = kode_prodi

                $prodi = Prodi::where('kode', $row[4])->first();

                if (!$prodi) {
                    continue; // skip kalau prodi tidak ditemukan
                }

                $dosen = Dosen::updateOrCreate(
                    ['nidn' => $row[0]],
                    [
                        'nama' => $row[1],
                        'keahlian' => $row[2],
                        'no_hp' => $row[3],
                        'prodi_id' => $prodi->id,
                        'is_active' => 1,
                    ]
                );

                if (!$dosen->user) {
                    User::create([
                        'username' => $dosen->nidn,
                        'password' => Hash::make($dosen->nidn),
                        'role' => 'dosen',
                        'dosen_id' => $dosen->id,
                        'is_active' => 1,
                        'first_login' => 1,
                    ]);
                }
            }
        });
    }
}