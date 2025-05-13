<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IvStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('iv_statuses')->insert([[
            'id' => 1,
            'status_name' => 'New',

        ],
        [
            'id' => 5,
            'status_name' => '本部DL済',

        ],
    ]);
    }
}
