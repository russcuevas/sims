<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BatchProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $batch_product_details = [
            [
                'employee_id' => 1,
                'product_id' => 1,
                'product_name' => 'Canned Tuna',
                'price' => 59.99,
                'quantity' => 100,
                'stock_unit_id' => '80g',
                'category' => 'Canned Goods',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 1,
                'product_id' => 2,
                'product_name' => 'Sardines',
                'price' => 25.50,
                'quantity' => 200,
                'stock_unit_id' => '120g',
                'category' => 'Canned Goods',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 1,
                'product_id' => 3,
                'product_name' => 'Corned Beef',
                'price' => 45.00,
                'quantity' => 150,
                'stock_unit_id' => '260g',
                'category' => 'Meat',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('batch_product_details')->insert($batch_product_details);
    }
}
