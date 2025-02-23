<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([[
            'id' => 1,
            'status' => '送信済',

        ],
        [
            'id' => 3,
            'status' => '本部受信済',

        ],
        [
            'id' => 7,
            'status' => '対応済',

        ],
        [
            'id' => 9,
            'status' => '対応不可',

        ],

    ]);
    }
}
