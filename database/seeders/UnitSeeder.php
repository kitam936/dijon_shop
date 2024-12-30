<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            [
            'id' => 1,
            'season_id' => 0,
            'season_name' => '春',
            ],
            [
            'id' => 2,
            'season_id' => 0,
            'season_name' => '春',
            ],
            [
            'id' => 3,
            'season_id' => 2,
            'season_name' => '夏',
            ],
            [
            'id' => 4,
            'season_id' => 2,
            'season_name' => '夏',
            ],
            [
            'id' => 5,
            'season_id' => 2,
            'season_name' => '夏',
            ],
            [
            'id' => 6,
            'season_id' => 2,
            'season_name' => '夏',
            ],
            [
            'id' => 7,
            'season_id' => 6,
            'season_name' => '秋',
            ],
            [
            'id' => 8,
            'season_id' => 6,
            'season_name' => '秋',
            ],
            [
            'id' => 9,
            'season_id' => 8,
            'season_name' => '冬',
            ],
            [
            'id' => 10,
            'season_id' => 8,
            'season_name' => '冬',
            ],
            [
            'id' => 11,
            'season_id' => 8,
            'season_name' => '冬',
            ],
            [
            'id' => 12,
            'season_id' => 8,
            'season_name' => '冬',
            ],

        ]);
    }
}
