@extends('layouts.admin')

@section('page-title', 'Deteksi Fraud')

@section('content')
<style>
    .fraud-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .stat-label {
        color: #999;
        font-size: 0.875rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        color: #FFD700;
        font-size: 2rem;
        font-weight: 700;
    }

    .fraud-table {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        overflow: hidden;
    }

    .fraud-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .fraud-table th {
        background: #1a1a1a;
        color: #FFD700;
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        border-bottom: 1px solid #FFD700;
    }

    .fraud-table td {
        padding: 1rem;
        border-bottom: 1px solid #333;
        color: #ccc;
    }

    .fraud-table tr:hover {
        background: rgba(255, 215, 0, 0.05);
    }

    .risk-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .risk-high {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }

    .risk-medium {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .risk-low {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .status-approved {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }

    .status-rejected {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view {
        background: rgba(255, 215, 0, 0.2);
        color: #FFD700;
    }

    .btn-view:hover {
        background: rgba(255, 215, 0, 0.3);
    }
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="color: #FFD700; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">🚨 Deteksi Fraud Order</h1>
    <p style="color: #999; font-size: 0.875rem;">Monitor dan review order yang mencurigakan</p>
</div>

<!-- Stats -->
<div class="fraud-stats">
    <div class="stat-card">
        <div class="stat-label">Risiko Tinggi</div>
        <div class="stat-value">{{ $stats['high_risk'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Risiko Sedang</div>
        <div class="stat-value">{{ $stats['medium_risk'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Menunggu Review</div>
        <div class="stat-value">{{ $stats['pending_review'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ditolak</div>
        <div class="stat-value">{{ $stats['rejected'] }}</div>
    </div>
</div>

<!-- Filters -->
<div style="background: linear-gradient(135deg, #2a2a2a 0%, #333 100%); border: 1px solid #FFD700; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div>
            <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Status</label>
            <select name="status" style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px;">
                <option value="">Semua</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Tingkat Risiko</label>
            <select name="risk_level" style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px;">
                <option value="">Semua</option>
                <option value="HIGH" {{ request('risk_level') === 'HIGH' ? 'selected' : '' }}>Tinggi (70+)</option>
                <option value="MEDIUM" {{ request('risk_level') === 'MEDIUM' ? 'selected' : '' }}>Sedang (40-69)</option>
                <option value="LOW" {{ request('risk_level') === 'LOW' ? 'selected' : '' }}>Rendah (<40)</option>
            </select>
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" style="width: 100%; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #1a1a1a; border: none; padding: 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer;">Filter</button>
        </div>
    </form>
</div>

<!-- Fraud Table -->
<div class="fraud-table">
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Nomor Telepon</th>
                <th>Nama Customer</th>
                <th>Total</th>
                <th>Risk Score</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($frauds as $fraud)
            <tr>
                <td><strong>{{ $fraud->order?->order_number ?? '-' }}</strong></td>
                <td>{{ $fraud->phone_number }}</td>
                <td>{{ $fraud->order?->customer_name ?? '-' }}</td>
                <td>Rp {{ number_format($fraud->order?->final_total ?? 0, 0, ',', '.') }}</td>
                <td>
                    <span class="risk-badge risk-{{ strtolower($fraud->getRiskLevel()) }}">
                        {{ $fraud->risk_score }}% - {{ $fraud->getRiskLevel() }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ $fraud->status }}">
                        {{ ucfirst($fraud->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.fraud.show', $fraud->id) }}" class="btn-action btn-view">Review</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">Tidak ada fraud detection</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($frauds->hasPages())
<div style="margin-top: 2rem; text-align: center;">
    {{ $frauds->links() }}
</div>
@endif

@endsection
