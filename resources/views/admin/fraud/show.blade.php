@extends('layouts.admin')

@section('page-title', 'Review Fraud Detection')

@section('content')
<style>
    .detail-card {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-card h2 {
        color: #FFD700;
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #333;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #999;
        font-weight: 600;
    }

    .detail-value {
        color: #fff;
        font-weight: 600;
    }

    .risk-factors {
        background: #1a1a1a;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .risk-factor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        color: #ccc;
    }

    .risk-factor-icon {
        color: #f44336;
        font-weight: bold;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-approve {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
        border: 1px solid #4CAF50;
        padding: 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-approve:hover {
        background: rgba(76, 175, 80, 0.3);
    }

    .btn-reject {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
        border: 1px solid #f44336;
        padding: 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-reject:hover {
        background: rgba(244, 67, 54, 0.3);
    }

    .back-btn {
        color: #FFD700;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .back-btn:hover {
        color: #FFA500;
    }
</style>

<a href="{{ route('admin.fraud.index') }}" class="back-btn">← Kembali ke Fraud Detection</a>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div>
        <!-- Fraud Info -->
        <div class="detail-card">
            <h2>🚨 Informasi Fraud Detection</h2>
            <div class="detail-row">
                <span class="detail-label">Risk Score</span>
                <span class="detail-value">{{ $fraud->risk_score }}% - {{ $fraud->getRiskLevel() }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">{{ ucfirst($fraud->status) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">IP Address</span>
                <span class="detail-value"><code style="background: #1a1a1a; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $fraud->ip_address }}</code></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nomor Telepon</span>
                <span class="detail-value">{{ $fraud->phone_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Waktu Deteksi</span>
                <span class="detail-value">{{ $fraud->created_at->format('d M Y H:i') }}</span>
            </div>

            <!-- Risk Factors -->
            <div class="risk-factors">
                <div style="color: #FFD700; font-weight: 700; margin-bottom: 1rem;">⚠️ Faktor Risiko:</div>
                @forelse($fraud->risk_factors as $factor)
                    <div class="risk-factor">
                        <span class="risk-factor-icon">✗</span>
                        <span>{{ $factor }}</span>
                    </div>
                @empty
                    <div style="color: #999;">Tidak ada faktor risiko</div>
                @endforelse
            </div>
        </div>

        <!-- Order Info -->
        @if($fraud->order)
        <div class="detail-card">
            <h2>📦 Informasi Order</h2>
            <div class="detail-row">
                <span class="detail-label">Order Number</span>
                <span class="detail-value">{{ $fraud->order->order_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nama Customer</span>
                <span class="detail-value">{{ $fraud->order->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Alamat</span>
                <span class="detail-value">{{ $fraud->order->customer_address }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Order</span>
                <span class="detail-value">Rp {{ number_format($fraud->order->final_total, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Metode Pembayaran</span>
                <span class="detail-value">{{ $fraud->order->payment_method }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Order</span>
                <span class="detail-value">{{ $fraud->order->status }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Action Card -->
        <div class="detail-card">
            <h2>⚙️ Aksi</h2>
            
            @if($fraud->status === 'pending')
                <form action="{{ route('admin.fraud.approve', $fraud->id) }}" method="POST" style="margin-bottom: 1rem;">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Catatan (Opsional)</label>
                        <textarea name="notes" style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px; resize: vertical; height: 80px;"></textarea>
                    </div>
                    <button type="submit" class="btn-approve" style="width: 100%;">✓ Setujui Order</button>
                </form>

                <form action="{{ route('admin.fraud.reject', $fraud->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Alasan Penolakan</label>
                        <textarea name="notes" required style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px; resize: vertical; height: 80px;"></textarea>
                    </div>
                    <button type="submit" class="btn-reject" style="width: 100%;">✕ Tolak Order</button>
                </form>
            @else
                <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center;">
                    <div style="color: #999; font-size: 0.875rem; margin-bottom: 0.5rem;">Status</div>
                    <div style="color: #FFD700; font-weight: 700; font-size: 1.125rem;">{{ ucfirst($fraud->status) }}</div>
                    @if($fraud->reviewed_at)
                    <div style="color: #999; font-size: 0.875rem; margin-top: 1rem;">
                        Direview pada: {{ $fraud->reviewed_at->format('d M Y H:i') }}
                    </div>
                    @endif
                </div>

                @if($fraud->notes)
                <div style="background: #1a1a1a; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    <div style="color: #FFD700; font-weight: 600; margin-bottom: 0.5rem;">Catatan:</div>
                    <div style="color: #ccc; font-size: 0.875rem; white-space: pre-wrap;">{{ $fraud->notes }}</div>
                </div>
                @endif
            @endif
        </div>

        <!-- View Order -->
        @if($fraud->order)
        <div class="detail-card">
            <a href="{{ route('admin.orders.show', $fraud->order->id) }}" style="display: block; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #1a1a1a; padding: 1rem; border-radius: 8px; text-align: center; font-weight: 600; text-decoration: none; transition: all 0.3s;">
                Lihat Detail Order →
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
