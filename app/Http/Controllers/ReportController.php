<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display daily revenue report
     */
    public function dailyReport(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $singleDate = $request->input('date');

        // Build query for daily report
        $query = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhere(function ($subQ) {
                  $subQ->where('payment_method', 'COD')
                       ->where('status', 'completed');
              });
        });

        // Apply filters
        if ($singleDate) {
            $query->whereDate('created_at', $singleDate);
        } elseif ($startDate && $endDate) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Group by date and order (descending for table)
        $dailyReports = $query->groupBy('date')
                              ->orderBy('date', 'desc')
                              ->get();

        // Prepare data for chart (ascending order for chronological display)
        $chartLabels = $dailyReports->sortBy('date')->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d M Y');
        })->values()->toArray();
        
        $chartValues = $dailyReports->sortBy('date')->pluck('total_revenue')->values()->toArray();
        
        $chartData = [
            'labels' => $chartLabels,
            'data' => $chartValues
        ];

        return view('admin.reports.daily', compact('dailyReports', 'chartData', 'startDate', 'endDate', 'singleDate'));
    }

    /**
     * Display monthly revenue report
     */
    public function monthlyReport(Request $request)
    {
        // Get filter parameter
        $year = $request->input('year', date('Y'));

        // Build query for monthly report
        $query = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhere(function ($subQ) {
                  $subQ->where('payment_method', 'COD')
                       ->where('status', 'completed');
              });
        });

        // Apply year filter
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Group by year and month
        $monthlyReports = $query->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();

        // Array of month names in Indonesian
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Add month name to each report
        $monthlyReports->transform(function ($item) use ($monthNames) {
            $item->month_name = $monthNames[$item->month];
            return $item;
        });

        // Prepare data for chart (ascending order for chronological display)
        $sortedReports = $monthlyReports->sortBy('month');
        $chartLabels = $sortedReports->pluck('month_name')->values()->toArray();
        $chartValues = $sortedReports->pluck('total_revenue')->values()->toArray();
        
        $chartData = [
            'labels' => $chartLabels,
            'data' => $chartValues
        ];

        // Get available years for filter dropdown
        $availableYears = Order::select(DB::raw('YEAR(created_at) as year'))
                               ->distinct()
                               ->orderBy('year', 'desc')
                               ->pluck('year');

        return view('admin.reports.monthly', compact('monthlyReports', 'chartData', 'year', 'availableYears'));
    }
}
