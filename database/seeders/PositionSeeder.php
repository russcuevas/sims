<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $positions = [
            ['position_name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['position_name' => 'Manager', 'created_at' => $now, 'updated_at' => $now],
            ['position_name' => 'Delivery', 'created_at' => $now, 'updated_at' => $now],
            ['position_name' => 'Supervisor', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('positions')->insert($positions);
    }
}
