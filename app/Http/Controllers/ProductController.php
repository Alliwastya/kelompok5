<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products with stats
    public function index(Request $request)
    {
        // 1. Calculate Stats
        $topProduct = \DB::table('order_items')
            ->select('product_name', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->first();

        $totalItemsSold = \DB::table('order_items')->sum('quantity');

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $itemsSoldThisWeek = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startOfWeek, $endOfWeek])
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.quantity');

        $weeklyRevenue = \App\Models\Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // 2. Query Products
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
             if ($request->sort === 'terlaris') {
                 // Sort by sold quantity using subquery
                 $subquery = Product::leftJoin('order_items', 'products.name', '=', 'order_items.product_name')
                     ->select('products.id', \DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
                     ->groupBy('products.id');
                 
                 $query = Product::joinSub($subquery, 'sales', function($join) {
                     $join->on('products.id', '=', 'sales.id');
                 })->orderByDesc('sales.total_sold');
             } else {
                 $query->latest();
             }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12); // Grid layout better with 12 items

        return view('admin.products.index', [
            'products' => $products,
            'topProduct' => $topProduct ? $topProduct->product_name : '-',
            'totalItemsSold' => $totalItemsSold,
            'itemsSoldThisWeek' => $itemsSoldThisWeek,
            'weeklyRevenue' => $weeklyRevenue,
        ]);
    }

    // Show create form
    public function create()
    {
        $categories = ['Roti Manis', 'Roti Tawar', 'Pastry', 'Donut', 'Croissant', 'Lainnya'];
        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    // Store product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:none,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'is_discount_active' => 'boolean',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'manual_status' => 'nullable|in:ready,habis,pre-order',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Show product detail
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ['Roti Manis', 'Roti Tawar', 'Pastry', 'Donut', 'Croissant', 'Lainnya'];
        
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:none,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'is_discount_active' => 'boolean',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'manual_status' => 'nullable|in:ready,habis,pre-order',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image file
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
