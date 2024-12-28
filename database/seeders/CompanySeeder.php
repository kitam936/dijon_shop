<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{

    public function run()
    {
        DB::table('companies')->insert([
            [
            'id' => 1100,
            'co_name' => 'Seiyu',
            'co_info' => '西友',
            ],
            [
            'id' => 3200,
            'co_name' => 'IY',
            'co_info' => 'イトーヨーカドー',
            ],
            [
            'id' => 5300,
            'co_name' => 'UNY',
            'co_info' => 'ライフ',
            ],



        ]);
    }
}
