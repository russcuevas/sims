<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->insert([
            'employee_firstname' => 'Marie Teresita',
            'employee_lastname' => 'Lumanda',
            'birthday' => '1967-09-22',
            'position_id' => 1,
            'contract_id' => 1,
            'username' => 'marieteresital',
            'email' => 'juandelacruz.sample100@gmail.com',
            'password' => bcrypt('lumanda1967'),
            'pin' => 1234,
            'status' => 'Unlocked',
            'login_attempts' => 0,
            'is_archived' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
