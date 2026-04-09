<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Roti Tawar Premium',
                'description' => 'Roti tawar lembut dengan tekstur yang sempurna, cocok untuk sarapan.',
                'price' => 25000,
                'discount_price' => 22000,
                'stock' => 50,
                'category' => 'Roti Tawar',
                'is_available' => true,
            ],
            [
                'name' => 'Croissant Butter',
                'description' => 'Croissant klasik dengan lapisan butter yang kaya dan renyah di luar.',
                'price' => 15000,
                'discount_price' => 12500,
                'stock' => 30,
                'category' => 'Pastry',
                'is_available' => true,
            ],
            [
                'name' => 'Donat Cokelat Lumer',
                'description' => 'Donat lembut dengan isian cokelat yang melimpah.',
                'price' => 12000,
                'discount_price' => null,
                'stock' => 40,
                'category' => 'Donut',
                'is_available' => true,
            ],
            [
                'name' => 'Roti Sisir Manis',
                'description' => 'Roti sisir tradisional dengan olesan mentega dan gula.',
                'price' => 18000,
                'discount_price' => 15000,
                'stock' => 25,
                'category' => 'Roti Manis',
                'is_available' => true,
            ],
            [
                'name' => 'Pizza Mini Sosis',
                'description' => 'Pizza ukuran mini dengan topping sosis dan keju mozzarella.',
                'price' => 10000,
                'discount_price' => 8500,
                'stock' => 35,
                'category' => 'Lainnya',
                'is_available' => true,
            ],
            [
                'name' => 'Roti Sobek Cokelat Keju',
                'description' => 'Roti sobek lembut dengan perpaduan cokelat dan keju.',
                'price' => 35000,
                'discount_price' => 30000,
                'stock' => 20,
                'category' => 'Roti Manis',
                'is_available' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
