@extends('layouts.admin')

@section('page-title', 'Laporan')

@section('content')
<style>
    .report-header {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .report-header h2 {
        color: #FFD700;
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    .report-header p {
        color: #999;
        font-size: 0.875rem;
    }
    
    .report-filters {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .report-filters input,
    .report-filters button {
        background: #1a1a1a;
        border: 1px solid #FFD700;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .report-filters button {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
    }
    
    .report-filters button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .chart-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .chart-card h3 {
        color: #FFD700;
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .chart-container {
        height: 300px;
        background: #1a1a1a;
        border-radius: 8px;
        padding: 1rem;
    }
</style>

<div class="report-header">
    <div>
        <h2>📊 Laporan Penjualan</h2>
        <p>Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>
    <div class="report-filters">
        <form action="{{ route('admin.reports') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
            <span style="color: #999;">-</span>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
            <button type="submit">Filter</button>
        </form>
        <button onclick="window.print()" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #1a1a1a;">🖨️ Print</button>
        <button onclick="exportTableToExcel('reportTable', 'Laporan_{{ date('Y-m-d') }}')" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #1a1a1a;">📥 Excel</button>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="chart-card">
        <h3>Product Revenue</h3>
        <div class="chart-container">
            <canvas id="productRevenueChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3>Revenue Breakdown</h3>
        <div class="chart-container">
            <canvas id="revenueBreakdownChart"></canvas>
        </div>
    </div>
</div>

<div class="chart-card">
    <h3>Total Income Trend (Daily)</h3>
    <div class="chart-container" style="height: 300px;">
        <canvas id="totalIncomeChart"></canvas>
    </div>
</div>

<!-- Hidden table for Excel export -->
<div style="display: none;">
    <table id="reportTable">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportTable as $row)
            <tr>
                <td>{{ $row->date }}</td>
                <td>{{ $row->product_name }}</td>
                <td>{{ $row->sold }}</td>
                <td>{{ $row->revenue }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = ['#FFD700', '#FFA500', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DFE6E9'];
    
    // Product Revenue Chart
    const ctxBar = document.getElementById('productRevenueChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($productRevenue->pluck('product_name')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($productRevenue->pluck('revenue')) !!},
                backgroundColor: colors,
                borderColor: '#FFD700',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { color: '#ccc' }, grid: { color: '#333' } },
                x: { ticks: { color: '#ccc' }, grid: { color: '#333' } }
            },
            plugins: { legend: { labels: { color: '#ccc' } } }
        }
    });
    
    // Revenue Breakdown Chart
    const ctxPie = document.getElementById('revenueBreakdownChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($revenueBreakdown->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($revenueBreakdown->pluck('revenue')) !!},
                backgroundColor: colors,
                borderColor: '#1a1a1a',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#ccc' } } }
        }
    });
    
    // Total Income Chart
    const ctxLine = document.getElementById('totalIncomeChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
            datasets: [{
                label: 'Total Pendapatan Harian',
                data: {!! json_encode($dailyRevenue->pluck('total')) !!},
                borderColor: '#FFD700',
                backgroundColor: 'rgba(255, 215, 0, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#FFD700',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { color: '#ccc' }, grid: { color: '#333' } },
                x: { ticks: { color: '#ccc' }, grid: { color: '#333' } }
            },
            plugins: { legend: { labels: { color: '#ccc' } } }
        }
    });
    
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>

@endsection
