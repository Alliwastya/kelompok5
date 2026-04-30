<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RotiController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\ReportController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA (WEB ROTI - BLADE)
|--------------------------------------------------------------------------
*/
Route::get('/', [RotiController::class, 'index']);
Route::post('/checkout', [RotiController::class, 'checkout'])->name('checkout');

// Pre-Order Routes
Route::get('/preorder', [RotiController::class, 'showPreorderPage'])->name('preorder.show');
Route::post('/preorder/submit', [RotiController::class, 'submitPreorder'])->name('preorder.submit');

Route::get('/payment/{orderId}', [RotiController::class, 'showPaymentPage'])->name('payment.show');
Route::post('/upload-payment-proof', [RotiController::class, 'uploadPaymentProof'])->name('payment.upload');
Route::get('/captcha/generate', [RotiController::class, 'generateCaptcha'])->name('captcha.generate');
Route::post('/send-otp', [RotiController::class, 'sendOtp'])->name('send-otp');
Route::post('/verify-otp', [RotiController::class, 'verifyOtp'])->name('verify-otp');
Route::get('/order-status/{phone}', [RotiController::class, 'getOrderStatus'])->name('order.status');
Route::get('/messages/thread', [MessageController::class, 'getThread'])->name('messages.getThread');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/unread/{phone}', [MessageController::class, 'getUnreadNotifications'])->name('messages.unread');
Route::post('/messages/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');

// Temporary Test Route
Route::get('/test/force-complete', function() {
    $order = \App\Models\Order::latest()->first();
    if($order) {
        $order->update(['status' => 'delivered']);
        return response()->json(['success' => true, 'message' => "Order {$order->order_number} updated to delivered"]);
    }
    return response()->json(['success' => false, 'message' => "No orders found"]);
});

// Test QRIS Image
Route::get('/test/qris', function() {
    $qrisImagePath = \App\Models\PaymentSetting::getQrisImage();
    $qrisImage = asset($qrisImagePath);
    
    return response()->json([
        'raw_path' => $qrisImagePath,
        'asset_url' => $qrisImage,
        'file_exists' => \Storage::disk('public')->exists(str_replace('/storage/', '', $qrisImagePath)),
        'setting' => \App\Models\PaymentSetting::where('key', 'qris_image')->first()
    ]);
});

// Test Notification API
Route::get('/test/notifications', function() {
    $notifications = \App\Models\AdminNotification::where('is_read', false)->get();
    return response()->json([
        'count' => $notifications->count(),
        'notifications' => $notifications
    ]);
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (INERTIA - LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE USER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Orders Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/respond', [AdminController::class, 'respondToOrder'])->name('orders.respond');
    Route::post('/orders/{order}/complete', [AdminController::class, 'completeOrder'])->name('orders.complete');
    Route::patch('/orders/{order}/shipping', [AdminController::class, 'updateOrderShipping'])->name('orders.update-shipping');
    Route::patch('/orders/{order}/confirm-payment', [AdminController::class, 'confirmPayment'])->name('orders.confirm-payment');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])->name('reports.monthly');

    // Revenue Charts
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue');
    Route::get('/revenue/daily', [RevenueController::class, 'getDailyRevenue'])->name('revenue.daily');
    Route::get('/revenue/monthly', [RevenueController::class, 'getMonthlyRevenue'])->name('revenue.monthly');
    Route::get('/revenue/summary', [RevenueController::class, 'getSummary'])->name('revenue.summary');

    // Promos Management (Removed as it's now integrated in Products)
    // Route::resource('promos', \App\Http\Controllers\AdminPromoController::class);
    // Route::patch('/promos/{promo}/toggle', [\App\Http\Controllers\AdminPromoController::class, 'toggleStatus'])->name('promos.toggle');

    // Shipping Rates Management
    Route::resource('shipping-rates', \App\Http\Controllers\AdminShippingController::class);
    Route::patch('/shipping-rates/{shippingRate}/toggle', [\App\Http\Controllers\AdminShippingController::class, 'toggleStatus'])->name('shipping-rates.toggle');

    // Reviews Management
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
    Route::patch('/reviews/{review}/toggle', [AdminController::class, 'toggleReviewVisibility'])->name('reviews.toggle');

    // Messages Management
    Route::get('/messages', [\App\Http\Controllers\AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [\App\Http\Controllers\AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{id}/reply', [\App\Http\Controllers\AdminMessageController::class, 'reply'])->name('messages.reply');
    
    // Payment Settings
    Route::get('/payment-settings', [\App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('payment-settings.index');
    Route::post('/payment-settings/qris', [\App\Http\Controllers\Admin\PaymentSettingController::class, 'updateQrisImage'])->name('payment-settings.update-qris');
    Route::delete('/payment-settings/qris', [\App\Http\Controllers\Admin\PaymentSettingController::class, 'deleteQrisImage'])->name('payment-settings.delete-qris');
    
    // Notifications Management
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    
    // Contact Messages Management
    Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact.index');
    Route::get('/contact-messages/unread', [\App\Http\Controllers\Admin\ContactMessageController::class, 'getUnread'])->name('contact.unread');
    Route::get('/contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact.show');
    Route::post('/contact-messages/{id}/reply', [\App\Http\Controllers\Admin\ContactMessageController::class, 'reply'])->name('contact.reply');
    Route::delete('/contact-messages/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'delete'])->name('contact.delete');
    
    // Security Management
    Route::get('/security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('security.index');
    Route::post('/security/block/{ip}', [\App\Http\Controllers\Admin\SecurityController::class, 'blockIp'])->name('security.block');
    Route::post('/security/unblock/{ip}', [\App\Http\Controllers\Admin\SecurityController::class, 'unblockIp'])->name('security.unblock');
    Route::post('/security/clear-logs', [\App\Http\Controllers\Admin\SecurityController::class, 'clearLogs'])->name('security.clear-logs');
    
    // Fraud Detection
    Route::get('/fraud', [\App\Http\Controllers\Admin\FraudController::class, 'index'])->name('fraud.index');
    Route::get('/fraud/{id}', [\App\Http\Controllers\Admin\FraudController::class, 'show'])->name('fraud.show');
    Route::post('/fraud/{id}/approve', [\App\Http\Controllers\Admin\FraudController::class, 'approve'])->name('fraud.approve');
    Route::post('/fraud/{id}/reject', [\App\Http\Controllers\Admin\FraudController::class, 'reject'])->name('fraud.reject');
});

// API Routes for Checkout
// Route::post('/api/validate-promo', [RotiController::class, 'validatePromo']); (Removed)
Route::get('/api/shipping-rates', [RotiController::class, 'getShippingRates']);
Route::post('/api/upload-payment-proof', [RotiController::class, 'uploadPaymentProof'])->name('payment.upload');

// Public Contact Form
Route::post('/contact', [\App\Http\Controllers\Admin\ContactMessageController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';