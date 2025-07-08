<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HistoryRawMaterialsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $historyData = [
            [
                'transact_id' => 'TRX-123',
                'supplier_id' => 1,
                'product_id' => 1,
                'quantity' => 100,
                'unit' => '80g',
                'price' => 59.99,
                'amount' => 5999.00,
                'process_by' => 'John Doe',
                'received_date' => $now->toDateString(),
                'is_archived' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transact_id' => 'TRX-123',
                'supplier_id' => 1,
                'product_id' => 2,
                'quantity' => 200,
                'unit' => '120g',
                'price' => 25.50,
                'amount' => 5100.00,
                'process_by' => 'John Doe',
                'received_date' => $now->toDateString(),
                'is_archived' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transact_id' => 'TRX-125',
                'supplier_id' => 2,
                'product_id' => 2,
                'quantity' => 200,
                'unit' => '120g',
                'price' => 25.50,
                'amount' => 5100.00,
                'process_by' => 'John Doe',
                'received_date' => $now->toDateString(),
                'is_archived' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('history_raw_materials')->insert($historyData);
    }
}
