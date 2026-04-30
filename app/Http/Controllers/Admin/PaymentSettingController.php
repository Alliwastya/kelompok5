<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    /**
     * Show payment settings page
     */
    public function index()
    {
        $qrisImage = PaymentSetting::where('key', 'qris_image')->first();
        
        return view('admin.payment-settings', [
            'qrisImage' => $qrisImage
        ]);
    }

    /**
     * Update QRIS image
     */
    public function updateQrisImage(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Delete old image if exists
            $oldSetting = PaymentSetting::where('key', 'qris_image')->first();
            if ($oldSetting && $oldSetting->value && !filter_var($oldSetting->value, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($oldSetting->value);
            }

            // Upload new image
            $path = $request->file('qris_image')->store('payment', 'public');

            // Save to database
            PaymentSetting::setValue(
                'qris_image',
                $path,
                'image',
                'QR Code untuk pembayaran QRIS'
            );

            return redirect()->back()->with('success', 'Gambar QRIS berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload gambar: ' . $e->getMessage());
        }
    }

    /**
     * Delete QRIS image (reset to default)
     */
    public function deleteQrisImage()
    {
        try {
            $setting = PaymentSetting::where('key', 'qris_image')->first();
            
            if ($setting && $setting->value && !filter_var($setting->value, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($setting->value);
            }

            // Reset to default URL
            PaymentSetting::setValue(
                'qris_image',
                'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=DapoerBudessQRISPayment_Mockup',
                'image',
                'QR Code untuk pembayaran QRIS'
            );

            return redirect()->back()->with('success', 'Gambar QRIS berhasil direset ke default!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus gambar: ' . $e->getMessage());
        }
    }
}
