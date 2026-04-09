<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingRate;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            [
                'region_name' => 'Jakarta',
                'cost' => 15000,
                'type' => 'regional',
                'is_active' => true,
            ],
            [
                'region_name' => 'Depok',
                'cost' => 10000,
                'type' => 'regional',
                'is_active' => true,
            ],
            [
                'region_name' => 'Bogor',
                'cost' => 20000,
                'type' => 'regional',
                'is_active' => true,
            ],
            [
                'region_name' => 'Luar Kota',
                'cost' => 25000,
                'type' => 'flat',
                'is_active' => true,
            ],
        ];

        foreach ($rates as $r) {
            ShippingRate::updateOrCreate(['region_name' => $r['region_name']], $r);
        }
    }
}
