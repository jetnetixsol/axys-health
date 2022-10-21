<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $patient_id = DB::table('users')->insertGetId([
        //     'mrn' => '123233F',
        //     'full_name' => 'Shahab uddin Patient',
        //     'mobile_number' => '09001231231',
        //     'email' => 'shahab@patient.com',
        //     'dob' => '1998-07-24',
        //     'role' => 'patient',
        //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        // ]);
    }
}
