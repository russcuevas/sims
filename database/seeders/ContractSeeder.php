<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $contracts = [
            ['contract' => 'Regular', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => 'Probationary', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => 'Seasonal', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => 'Part-time', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => 'Terminated', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('contracts')->insert($contracts);
    }
}
