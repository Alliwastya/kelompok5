@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .chart-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: bold;
        transition: all 0.3s;
    }
    
    .chart-btn.active {
        color: #FFD700;
        background: rgba(255, 215, 0, 0.1);
    }
    
    .chart-btn:not(.active) {
        color: #888;
        background: transparent;
    }
    
    .chart-btn:hover {
        color: #FFD700;
        background: rgba(255, 215, 0, 0.1);
    }
</style>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Revenue Card -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #FFD700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Pendapatan Minggu Ini</div>
                <div style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 0.5rem;">Rp{{ number_format($weeklyRevenue ?? 21000, 0, ',', '.') }}</div>
                <div style="font-size: 0.875rem; color: #999; font-weight: 600;">Meningkat 09%</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: #FFD700;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
    </div>
    
    <!-- Orders Card -->
    <div class="card">
        <div style="font-size: 0.75rem; font-weight: 700; color: #FFD700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Pesanan Terjual</div>
        <div style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 0.5rem;">{{ $weeklyOrdersCount ?? 65 }} <span style="font-size: 1rem; color: #999;">pcs</span></div>
        <div style="font-size: 0.875rem; color: #999; font-weight: 600;">+ 150</div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Sales Chart -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div class="card-title" style="margin-bottom: 0;">Statistik Penjualan</div>
            <div style="display: flex; gap: 0.5rem; background: #1a1a1a; padding: 0.25rem; border-radius: 8px;">
                <button class="chart-btn active" onclick="switchChart('harian')">Harian</button>
                <button class="chart-btn" onclick="switchChart('mingguan')">Mingguan</button>
                <button class="chart-btn" onclick="switchChart('bulanan')">Bulanan</button>
                <button class="chart-btn" onclick="switchChart('tahunan')">Tahunan</button>
            </div>
        </div>
        
        <div style="height: 300px; position: relative;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="card">
        <div class="card-title">Top Selling Produk</div>
        @php
            $topProducts = [
                ['name' => 'Roti Enaks', 'sales' => 22],
                ['name' => 'Choco Lava Bun', 'sales' => 10],
                ['name' => 'Cheese Chocolate Roll', 'sales' => 8],
                ['name' => 'Roti Mayones', 'sales' => 4],
                ['name' => 'Roti Coklat Keju', 'sales' => 3],
            ];
        @endphp
        @foreach($topProducts as $product)
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #ccc; font-size: 0.875rem; font-weight: 600;">{{ $product['name'] }}</span>
                    <span style="color: #FFD700; font-size: 0.875rem; font-weight: bold;">{{ $product['sales'] }} pcs</span>
                </div>
                <div style="background: #1a1a1a; height: 6px; border-radius: 3px; overflow: hidden;">
                    <div style="background: linear-gradient(90deg, #FFD700 0%, #FFA500 100%); height: 100%; width: {{ ($product['sales'] / 22) * 100 }}%; border-radius: 3px;"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Insights -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-title">Insights</div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        <div style="display: flex; gap: 1rem; padding: 1rem; background: #1a1a1a; border-radius: 8px;">
            <span style="font-size: 1.5rem;">🍞</span>
            <div>
                <p style="color: #ccc; font-size: 0.875rem;">Top Product terlaris itu</p>
                <p style="color: #FFD700; font-weight: bold;">Roti Enaks</p>
                <span style="color: #4CAF50; font-weight: bold; font-size: 0.875rem;">📈 NAIK</span>
            </div>
        </div>
        <div style="display: flex; gap: 1rem; padding: 1rem; background: #1a1a1a; border-radius: 8px;">
            <span style="font-size: 1.5rem;">🎯</span>
            <div>
                <p style="color: #ccc; font-size: 0.875rem;">Prioritas menutur</p>
                <p style="color: #FFD700; font-weight: bold;">Roti Keju</p>
                <span style="color: #f44336; font-weight: bold; font-size: 0.875rem;">📉 TURUN</span>
            </div>
        </div>
        <div style="display: flex; gap: 1rem; padding: 1rem; background: #1a1a1a; border-radius: 8px;">
            <span style="font-size: 1.5rem;">⏰</span>
            <div>
                <p style="color: #ccc; font-size: 0.875rem;">Peak jam</p>
                <p style="color: #FFD700; font-weight: bold;">17:00</p>
            </div>
        </div>
    </div>
</div>

<script>
let chart = null;

const chartData = {
    harian: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        data: [45, 52, 48, 61, 58, 75, 68]
    },
    mingguan: {
        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        data: [35, 50, 40, 65]
    },
    bulanan: {
        labels: ['01 MAR', '11 MAR', '21 MAR', '02 APR'],
        data: [35, 50, 40, 65]
    },
    tahunan: {
        labels: ['Januari', 'Februari', 'Maret', 'April'],
        data: [55, 72, 68, 85]
    }
};

function initChart(type = 'harian') {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const data = chartData[type];
    
    if (chart) {
        chart.destroy();
    }
    
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Penjualan',
                data: data.data,
                borderColor: '#FFD700',
                backgroundColor: 'rgba(255, 215, 0, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#FFD700',
                pointBorderColor: '#FFA500',
                pointBorderWidth: 2,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#FFA500'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: '#666',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: '#333',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#999',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
}

function switchChart(type) {
    // Remove active class from all buttons
    document.querySelectorAll('.chart-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Initialize new chart
    initChart(type);
}

// Initialize chart on page load
document.addEventListener('DOMContentLoaded', function() {
    initChart('harian');
});
</script>
@endsection
