<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([[
            'id' => 1,
            'role_name' => '管理者',
            'role_info' => '管理者権限',
        ],
        [
            'id' => 2,
            'role_name' => '管理者代行',
            'role_info' => '管理者権限',
        ],
        [
            'id' => 3,
            'role_name' => 'チーフマネージャー',
            'role_info' => 'チーフマネージャー権限',
        ],
        [
            'id' => 5,
            'role_name' => 'マネージャー',
            'role_info' => 'マネージャー権限',
        ],
        [
            'id' => 7,
            'role_name' => 'スタッフメンバー',
            'role_info' => 'スタッフメンバー',
        ],
        [
            'id' => 9,
            'role_name' => 'メンバー',
            'role_info' => 'メンバー',
        ]
    ]);
    }
}
