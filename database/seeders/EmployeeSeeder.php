<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $lastName = 'Doe';
        $birthday = '1990-01-01';
        $formattedBirthday = str_replace('-', '', $birthday);
        $passwordRaw = $lastName . $formattedBirthday;

        DB::table('employees')->insert([
            'employee_firstname' => 'John',
            'employee_lastname' => $lastName,
            'birthday' => $birthday,
            'position_id' => 1,
            'contract_id' => 1,
            'username' => 'johndoe',
            'email' => 'russelcuevas0@gmail.com',
            'password' => bcrypt($passwordRaw),
            'pin' => 1234,
            'status' => 'Unlocked',
            'login_attempts' => 0,
            'is_archived' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
