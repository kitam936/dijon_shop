<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->insert([
        [
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'shop_id' => 101,
            'mailService' => 1
        ],
        [
            'id' => 2,
            'name' => 'manager',
            'email' => 'manager@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 5,
            'shop_id' => 101,
            'mailService' => 1
        ],
        [
            'id' => 9,
            'name' => 'test1',
            'email' => 'test1@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 9,
            'shop_id' => 1104,
            'mailService' => 1
        ],
        [
            'id' => 10,
            'name' => 'test2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 9,
            'shop_id' => 5201,
            'mailService' => 0
        ],

    ]);
    }
}
