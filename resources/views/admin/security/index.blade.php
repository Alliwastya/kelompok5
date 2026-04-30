@extends('layouts.admin')

@section('page-title', 'Keamanan')

@section('content')
<style>
    .security-stats {
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

    .logs-table {
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 1px solid #FFD700;
        border-radius: 12px;
        overflow: hidden;
    }

    .logs-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .logs-table th {
        background: #1a1a1a;
        color: #FFD700;
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        border-bottom: 1px solid #FFD700;
    }

    .logs-table td {
        padding: 1rem;
        border-bottom: 1px solid #333;
        color: #ccc;
    }

    .logs-table tr:hover {
        background: rgba(255, 215, 0, 0.05);
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-blocked {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }

    .badge-suspicious {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .badge-normal {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .btn-block {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
    }

    .btn-block:hover {
        background: rgba(244, 67, 54, 0.3);
    }

    .btn-unblock {
        background: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
    }

    .btn-unblock:hover {
        background: rgba(76, 175, 80, 0.3);
    }
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="color: #FFD700; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">🔒 Keamanan Sistem</h1>
    <p style="color: #999; font-size: 0.875rem;">Monitor dan kelola aktivitas keamanan website</p>
</div>

<!-- Stats -->
<div class="security-stats">
    <div class="stat-card">
        <div class="stat-label">IP Terblokir</div>
        <div class="stat-value">{{ $blockedIps }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Aktivitas Mencurigakan (Hari Ini)</div>
        <div class="stat-value">{{ $suspiciousActivities }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Log</div>
        <div class="stat-value">{{ $logs->total() }}</div>
    </div>
</div>

<!-- Filters -->
<div style="background: linear-gradient(135deg, #2a2a2a 0%, #333 100%); border: 1px solid #FFD700; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div>
            <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">IP Address</label>
            <input type="text" name="ip" value="{{ request('ip') }}" placeholder="Cari IP..." style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px;">
        </div>
        <div>
            <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Tipe Event</label>
            <select name="event_type" style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px;">
                <option value="">Semua</option>
                <option value="order_attempt" {{ request('event_type') === 'order_attempt' ? 'selected' : '' }}>Order Attempt</option>
                <option value="failed_captcha" {{ request('event_type') === 'failed_captcha' ? 'selected' : '' }}>Failed CAPTCHA</option>
                <option value="suspicious_activity" {{ request('event_type') === 'suspicious_activity' ? 'selected' : '' }}>Suspicious Activity</option>
            </select>
        </div>
        <div>
            <label style="color: #FFD700; font-weight: 600; display: block; margin-bottom: 0.5rem;">Status</label>
            <select name="is_blocked" style="width: 100%; background: #1a1a1a; border: 1px solid #FFD700; color: #fff; padding: 0.75rem; border-radius: 6px;">
                <option value="">Semua</option>
                <option value="true" {{ request('is_blocked') === 'true' ? 'selected' : '' }}>Terblokir</option>
                <option value="false" {{ request('is_blocked') === 'false' ? 'selected' : '' }}>Tidak Terblokir</option>
            </select>
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" style="width: 100%; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #1a1a1a; border: none; padding: 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer;">Filter</button>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="logs-table">
    <table>
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Nomor Telepon</th>
                <th>Tipe Event</th>
                <th>Jumlah Order</th>
                <th>Status</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td><code style="background: #1a1a1a; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $log->ip_address }}</code></td>
                <td>{{ $log->phone_number ?? '-' }}</td>
                <td>
                    @if($log->event_type === 'order_attempt')
                        <span class="badge badge-normal">Order Attempt</span>
                    @elseif($log->event_type === 'failed_captcha')
                        <span class="badge badge-suspicious">Failed CAPTCHA</span>
                    @else
                        <span class="badge badge-suspicious">Suspicious</span>
                    @endif
                </td>
                <td>{{ $log->order_count }}</td>
                <td>
                    @if($log->is_blocked)
                        <span class="badge badge-blocked">🔒 Terblokir</span>
                        @if($log->blocked_until)
                            <div style="font-size: 0.75rem; color: #999; margin-top: 0.25rem;">Sampai: {{ $log->blocked_until->format('d M Y H:i') }}</div>
                        @endif
                    @else
                        <span class="badge badge-normal">✓ Normal</span>
                    @endif
                </td>
                <td style="font-size: 0.875rem;">{{ $log->created_at->format('d M Y H:i') }}</td>
                <td>
                    @if($log->is_blocked)
                        <form action="{{ route('admin.security.unblock', ['ip' => $log->ip_address]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin membuka blokir IP {{ $log->ip_address }}?')">
                            @csrf
                            <button type="submit" class="btn-action btn-unblock">Buka Blokir</button>
                        </form>
                    @else
                        <form action="{{ route('admin.security.block', ['ip' => $log->ip_address]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin memblokir IP {{ $log->ip_address }}?')">
                            @csrf
                            <input type="hidden" name="minutes" value="60">
                            <button type="submit" class="btn-action btn-block">Blokir (60 menit)</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">Tidak ada log keamanan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($logs->hasPages())
<div style="margin-top: 2rem; text-align: center;">
    {{ $logs->links() }}
</div>
@endif

@endsection
