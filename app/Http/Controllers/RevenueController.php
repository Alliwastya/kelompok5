<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueController extends Controller
{
    /**
     * Display revenue dashboard
     */
    public function index()
    {
        return view('admin.revenue');
    }

    /**
     * Get daily revenue data
     */
    public function getDailyRevenue(Request $request)
    {
        try {
            $filter = $request->get('filter', 'month'); // today, month, year
            
            $query = Order::where(function($q) {
                $q->where('payment_status', 'paid')
                  ->orWhere(function($subQuery) {
                      $subQuery->where('payment_method', 'COD')
                               ->where('status', 'completed');
                  });
            });

            // Apply date filters
            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }

            $data = $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                         ->groupBy('date')
                         ->orderBy('date', 'asc')
                         ->get();

            // Format response
            $chartData = [
                'labels' => [],
                'data' => []
            ];

            foreach ($data as $item) {
                $chartData['labels'][] = Carbon::parse($item->date)->format('d M');
                $chartData['data'][] = (float) $item->total;
            }

            return response()->json([
                'success' => true,
                'chartData' => $chartData,
                'total' => array_sum($chartData['data'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Daily Revenue Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading daily revenue data'
            ], 500);
        }
    }

    /**
     * Get monthly revenue data
     */
    public function getMonthlyRevenue(Request $request)
    {
        try {
            $year = $request->get('year', Carbon::now()->year);
            
            $data = Order::where(function($q) {
                $q->where('payment_status', 'paid')
                  ->orWhere(function($subQuery) {
                      $subQuery->where('payment_method', 'COD')
                               ->where('status', 'completed');
                  });
            })
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month');

            // Prepare 12 months data (fill empty months with 0)
            $chartData = [
                'labels' => [],
                'data' => []
            ];

            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            for ($i = 1; $i <= 12; $i++) {
                $chartData['labels'][] = $monthNames[$i - 1];
                $chartData['data'][] = isset($data[$i]) ? (float) $data[$i]->total : 0;
            }

            return response()->json([
                'success' => true,
                'chartData' => $chartData,
                'total' => array_sum($chartData['data'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Monthly Revenue Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading monthly revenue data'
            ], 500);
        }
    }

    /**
     * Get revenue summary
     */
    public function getSummary()
    {
        try {
            $baseQuery = function() {
                return Order::where(function($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere(function($subQuery) {
                          $subQuery->where('payment_method', 'COD')
                                   ->where('status', 'completed');
                      });
                });
            };

            $today = $baseQuery()->whereDate('created_at', Carbon::today())
                                ->sum('total_amount');

            $thisMonth = $baseQuery()->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->sum('total_amount');

            $thisYear = $baseQuery()->whereYear('created_at', Carbon::now()->year)
                                   ->sum('total_amount');

            return response()->json([
                'success' => true,
                'summary' => [
                    'today' => (float) $today,
                    'month' => (float) $thisMonth,
                    'year' => (float) $thisYear
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Revenue Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading revenue summary'
            ], 500);
        }
    }
}
