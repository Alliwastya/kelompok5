<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User (hanya 1 admin)
        User::updateOrCreate(
            ['email' => 'admin@budess.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('admin123'),
                'is_admin' => 1,
            ]
        );

        // Create Test User (regular user)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
                'is_admin' => 0,
            ]
        );

        // Seed products, orders and messages for admin dashboard demo
        $this->call([
            \Database\Seeders\ProductSeeder::class,
            \Database\Seeders\ShippingRateSeeder::class,
            \Database\Seeders\OrderSeeder::class,
            \Database\Seeders\MessageSeeder::class,
        ]);
    }
}
