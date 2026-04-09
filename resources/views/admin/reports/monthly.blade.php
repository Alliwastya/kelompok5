@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Penghasilan Bulanan</h1>
            <p class="text-gray-500 mt-1">Lihat detail penghasilan per bulan dengan grafik dan filter.</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Laporan</h2>
            <form method="GET" action="{{ route('admin.reports.monthly') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Year Filter -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun</label>
                        <select id="year" 
                                name="year" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">
                        🔍 Filter
                    </button>
                    <a href="{{ route('admin.reports.monthly') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition">
                        🔄 Reset
                    </a>
                    <a href="{{ route('admin.reports.daily') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition ml-auto">
                        📅 Laporan Harian
                    </a>
                </div>
            </form>
        </div>

        <!-- Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Grafik Penghasilan Bulanan</h2>
            <div class="h-80">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Table Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tabel Laporan Bulanan - Tahun {{ $year }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penghasilan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($monthlyReports as $report)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $report->month_name }} {{ $report->year }}
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
                                    Tidak ada data untuk tahun {{ $year }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($monthlyReports->count() > 0)
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-900">Total</td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $monthlyReports->sum('total_orders') }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($monthlyReports->sum('total_revenue'), 0, ',', '.') }}</td>
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
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Penghasilan Bulanan (Rp)',
                data: @json($chartData['data']),
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(34, 197, 94, 0.9)'
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
