<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $products = [
            ['product_name' => 'Product A', 'stock_unit_id' => '80g', 'product_price' => 10.50, 'created_at' => $now, 'updated_at' => $now],
            ['product_name' => 'Product B', 'stock_unit_id' => '120g', 'product_price' => 20.00, 'created_at' => $now, 'updated_at' => $now],
            ['product_name' => 'Product C', 'stock_unit_id' => '260g', 'product_price' => 15.75, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('products')->insert($products);
    }
}
