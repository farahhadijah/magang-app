<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StaffImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows->skip(1) as $row) {

                $staff = Staff::updateOrCreate(
                    ['nip' => $row[0]],
                    [
                        'nama' => $row[1],
                        'jabatan' => $row[2],
                        'no_hp' => $row[3],
                        'prodi_id' => $row[4],
                        'is_active' => 1,
                    ]
                );

                if (!$staff->user) {
                    User::create([
                        'username' => $staff->nip,
                        'password' => Hash::make($staff->nip),
                        'role' => 'staf',
                        'staff_id' => $staff->id,
                        'is_active' => 1,
                        'first_login' => 1,
                    ]);
                }
            }
        });
    }
}