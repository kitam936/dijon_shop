<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
            'id' =>1104,
            'company_id' => 1100,
            'shop_name' => '西友荻窪店',
            'shop_info' => '',
            'area_id' => 4,
            'is_selling' => 1,
            ],
            [
            'id' => 3201,
            'company_id' => 3200,
            'shop_name' => 'IY船橋店',
            'shop_info' => '',
            'area_id' => 4,
            'is_selling' => 1,
            ],
            [
            'id' => 5301,
            'company_id' => 5300,
            'shop_name' => 'アピタ高崎店',
            'shop_info' => '',
            'area_id' => 4,
            'is_selling' => 1,
            ],
            [
            'id' => 3216,
            'company_id' => 3200,
            'shop_name' => 'IY小田原店',
            'shop_info' => '',
            'area_id' => 4,
            'is_selling' => 1,
            ],




        ]);
    }
}
