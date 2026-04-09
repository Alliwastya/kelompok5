<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'media.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
            'phone' => 'required|string', // Validation security measure
            'display_name' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $order = Order::find($request->order_id);

        // Security check: Verify phone matches order
        if ($order->customer_phone !== $request->phone) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        // Check if already reviewed
        if ($order->review) {
            return response()->json(['success' => false, 'message' => 'Anda sudah memberikan ulasan untuk pesanan ini'], 400);
        }

        // Handle Media Upload
        $mediaUrls = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('reviews', 'public');
                $mediaUrls[] = 'storage/' . $path;
            }
        }

        // Create Review
        Review::create([
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'media_urls' => count($mediaUrls) > 0 ? $mediaUrls : null,
            'is_visible' => true, // Auto-approve per user request
            'display_name' => $request->display_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Ulasan Anda telah berhasil dikirim.',
        ]);
    }

    /**
     * Get recent reviews for display
     */
    public function index()
    {
        $reviews = Review::with(['order:id,customer_name', 'order.items:order_id,product_name'])
            ->where('is_visible', true)
            // ->where('rating', '>=', 4) // Removed to show all reviews
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'customer_name' => $this->maskName($review->order->customer_name),
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'media_urls' => $review->media_urls,
                    'items' => $review->order->items->pluck('product_name'),
                    'created_at' => $review->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }

    private function maskName($name)
    {
        $parts = explode(' ', $name);
        if (count($parts) > 1) {
            return $parts[0] . ' ' . substr($parts[1], 0, 1) . '.';
        }
        return $name;
    }
}
