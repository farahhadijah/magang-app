<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nip']) ||
            empty($row['nama']) ||
            empty($row['jabatan']) ||
            empty($row['kode_prodi'])
        ) {
            return null;
        }

        // Cari prodi berdasarkan kode
        $prodi = Prodi::where('kode', strtoupper(trim($row['kode_prodi'])))->first();

        if (!$prodi) {
            return null; // skip jika prodi tidak ditemukan
        }

        $staff = Staff::updateOrCreate(
            ['nip' => trim($row['nip'])],
            [
                'nama'      => trim($row['nama']),
                'jabatan'   => trim($row['jabatan']),
                'no_hp'     => trim($row['no_hp'] ?? null),
                'prodi_id'  => $prodi->id,
                'is_active' => 1,
            ]
        );

        if (!$staff->user) {
            User::create([
                'username'    => $staff->nip,
                'password'    => Hash::make($staff->nip),
                'role'        => 'staf',
                'staff_id'    => $staff->id,
                'is_active'   => 1,
                'first_login' => 1,
            ]);
        }

        return $staff;
    }
}