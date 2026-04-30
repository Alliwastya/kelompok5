<?php

namespace App\Http\Controllers;

use App\Models\PromoSetting;
use App\Models\PromoModalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PromoSettingController extends Controller
{
    public function index()
    {
        $promo = PromoSetting::first();
        if (!$promo) {
            $promo = PromoSetting::create([
                'title' => 'Roti Sobek Premium, Lembut & Fresh!',
                'subtitle' => 'Roti sobek premium dengan tekstur lembut dan rasa yang menggugah selera. Sempurna untuk sarapan atau camilan Anda!',
                'price_original' => 35000,
                'price_promo' => 28000,
                'badge_text' => '🔥 SPESIAL HARI INI!',
                'discount_badge_text' => '💎 HEMAT 20%',
                'is_active' => true,
                'end_time' => now()->addDays(1),
            ]);
        }

        $modalProducts = PromoModalProduct::orderBy('order')->get();
        if ($modalProducts->isEmpty()) {
            // Seed default products
            $defaults = [
                [
                    'name' => 'Roti Sobek Coklat Keju',
                    'subtitle' => 'ROTI ENAKS',
                    'badge' => null,
                    'price_original' => 30000,
                    'price_promo' => 30000,
                    'stock_label' => 'Ready Hari Ini 📦',
                    'bottom_label' => '⚡ Promo terbatas!',
                    'order' => 0
                ],
                [
                    'name' => 'ROTI GULA MANIS',
                    'subtitle' => 'ENAK',
                    'badge' => 'PROMO',
                    'price_original' => 30000,
                    'price_promo' => 27000,
                    'stock_label' => 'Ready Hari Ini 📦',
                    'bottom_label' => '🔥 Stok menipis!',
                    'order' => 1
                ],
                [
                    'name' => 'Roti Sobek Pisang Coklat',
                    'subtitle' => 'Produk berkualitas dari Dapoer Budess',
                    'badge' => 'PROMO',
                    'price_original' => 30000,
                    'price_promo' => 27000,
                    'stock_label' => 'Ready Hari Ini 📦',
                    'bottom_label' => '✨ Fresh setiap hari!',
                    'order' => 2
                ]
            ];
            foreach ($defaults as $def) {
                PromoModalProduct::create($def);
            }
            $modalProducts = PromoModalProduct::orderBy('order')->get();
        }

        return view('admin.promo.edit', compact('promo', 'modalProducts'));
    }

    public function update(Request $request)
    {
        $promo = PromoSetting::first();

        $request->validate([
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'price_original' => 'required|numeric',
            'price_promo' => 'required|numeric',
            'badge_text' => 'nullable|string',
            'discount_badge_text' => 'nullable|string',
            'end_time' => 'required|date',
            'image_main' => 'nullable|image|max:2048',
            'image_second' => 'nullable|image|max:2048',
            'image_third' => 'nullable|image|max:2048',
            // Modal products validation
            'modal_products.*.name' => 'required|string',
            'modal_products.*.price_promo' => 'required|numeric',
            'modal_products.*.image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'subtitle', 'price_original', 'price_promo', 'badge_text', 'discount_badge_text', 'end_time']);
        $data['is_active'] = $request->has('is_active');

        // Handle Banner Image Uploads
        $imageFields = ['image_main', 'image_second', 'image_third'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if ($promo->$field && File::exists(public_path($promo->$field))) {
                    File::delete(public_path($promo->$field));
                }
                $image = $request->file($field);
                $name = time() . '_' . $field . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/promo'), $name);
                $data[$field] = 'images/promo/' . $name;
            }
        }
        $promo->update($data);

        // Handle Modal Products Update
        if ($request->has('modal_products')) {
            foreach ($request->modal_products as $id => $pData) {
                $product = PromoModalProduct::findOrFail($id);
                
                $updateData = [
                    'name' => $pData['name'],
                    'subtitle' => $pData['subtitle'],
                    'badge' => $pData['badge'],
                    'price_original' => $pData['price_original'],
                    'price_promo' => $pData['price_promo'],
                    'stock_label' => $pData['stock_label'],
                    'bottom_label' => $pData['bottom_label'],
                ];

                if (isset($pData['image']) && $request->hasFile("modal_products.$id.image")) {
                    if ($product->image && File::exists(public_path($product->image))) {
                        File::delete(public_path($product->image));
                    }
                    $image = $request->file("modal_products.$id.image");
                    $name = time() . '_modal_' . $id . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/promo'), $name);
                    $updateData['image'] = 'images/promo/' . $name;
                }

                $product->update($updateData);
            }
        }

        return back()->with('success', 'Pengaturan promo berhasil diperbarui');
    }
}
