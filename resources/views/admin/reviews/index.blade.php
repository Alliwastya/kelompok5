@extends('layouts.admin')

@section('title', 'Manajemen Ulasan')

@section('content')
<div style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #FFD700; margin-bottom: 0.5rem;">⭐ Manajemen Ulasan</h1>
            <p style="color: #999;">Kelola ulasan dari pelanggan untuk ditampilkan di halaman utama.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(76, 175, 80, 0.1); border: 1px solid #4CAF50; color: #4CAF50; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #2a2a2a; border-radius: 12px; border: 1px solid #333; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #333; color: #FFD700;">
                    <th style="padding: 1rem; font-weight: 600;">Pelanggan</th>
                    <th style="padding: 1rem; font-weight: 600;">Rating</th>
                    <th style="padding: 1rem; font-weight: 600;">Komentar</th>
                    <th style="padding: 1rem; font-weight: 600;">Media</th>
                    <th style="padding: 1rem; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; font-weight: 600; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr style="border-bottom: 1px solid #333; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 215, 0, 0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: #fff;">{{ $review->display_name ?: ($review->order ? $review->order->customer_name : 'N/A') }}</div>
                            <div style="font-size: 0.8rem; color: #666;">#{{ $review->order ? $review->order->order_number : 'N/A' }}</div>
                            <div style="font-size: 0.75rem; color: #555;">{{ $review->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="color: #FFD700;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ★
                                    @else
                                        <span style="color: #444;">★</span>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="max-width: 300px; color: #ccc; font-size: 0.9rem; line-height: 1.4;">
                                "{{ $review->comment ?: 'Tidak ada komentar.' }}"
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            @if($review->media_urls && count($review->media_urls) > 0)
                                <div style="display: flex; gap: 5px;">
                                    @foreach($review->media_urls as $url)
                                        <a href="/{{ $url }}" target="_blank">
                                            <img src="/{{ $url }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #444;">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span style="color: #444; font-size: 0.8rem;">Tidak ada media</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($review->is_visible)
                                <span style="background: rgba(76, 175, 80, 0.1); color: #4CAF50; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">Terlihat</span>
                            @else
                                <span style="background: rgba(244, 67, 54, 0.1); color: #F44336; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">Tersembunyi</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: {{ $review->is_visible ? 'rgba(244, 67, 54, 0.1)' : 'rgba(76, 175, 80, 0.1)' }}; color: {{ $review->is_visible ? '#F44336' : '#4CAF50' }}; border: 1px solid {{ $review->is_visible ? '#F44336' : '#4CAF50' }}; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;">
                                    {{ $review->is_visible ? '👁️ Sembunyikan' : '👁️ Tampilkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: #666;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
                            <div>Belum ada ulasan dari pelanggan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $reviews->links() }}
    </div>
</div>

<style>
    /* Custom styles for pagination if needed */
    .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
    }
    .page-item .page-link {
        padding: 8px 16px;
        background: #2a2a2a;
        border: 1px solid #333;
        color: #FFD700;
        text-decoration: none;
        border-radius: 4px;
    }
    .page-item.active .page-link {
        background: #FFD700;
        color: #1a1a1a;
        border-color: #FFD700;
    }
</style>
@endsection
