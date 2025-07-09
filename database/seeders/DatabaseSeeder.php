<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PositionSeeder::class);
        $this->call(ContractSeeder::class);
        $this->call(EmployeeSeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(SupplierSeeder::class);
        // $this->call(ProductDetailSeeder::class);
        // $this->call(BatchProductDetailSeeder::class);
        // $this->call(HistoryRawMaterialsSeeder::class);
    }
}
