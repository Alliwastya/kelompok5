@extends('layouts.admin')

@section('page-title', 'Keluar')

@section('content')
<style>
    .logout-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        padding: 2rem;
    }
    
    .logout-logo {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.3);
    }
    
    .logout-logo img {
        width: 120px;
        height: 120px;
        object-fit: contain;
    }
    
    .logout-content {
        text-align: center;
        background: linear-gradient(135deg, #2a2a2a 0%, #333 100%);
        border: 2px solid #FFD700;
        border-radius: 16px;
        padding: 3rem 2rem;
        max-width: 500px;
    }
    
    .logout-title {
        color: #FFD700;
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 1rem;
        font-family: 'Playfair Display', serif;
    }
    
    .logout-subtitle {
        color: #ccc;
        font-size: 1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .logout-buttons {
        display: flex;
        gap: 1rem;
        flex-direction: column;
    }
    
    .logout-btn {
        padding: 1rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .logout-btn-primary {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #1a1a1a;
    }
    
    .logout-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
    }
    
    .logout-btn-secondary {
        background: transparent;
        color: #FFD700;
        border: 2px solid #FFD700;
    }
    
    .logout-btn-secondary:hover {
        background: rgba(255, 215, 0, 0.1);
    }
    
    .logout-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>

<div class="logout-container">
    <!-- Logo -->
    <div class="logout-logo">
        🍞
    </div>
    
    <!-- Content -->
    <div class="logout-content">
        <div class="logout-icon">👋</div>
        <div class="logout-title">Keluar</div>
        <div class="logout-subtitle">
            Apakah Anda yakin ingin keluar dari Dapoer Budess Admin Panel?
        </div>
        
        <div class="logout-buttons">
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="logout-btn logout-btn-primary" style="width: 100%;">
                    ✓ Ya, Keluar Sekarang
                </button>
            </form>
            <a href="{{ route('admin.dashboard') }}" class="logout-btn logout-btn-secondary" style="width: 100%; text-align: center;">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
