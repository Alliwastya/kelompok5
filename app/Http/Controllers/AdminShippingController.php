<?php

namespace App\Http\Controllers;

use App\Models\ShippingRate;
use Illuminate\Http\Request;

class AdminShippingController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::latest()->paginate(10);
        return view('admin.shipping.index', compact('rates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'type' => 'required|in:flat,regional',
        ]);

        $validated['is_active'] = true;

        ShippingRate::create($validated);

        return redirect()->back()->with('success', 'Tarif ongkir berhasil ditambahkan');
    }

    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'region_name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'type' => 'required|in:flat,regional',
        ]);

        $shippingRate->update($validated);

        return redirect()->back()->with('success', 'Tarif ongkir berhasil diperbarui');
    }

    public function destroy(ShippingRate $shippingRate)
    {
        $shippingRate->delete();
        return redirect()->back()->with('success', 'Tarif ongkir berhasil dihapus');
    }

    public function toggleStatus(ShippingRate $shippingRate)
    {
        $shippingRate->update(['is_active' => !$shippingRate->is_active]);
        return redirect()->back()->with('success', 'Status ongkir diperbarui');
    }
}
