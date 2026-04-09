<?php

namespace Database\Seeders;

use App\Models\BestsellerHighlight;
use Illuminate\Database\Seeder;

class BestsellerHighlightSeeder extends Seeder
{
    public function run(): void
    {
        BestsellerHighlight::create([
            'name' => 'Roti Tawar Gandum Premium',
            'description' => 'Terbuat dari gandum murni pilihan, tekstur lembut dan kaya serat. Cocok untuk sarapan sehat keluarga Anda.',
            'label' => 'Best Seller',
            'is_active' => true
        ]);

        BestsellerHighlight::create([
            'name' => 'Pastry Coklat Lumer',
            'description' => 'Pastry renyah dengan isian coklat Belgia premium yang melumer di mulut dalam setiap gigitan.',
            'label' => 'Paling Dicari',
            'is_active' => true
        ]);
        
        BestsellerHighlight::create([
            'name' => 'Roti Manis Keju Susu',
            'description' => 'Kombinasi sempurna keju cheddar melimpah dan susu segar yang memberikan rasa gurih dan manis yang seimbang.',
            'label' => 'Favorit',
            'is_active' => true
        ]);
    }
}
