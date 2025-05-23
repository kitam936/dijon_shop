<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            UserSeeder::class,
            IvStatusSeeder::class,
            // InventorySeeder::class,
            // CompanySeeder::class,
            // ShopSeeder::class,
            // BrandSeeder::class,
            // UnitSeeder::class,

            ]);
    }
}
