<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            [
            'id' => 1,
            'brand_name' => 'TBIS',
            'brand_info' => 'TBIS',
            ],
            [
            'id' => 6,
            'brand_name' => 'Mam',
            'brand_info' => 'Mam',
            ],
            [
            'id' => 7,
            'brand_name' => 'notorico',
            'brand_info' => 'notorico',
            ],



        ]);
    }
}
