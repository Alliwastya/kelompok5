@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Penghasilan Harian</h1>
            <p class="text-gray-500 mt-1">Lihat detail penghasilan per hari dengan grafik dan filter.</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Laporan</h2>
            <form method="GET" action="{{ route('admin.reports.daily') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Single Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Spesifik</label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               value="{{ $singleDate }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ $startDate }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ $endDate }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">
                        🔍 Filter
                    </button>
                    <a href="{{ route('admin.reports.daily') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition">
                        🔄 Reset
                    </a>
                    <a href="{{ route('admin.reports.monthly') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition ml-auto">
                        📊 Laporan Bulanan
                    </a>
                </div>
            </form>
        </div>

        <!-- Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Grafik Penghasilan Harian</h2>
            <div class="h-80">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <!-- Table Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tabel Laporan Harian</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penghasilan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dailyReports as $report)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $report->total_orders }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    Rp {{ number_format($report->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data untuk ditampilkan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dailyReports->count() > 0)
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-900">Total</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $dailyReports->sum('total_orders') }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($dailyReports->sum('total_revenue'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('dailyChart').getContext('2d');
    const dailyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Penghasilan Harian (Rp)',
                data: @json($chartData['data']),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
