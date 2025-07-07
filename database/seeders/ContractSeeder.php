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
            ['contract' => '1 year', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => '2 years', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => '3 years', 'created_at' => $now, 'updated_at' => $now],
            ['contract' => '4 years', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('contracts')->insert($contracts);
    }
}
