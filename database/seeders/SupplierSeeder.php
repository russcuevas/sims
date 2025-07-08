<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $suppliers = [
            [
                'supplier_name' => 'ABC Supplies Co.',
                'supplier_contact_num' => '09171234567',
                'supplier_email_add' => 'contact@abcsupplies.com',
                'supplier_address' => '123 Main Street, Cityville',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_name' => 'Global Wholesale',
                'supplier_contact_num' => '09281234567',
                'supplier_email_add' => 'info@globalwholesale.com',
                'supplier_address' => '456 Market Road, Townsville',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_name' => 'Quality Products Inc.',
                'supplier_contact_num' => '09391234567',
                'supplier_email_add' => 'sales@qualityproducts.com',
                'supplier_address' => '789 Industrial Ave, Villagetown',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('suppliers')->insert($suppliers);
    }
}
