<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudDetection;
use App\Models\Order;
use Illuminate\Http\Request;

class FraudController extends Controller
{
    public function index(Request $request)
    {
        $frauds = FraudDetection::query()
            ->when($request->input('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->input('risk_level'), function ($query) use ($request) {
                $level = $request->input('risk_level');
                if ($level === 'HIGH') {
                    $query->where('risk_score', '>=', 70);
                } elseif ($level === 'MEDIUM') {
                    $query->whereBetween('risk_score', [40, 69]);
                } else {
                    $query->where('risk_score', '<', 40);
                }
            })
            ->with('order')
            ->orderBy('risk_score', 'desc')
            ->paginate(50);

        $stats = [
            'high_risk' => FraudDetection::where('risk_score', '>=', 70)->count(),
            'medium_risk' => FraudDetection::whereBetween('risk_score', [40, 69])->count(),
            'pending_review' => FraudDetection::where('status', 'pending')->count(),
            'rejected' => FraudDetection::where('status', 'rejected')->count(),
        ];

        return view('admin.fraud.index', [
            'frauds' => $frauds,
            'stats' => $stats
        ]);
    }

    public function show($id)
    {
        $fraud = FraudDetection::with('order')->findOrFail($id);
        return view('admin.fraud.show', ['fraud' => $fraud]);
    }

    public function approve(Request $request, $id)
    {
        $fraud = FraudDetection::findOrFail($id);
        $fraud->update([
            'status' => 'approved',
            'notes' => $request->input('notes'),
            'reviewed_at' => now()
        ]);

        if ($fraud->order) {
            $fraud->order->update(['status' => 'pending_admin']);
        }

        return redirect()->back()->with('success', 'Order disetujui');
    }

    public function reject(Request $request, $id)
    {
        $fraud = FraudDetection::findOrFail($id);
        $fraud->update([
            'status' => 'rejected',
            'notes' => $request->input('notes'),
            'reviewed_at' => now()
        ]);

        if ($fraud->order) {
            $fraud->order->update(['status' => 'cancelled']);
        }

        return redirect()->back()->with('success', 'Order ditolak');
    }

    /**
     * Show fraud analytics dashboard
     */
    public function analytics()
    {
        $total = FraudDetection::count();
        $approved = FraudDetection::where('status', 'approved')->count();
        
        $stats = [
            'total_fraud_detected' => $total,
            'high_risk_pending' => FraudDetection::where('status', 'pending')->where('risk_score', '>=', 70)->count(),
            'avg_risk_score' => FraudDetection::avg('risk_score') ?? 0,
            'approval_rate' => $total > 0 ? round(($approved / $total) * 100) : 0,
            'risk_0_20' => FraudDetection::whereBetween('risk_score', [0, 20])->count(),
            'risk_21_40' => FraudDetection::whereBetween('risk_score', [21, 40])->count(),
            'risk_41_60' => FraudDetection::whereBetween('risk_score', [41, 60])->count(),
            'risk_61_80' => FraudDetection::whereBetween('risk_score', [61, 80])->count(),
            'risk_81_100' => FraudDetection::whereBetween('risk_score', [81, 100])->count(),
            'status_pending' => FraudDetection::where('status', 'pending')->count(),
            'status_approved' => FraudDetection::where('status', 'approved')->count(),
            'status_rejected' => FraudDetection::where('status', 'rejected')->count(),
        ];

        // Top fraud factors
        $fraud_factors = [
            ['name' => 'Phone Not Verified', 'count' => FraudDetection::where('factors', 'like', '%phone_not_verified%')->count()],
            ['name' => 'First Time Customer', 'count' => FraudDetection::where('factors', 'like', '%first_time_customer%')->count()],
            ['name' => 'High Order Amount', 'count' => FraudDetection::where('factors', 'like', '%high_order_amount%')->count()],
            ['name' => 'Multiple Orders Same IP', 'count' => FraudDetection::where('factors', 'like', '%multiple_orders_same_ip%')->count()],
            ['name' => 'Address Too Short', 'count' => FraudDetection::where('factors', 'like', '%address_too_short%')->count()],
        ];

        // Top suspicious customers
        $suspicious_customers = FraudDetection::selectRaw('customer_phone as phone, COUNT(*) as fraud_count, AVG(risk_score) as avg_risk_score')
            ->groupBy('customer_phone')
            ->orderBy('fraud_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $order = Order::where('customer_phone', $item->phone)->first();
                return [
                    'phone' => $item->phone,
                    'name' => $order?->customer_name ?? 'Unknown',
                    'fraud_count' => $item->fraud_count,
                    'avg_risk_score' => round($item->avg_risk_score)
                ];
            });

        return view('admin.fraud.analytics', compact('stats', 'fraud_factors', 'suspicious_customers'));
    }
}
