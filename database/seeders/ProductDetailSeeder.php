<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $now = Carbon::now();

        // $productDetails = [
        //     [
        //         'product_id' => 1,
        //         'product_name' => 'Canned Tuna',
        //         'price' => 59.99,
        //         'quantity' => 100,
        //         'stock_unit_id' => '80g',
        //         'category' => 'Canned Goods',
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //     ],
        //     [
        //         'product_id' => 2,
        //         'product_name' => 'Sardines',
        //         'price' => 25.50,
        //         'quantity' => 200,
        //         'stock_unit_id' => '120g',
        //         'category' => 'Canned Goods',
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //     ],
        //     [
        //         'product_id' => 3,
        //         'product_name' => 'Corned Beef',
        //         'price' => 45.00,
        //         'quantity' => 150,
        //         'stock_unit_id' => '260g',
        //         'category' => 'Meat',
        //         'created_at' => $now,
        //         'updated_at' => $now,
        //     ],
        // ];

        // DB::table('product_details')->insert($productDetails);
    }
}
