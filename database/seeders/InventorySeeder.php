<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('inventory_headers')->insert([
            [
            'id' => 1,
            'user_id' => 1,
            'inventory_date' => '2025/2/28',
            'memo' => 'test1',
            ],
            [
            'id' =>2,
            'user_id' => 2,
            'inventory_date' => '2025/2/28',
            'memo' => 'test2',
            ],
            [
            'id' => 3,
            'user_id' => 9,
            'inventory_date' => '2025/2/28',
            'memo' => 'test3',
            ],
        ]);

        DB::table('inventory_details')->insert([
            [
            'inventory_header_id' => 1,
            'sku_id' => '153601999',
            'pcs' => '3',
            ],
            [
            'inventory_header_id' => 1,
            'sku_id' => '153602999',
            'pcs' => '3',
            ],
            [
            'inventory_header_id' => 1,
            'sku_id' => '153603999',
            'pcs' => '3',
            ],
        ]);
    }
}
