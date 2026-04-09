<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grafik Penghasilan - Dapoer Bubess Admin</title>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #8B4513;
            --primary-light: #A0522D;
            --secondary: #DEB887;
            --accent: #F5DEB3;
            --bg-light: #FFF8DC;
            --text-dark: #3E2723;
            --success: #4CAF50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, white 100%);
            min-height: 100vh;
            padding: 2rem;
            color: var(--text-dark);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(139, 69, 19, 0.1);
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #666;
            font-size: 0.95rem;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(139, 69, 19, 0.1);
            border-left: 4px solid var(--primary);
        }

        .summary-card .label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .summary-card .value {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: bold;
        }

        .summary-card .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(139, 69, 19, 0.1);
            margin-bottom: 2rem;
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--primary);
            background: white;
            color: var(--primary);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-btn:hover {
            background: var(--primary);
            color: white;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(139, 69, 19, 0.1);
        }

        .chart-container h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #999;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .summary-card .value {
                font-size: 1.5rem;
            }

            .chart-wrapper {
                height: 300px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .filter-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

        <div class="header">
            <h1>📊 Grafik Penghasilan Toko</h1>
            <p>Dapoer Bubess Bakery - Dashboard Pendapatan</p>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="icon">📅</div>
                <div class="label">Penghasilan Hari Ini</div>
                <div class="value" id="todayRevenue">Rp 0</div>
            </div>
            <div class="summary-card">
                <div class="icon">📆</div>
                <div class="label">Penghasilan Bulan Ini</div>
                <div class="value" id="monthRevenue">Rp 0</div>
            </div>
            <div class="summary-card">
                <div class="icon">🗓️</div>
                <div class="label">Penghasilan Tahun Ini</div>
                <div class="value" id="yearRevenue">Rp 0</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 style="margin-bottom: 1rem; color: var(--primary);">Filter Periode</h3>
            <div class="filter-buttons">
                <button class="filter-btn" onclick="setFilter('today')">Hari Ini</button>
                <button class="filter-btn active" onclick="setFilter('month')">Bulan Ini</button>
                <button class="filter-btn" onclick="setFilter('year')">Tahun Ini</button>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <!-- Daily Revenue Chart -->
            <div class="chart-container">
                <h2>📈 Penghasilan Harian</h2>
                <div class="chart-wrapper">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="chart-container">
                <h2>📊 Penghasilan Bulanan ({{ date('Y') }})</h2>
                <div class="chart-wrapper">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'month';
        let dailyChart = null;
        let monthlyChart = null;

        // Format Rupiah
        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Load Summary
        async function loadSummary() {
            try {
                const response = await fetch('/admin/revenue/summary');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('todayRevenue').textContent = formatRupiah(data.summary.today);
                    document.getElementById('monthRevenue').textContent = formatRupiah(data.summary.month);
                    document.getElementById('yearRevenue').textContent = formatRupiah(data.summary.year);
                }
            } catch (error) {
                console.error('Error loading summary:', error);
            }
        }

        // Load Daily Revenue Chart
        async function loadDailyChart(filter) {
            try {
                const response = await fetch(`/admin/revenue/daily?filter=${filter}`);
                const data = await response.json();
                
                if (data.success) {
                    const ctx = document.getElementById('dailyChart').getContext('2d');
                    
                    // Destroy existing chart
                    if (dailyChart) {
                        dailyChart.destroy();
                    }
                    
                    // Create new chart
                    dailyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.chartData.labels,
                            datasets: [{
                                label: 'Penghasilan Harian',
                                data: data.chartData.data,
                                borderColor: '#8B4513',
                                backgroundColor: 'rgba(139, 69, 19, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#3E2723',
                                        font: {
                                            size: 14,
                                            weight: '600'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(139, 69, 19, 0.9)',
                                    callbacks: {
                                        label: function(context) {
                                            return 'Penghasilan: ' + formatRupiah(context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        color: '#666'
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#666'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading daily chart:', error);
            }
        }

        // Load Monthly Revenue Chart
        async function loadMonthlyChart() {
            try {
                const response = await fetch('/admin/revenue/monthly');
                const data = await response.json();
                
                if (data.success) {
                    const ctx = document.getElementById('monthlyChart').getContext('2d');
                    
                    // Destroy existing chart
                    if (monthlyChart) {
                        monthlyChart.destroy();
                    }
                    
                    // Create new chart
                    monthlyChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.chartData.labels,
                            datasets: [{
                                label: 'Penghasilan Bulanan',
                                data: data.chartData.data,
                                backgroundColor: 'rgba(139, 69, 19, 0.8)',
                                borderColor: '#8B4513',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#3E2723',
                                        font: {
                                            size: 14,
                                            weight: '600'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(139, 69, 19, 0.9)',
                                    callbacks: {
                                        label: function(context) {
                                            return 'Penghasilan: ' + formatRupiah(context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return formatRupiah(value);
                                        },
                                        color: '#666'
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#666'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading monthly chart:', error);
            }
        }

        // Set Filter
        function setFilter(filter) {
            currentFilter = filter;
            
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Reload daily chart
            loadDailyChart(filter);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSummary();
            loadDailyChart(currentFilter);
            loadMonthlyChart();
        });
    </script>
</body>
</html>
