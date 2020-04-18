<?php

use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('access')->insert(['name' => 'manage_patients']);
        DB::table('access')->insert(['name' => 'patients']);
        DB::table('access')->insert(['name' => 'in_patients']);
        DB::table('access')->insert(['name' => 'generate_report']);
        DB::table('access')->insert(['name' => 'library']);
        DB::table('access')->insert(['name' => 'lib']);
        DB::table('access')->insert(['name' => 'brgy']);
        DB::table('access')->insert(['name' => 'comorbid']);
        DB::table('access')->insert(['name' => 'charges']);
        DB::table('access')->insert(['name' => 'services']);
        DB::table('access')->insert(['name' => 'users']);
        DB::table('access')->insert(['name' => 'access']);
        DB::table('access')->insert(['name' => 'patient_save']);
        DB::table('access')->insert(['name' => 'patient_list']);
        DB::table('access')->insert(['name' => 'patient_update']);
        DB::table('access')->insert(['name' => 'patient_delete']);
        DB::table('access')->insert(['name' => 'patient_admit']);
        DB::table('access')->insert(['name' => 'patient_history']);
        DB::table('access')->insert(['name' => 'soa_update']);
        DB::table('access')->insert(['name' => 'soa_print']);
        DB::table('access')->insert(['name' => 'patient_services']);
        DB::table('access')->insert(['name' => 'patient_discharge']);
        DB::table('access')->insert(['name' => 'patient_services_remove']);
        DB::table('access')->insert(['name' => 'patient_services_add']);
    }
}
