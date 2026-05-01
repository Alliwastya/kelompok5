<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dapoer Budess - Roti Rumahan </title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@500;600&family=Outfit:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #8B4513;
            --secondary: #D2691E;
            --accent: #F4A460;
            --dark: #2C1810;
            --cream: #FFF8DC;
            --light-cream: #FFFAF0;
            --text: #3E2723;
            --shadow: rgba(139, 69, 19, 0.15);
            --gold: #FFD700;
            --warm-bg: #FFFDF9;
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered, .status-completed { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            line-height: 1.6;
            color: var(--dark);
            background: linear-gradient(135deg, #F5EDE3 0%, #EDE4D9 50%, #F5EDE3 100%);
            background-attachment: fixed;
            overflow-x: hidden;
            position: relative;
            padding-top: 0;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(139, 90, 60, 0.03) 35px, rgba(139, 90, 60, 0.03) 70px),
                repeating-linear-gradient(-45deg, transparent, transparent 35px, rgba(139, 90, 60, 0.03) 35px, rgba(139, 90, 60, 0.03) 70px);
            opacity: 0.6;
            z-index: 0;
            pointer-events: none !important;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' /%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100' height='100' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none !important;
        }

        .wheat-left {
            position: fixed;
            bottom: -20px;
            left: -20px;
            width: 280px;
            height: 350px;
            opacity: 0.25;
            z-index: 1;
            pointer-events: none !important;
        }

        .wheat-right {
            position: fixed;
            top: -20px;
            right: -20px;
            width: 280px;
            height: 350px;
            opacity: 0.25;
            z-index: 1;
            pointer-events: none !important;
            transform: rotate(180deg);
        }

        .batik-corner-left {
            position: fixed;
            top: 80px;
            left: 20px;
            width: 150px;
            height: 150px;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none !important;
        }

        .batik-corner-right {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 150px;
            height: 150px;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none !important;
            transform: rotate(180deg);
        }

        .banana-leaf {
            position: fixed;
            opacity: 0.06;
            z-index: 1;
            pointer-events: none !important;
        }

        .banana-leaf-1 {
            top: 15%;
            right: 5%;
            width: 200px;
            height: 120px;
            transform: rotate(-15deg);
        }

        .banana-leaf-2 {
            bottom: 25%;
            left: 8%;
            width: 180px;
            height: 110px;
            transform: rotate(25deg);
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: rgba(44, 24, 16, 0.98);
            backdrop-filter: blur(10px);
            padding: 0.8rem 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(244, 164, 96, 0.1);
            transition: all 0.3s ease;
            pointer-events: auto !important;
        }

        header.scrolled {
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: rgba(44, 24, 16, 1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .logo-img {
            max-width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--cream);
            letter-spacing: 1px;
        }

        .header-actions {
            display: flex;
            gap: 1.25rem;
            align-items: center;
            pointer-events: auto !important;
            z-index: 10001;
            position: relative;
        }

        .cart-btn, .menu-btn, .message-btn, .admin-btn {
            background: transparent;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            color: var(--cream);
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            pointer-events: auto !important;
            z-index: 10001;
        }

        .cart-btn:hover, .menu-btn:hover, .message-btn:hover, .admin-btn:hover {
            color: var(--accent);
            transform: translateY(-2px);
            background: rgba(255,255,255,0.05);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #FF4444;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            pointer-events: none;
        }

        .product-image {
            height: 200px;
            overflow: hidden;
            background: #fdf5e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            position: relative;
        }

        .bestseller-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: linear-gradient(135deg, #FF4B2B, #FF416C);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3);
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 95vh;
            min-height: 650px;
            overflow: hidden;
            background: #2c1e19;
            margin-bottom: 0;
            margin-top: -80px;
            border-radius: 0;
            padding-top: 80px;
            z-index: 2;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 8%;
            z-index: 1;
            pointer-events: none;
        }

        .slide.active {
            opacity: 1;
            z-index: 2;
            pointer-events: auto;
        }

        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            pointer-events: none;
        }

        .slide-1::before {
            background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.2) 100%);
        }

        .slide-2::before {
            background: linear-gradient(0deg, rgba(40,20,10,0.85) 0%, rgba(40,20,10,0.4) 50%, rgba(40,20,10,0.2) 100%);
        }

        .slide-3::before {
            background: linear-gradient(270deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.2) 100%);
        }

        .slide-content {
            position: relative;
            z-index: 10;
            max-width: 650px;
            color: #fff;
            padding-left: 2rem;
        }

        .slide h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 10px rgba(0,0,0,0.8);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out 0.3s;
        }

        .slide p {
            font-family: 'Lora', serif;
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out 0.5s;
            max-width: 550px;
        }

        .slide.active h1,
        .slide.active p {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-btn {
            display: inline-block;
            background: linear-gradient(135deg, #d39e00 0%, #b8860b 100%);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(184, 134, 11, 0.5);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out 0.7s, background 0.3s, transform 0.3s;
            cursor: pointer;
        }

        .slide.active .hero-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 25px rgba(184, 134, 11, 0.7);
            background: linear-gradient(135deg, #e6ac00 0%, #d49a0c 100%);
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 3.5rem;
            height: 3.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 20;
            color: white;
            transition: all 0.3s ease;
            font-size: 1.4rem;
        }

        .slider-nav:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .prev-slide { left: 2.5rem; }
        .next-slide { right: 2.5rem; }

        .slider-dots {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.8rem;
            z-index: 20;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .dot.active {
            background: white;
            transform: scale(1.3);
            box-shadow: 0 0 12px rgba(255,255,255,0.6);
        }

        .slide-2 {
            justify-content: center;
            text-align: center;
        }
        .slide-2 .slide-content {
            max-width: 800px;
            padding-left: 0;
        }

        .slide-3 {
            justify-content: flex-end;
            text-align: right;
        }
        .slide-3 .slide-content {
            padding-left: 0;
            padding-right: 2rem;
        }

        @media (max-width: 768px) {
            body { padding-top: 0; }
            .hero-slider { height: 75vh; min-height: 500px; }
            .slide { padding: 0 5%; align-items: center; }
            .slide-content { padding-left: 0; padding-right: 0; max-width: 100%; }
            .slide h1 { font-size: 3rem; margin-bottom: 1rem; line-height: 1.2; }
            .slide p { font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6; max-width: 100%; }
            .hero-btn { padding: 0.8rem 1.8rem; font-size: 0.85rem; }
            .slider-nav { width: 2.5rem; height: 2.5rem; font-size: 1.1rem; }
            .prev-slide { left: 1rem; }
            .next-slide { right: 1rem; }
            .slider-dots { bottom: 1.5rem; gap: 0.6rem; }
            .dot { width: 9px; height: 9px; }
            .slide-2, .slide-3 { justify-content: center; text-align: center; }
            .slide-3 .slide-content { padding-right: 0; }
        }
        
        @media (max-width: 480px) {
            .hero-slider { height: 75vh; min-height: 450px; }
            .slide h1 { font-size: 1.7rem; }
            .slide p { font-size: 0.9rem; }
            .hero-btn { padding: 0.7rem 1.5rem; font-size: 0.8rem; }
            .success-message { padding: 2rem 1.5rem; width: 95%; max-width: 100%; }
            .success-message h2 { font-size: 1.6rem !important; }
            .success-icon { font-size: 3rem !important; margin-bottom: 1rem !important; }
        }

        /* ========== PROMO BANNER SECTION v2 ========== */
        .promo-section {
            background: transparent;
            padding: 2rem 0;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeInScale 0.6s ease-out 0.3s both;
            margin: 0;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .promo-card {
            background: 
                linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, transparent 50%),
                linear-gradient(225deg, rgba(232, 130, 26, 0.08) 0%, transparent 60%),
                linear-gradient(135deg, rgba(245, 230, 200, 0.95) 0%, rgba(240, 221, 184, 0.95) 50%, rgba(232, 213, 176, 0.95) 100%),
                url('https://images.unsplash.com/photo-1589569444360-61ed59c6e2be?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') !important;
            background-size: cover, cover, cover, cover;
            background-position: center, center, center, center;
            background-blend-mode: normal, normal, normal, multiply;
            border-radius: 32px !important;
            padding: 0 !important;
            box-shadow: 
                0 0 0 3px rgba(255, 255, 255, 0.9),
                0 0 0 6px rgba(232, 130, 26, 0.6),
                0 0 0 9px rgba(255, 215, 0, 0.4),
                0 0 30px rgba(255, 215, 0, 0.3),
                0 20px 60px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.5) !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            transition: all 0.4s ease;
            max-width: 1250px;
            width: 94%;
            margin: 0 auto;
            height: auto;
            animation: borderGlow 3s ease-in-out infinite;
        }

        @keyframes borderGlow {
            0%, 100% { 
                box-shadow: 
                    0 0 0 3px rgba(255, 255, 255, 0.9),
                    0 0 0 6px rgba(232, 130, 26, 0.6),
                    0 0 0 9px rgba(255, 215, 0, 0.4),
                    0 0 30px rgba(255, 215, 0, 0.3),
                    0 20px 60px rgba(0, 0, 0, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.5);
            }
            50% { 
                box-shadow: 
                    0 0 0 3px rgba(255, 255, 255, 1),
                    0 0 0 6px rgba(232, 130, 26, 0.8),
                    0 0 0 9px rgba(255, 215, 0, 0.6),
                    0 0 40px rgba(255, 215, 0, 0.5),
                    0 20px 60px rgba(0, 0, 0, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.5);
            }
        }

        /* Texture overlay - Enhanced */
        .promo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(232, 130, 26, 0.12) 30%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(45, 122, 58, 0.08) 20%, transparent 30%),
                radial-gradient(circle at 50% 50%, rgba(255, 215, 0, 0.05) 30%, transparent 30%);
            pointer-events: none;
            z-index: 1;
        }

        /* Grain texture + Pattern */
        .promo-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(61, 31, 0, 0.03) 2px, rgba(61, 31, 0, 0.03) 4px),
                repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(61, 31, 0, 0.02) 2px, rgba(61, 31, 0, 0.02) 4px);
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        .promo-content {
            position: relative;
            z-index: 2;
            display: flex;
            height: 100%;
            min-height: 400px;
        }

        /* ========== LEFT COLUMN ========== */
        .promo-left {
            width: 46%;
            padding: 2.5rem 2rem 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }

        .promo-badges {
            display: flex;
            gap: 8px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .promo-badge {
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #E53935 0%, #E8821A 100%);
            animation: shimmer 3s ease-in-out infinite;
        }

        .discount-badge {
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #2D7A3A 0%, #45a049 100%);
            animation: shimmer 3s ease-in-out infinite 0.5s;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.9; }
        }

        .promo-title-section {
            margin-bottom: 0.8rem;
            position: relative;
        }

        .wheat-ornament-left {
            position: absolute;
            left: -35px;
            top: 10px;
            font-size: 48px;
            opacity: 0.35;
            transform: rotate(-15deg);
            filter: drop-shadow(0 2px 4px rgba(200, 168, 107, 0.3));
        }

        .promo-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            line-height: 1.2;
            color: #3D1F00;
            font-weight: 900;
            margin: 0;
            text-align: left;
        }

        .promo-title .highlight {
            color: #E8821A;
        }

        .promo-subtitle {
            font-size: 0.9rem;
            line-height: 1.5;
            color: #6B4E3D;
            margin: 0 0 1rem 0;
            text-align: left;
        }

        .promo-pricing {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        /* Countdown Timer Styling */
        .promo-countdown {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
            justify-content: flex-start;
        }

        .timer-item {
            background: #fff;
            border: 1px solid rgba(232, 130, 26, 0.2);
            border-radius: 10px;
            padding: 8px;
            min-width: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        .timer-item span {
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: #E8821A;
            line-height: 1;
        }

        .timer-item small {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #8b5a2b;
            font-weight: 600;
            margin-top: 4px;
        }

        @media (max-width: 480px) {
            .promo-countdown {
                justify-content: center;
                gap: 0.5rem;
                margin-bottom: 1.2rem;
            }
            .timer-item {
                min-width: 55px;
                padding: 6px;
            }
            .timer-item span {
                font-size: 1.1rem;
            }
        }

        .price-original {
            font-size: 1.2rem;
            color: #999;
            text-decoration: line-through;
            font-weight: 500;
        }

        .price-discount {
            font-size: 2.5rem;
            color: #E8821A;
            font-weight: 700;
        }

        .price-save {
            display: inline-block;
            padding: 6px 14px;
            border: 2px solid #E8821A;
            border-radius: 50px;
            color: #E8821A;
            font-size: 13px;
            font-weight: 700;
        }

        .promo-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .feature-item {
            text-align: center;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border: 2px solid #E8821A;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 6px;
            font-size: 20px;
        }

        .feature-title {
            font-size: 11px;
            font-weight: 700;
            color: #3D1F00;
            margin-bottom: 3px;
        }

        .feature-subtitle {
            font-size: 9px;
            color: #999;
        }

        .promo-cta {
            width: 100%;
            padding: 16px 28px;
            background: linear-gradient(135deg, #E07B00 0%, #F5A623 100%);
            color: white;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 
                0 8px 20px rgba(232, 130, 26, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            position: relative;
            overflow: hidden;
        }

        .promo-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .promo-cta:hover::before {
            left: 100%;
        }

        .promo-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(232, 130, 26, 0.5);
        }

        .promo-note {
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        /* ========== RIGHT COLUMN ========== */
        .promo-right {
            width: 54%;
            position: relative;
        }

        .promo-images {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .promo-image-item {
            position: absolute;
            border-radius: 15px;
            box-shadow: 
                0 0 0 3px rgba(255, 255, 255, 0.95),
                0 0 0 5px rgba(232, 130, 26, 0.5),
                0 0 0 7px rgba(255, 215, 0, 0.35),
                0 0 20px rgba(255, 215, 0, 0.25),
                0 8px 24px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: #ddd;
            border: none;
            transition: all 0.3s ease;
            animation: imageBorderGlow 3s ease-in-out infinite;
        }

        @keyframes imageBorderGlow {
            0%, 100% { 
                box-shadow: 
                    0 0 0 3px rgba(255, 255, 255, 0.95),
                    0 0 0 5px rgba(232, 130, 26, 0.5),
                    0 0 0 7px rgba(255, 215, 0, 0.35),
                    0 0 20px rgba(255, 215, 0, 0.25),
                    0 8px 24px rgba(0, 0, 0, 0.15);
            }
            50% { 
                box-shadow: 
                    0 0 0 3px rgba(255, 255, 255, 1),
                    0 0 0 5px rgba(232, 130, 26, 0.7),
                    0 0 0 7px rgba(255, 215, 0, 0.55),
                    0 0 30px rgba(255, 215, 0, 0.4),
                    0 8px 24px rgba(0, 0, 0, 0.15);
            }
        }

        .promo-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-image-item:hover {
            transform: scale(1.03);
            box-shadow: 
                0 0 0 3px rgba(255, 255, 255, 1),
                0 0 0 5px rgba(232, 130, 26, 0.8),
                0 0 0 7px rgba(255, 215, 0, 0.6),
                0 0 35px rgba(255, 215, 0, 0.5),
                0 12px 32px rgba(0, 0, 0, 0.2);
            animation: none;
        }

        .promo-image-main {
            width: 360px;
            height: 310px;
            top: 25px;
            right: 55px;
            transform: rotate(2deg);
            z-index: 3;
        }

        .promo-image-second {
            width: 200px;
            height: 180px;
            bottom: 25px;
            left: 85px;
            transform: rotate(-3deg);
            z-index: 2;
        }

        .promo-image-third {
            width: 220px;
            height: 200px;
            bottom: 20px;
            right: 105px;
            transform: rotate(1deg);
            z-index: 1;
        }

        .wheat-decoration {
            position: absolute;
            top: 30px;
            right: 20px;
            font-size: 120px;
            color: #C8A86B;
            opacity: 0.25;
            transform: rotate(15deg);
            z-index: 0;
            text-shadow: 0 2px 10px rgba(200, 168, 107, 0.3);
        }

        .best-seller-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #3D1F00 0%, #2C1500 100%);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            text-align: center;
            border: 3px solid #C8A86B;
            box-shadow: 
                0 6px 20px rgba(0, 0, 0, 0.3),
                0 0 0 5px rgba(200, 168, 107, 0.2),
                inset 0 2px 0 rgba(255, 255, 255, 0.1);
            z-index: 10;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .best-seller-badge .crown {
            font-size: 24px;
            margin-bottom: 2px;
        }

        .best-seller-badge .text {
            font-size: 11px;
            line-height: 1.2;
        }

        .best-seller-badge .stars {
            font-size: 10px;
            color: #C8A86B;
        }

        /* ========== MOBILE RESPONSIVE (PREMIUM INSPIRATION STYLE) ========== */
        @media (max-width: 992px) {
            .promo-section { padding: 15px 0 !important; }
            
            .promo-card { 
                margin: 0 auto !important; 
                width: 100% !important;
                border-radius: 40px !important; 
                padding: 40px 22px !important;
                background: #FFF9F0 !important;
                border: 1.5px solid rgba(212, 175, 55, 0.3) !important;
                box-shadow: 0 20px 60px rgba(139, 69, 19, 0.1) !important;
                position: relative !important;
                overflow: hidden !important;
            }

            .promo-content {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0 !important;
            }

            .promo-left, .promo-right { 
                width: 100% !important; 
                display: block !important;
                position: relative !important;
                padding: 0 !important;
                transform: none !important;
            }

            /* 1. Badges */
            .promo-badges {
                display: flex !important;
                gap: 10px !important;
                margin-bottom: 20px !important;
            }
            .promo-badge, .discount-badge {
                padding: 7px 15px !important;
                font-size: 11px !important;
                border-radius: 50px !important;
                font-weight: 700 !important;
            }

            /* 2. Title & Subtitle */
            .promo-title { 
                font-size: 2.2rem !important; 
                text-align: left !important; 
                margin-bottom: 12px !important;
                color: #3e2723 !important;
                line-height: 1.1 !important;
                font-weight: 900 !important;
            }
            .promo-subtitle { 
                font-size: 0.9rem !important;
                text-align: left !important;
                margin-bottom: 25px !important;
                color: #6B4E3D !important;
                line-height: 1.5 !important;
                opacity: 0.8 !important;
            }

            /* 3. Pricing */
            .promo-pricing {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 12px !important;
                margin-bottom: 30px !important;
            }
            .price-original { font-size: 1rem !important; opacity: 0.4 !important; text-decoration: line-through !important; }
            .price-discount { font-size: 2.2rem !important; color: #E8821A !important; font-weight: 800 !important; }
            .price-save { border-radius: 50px !important; padding: 4px 12px !important; font-size: 10px !important; }

            /* 4. Timer */
            .promo-countdown {
                display: flex !important;
                justify-content: flex-start !important;
                gap: 8px !important;
                margin-bottom: 35px !important;
            }
            .timer-item {
                background: white !important;
                min-width: 62px !important;
                border-radius: 12px !important;
                padding: 10px 4px !important;
                box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
                border: 1px solid rgba(212, 175, 55, 0.1) !important;
            }

            /* 5. 3 IMAGES (GRID) - SEJAJAR & RAPAT */
            .promo-images {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 10px !important;
                width: 100% !important;
                height: auto !important;
                margin-bottom: 30px !important;
                position: relative !important;
                padding: 0 !important;
                left: auto !important;
                right: auto !important;
                top: auto !important;
                bottom: auto !important;
            }
            .promo-image-item {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
                transform: none !important;
                width: 100% !important;
                aspect-ratio: 1/1.1 !important;
                height: auto !important;
                border-radius: 12px !important;
                border: 2px solid white !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.05) !important;
                animation: none !important;
                overflow: hidden !important;
            }
            .promo-image-item img { width: 100% !important; height: 100% !important; object-fit: cover !important; }

            /* 6. Features Icons */
            .promo-features {
                display: flex !important;
                justify-content: space-between !important;
                width: 100% !important;
                background: rgba(255, 255, 255, 0.5) !important;
                padding: 12px !important;
                border-radius: 20px !important;
                margin-bottom: 25px !important;
                border: 1px solid rgba(255,255,255,0.8) !important;
                gap: 5px !important;
            }
            .feature-icon { width: 28px !important; height: 28px !important; font-size: 11px !important; }
            .feature-title { font-size: 8px !important; font-weight: 700 !important; }

            /* 7. Button CTA */
            .promo-cta-container { width: 100% !important; margin: 0 !important; }
            .promo-cta { 
                padding: 18px !important; 
                font-size: 14px !important;
                border-radius: 50px !important;
                width: 100% !important;
                background: linear-gradient(135deg, #E8821A, #D46A00) !important;
                font-weight: 800 !important;
                letter-spacing: 0.5px !important;
            }

            /* DECORATIONS */
            .wheat-decoration {
                display: block !important;
                font-size: 160px !important;
                top: -20px !important;
                right: -40px !important;
                opacity: 0.1 !important;
                position: absolute !important;
                z-index: 0 !important;
            }
            .best-seller-badge, .wheat-ornament-left { display: none !important; }
            .promo-note { display: block !important; text-align: center !important; width: 100% !important; margin-top: 15px !important; font-size: 10px !important; }
        }

        .review-button-container {
            text-align: center;
            margin: 3rem 0;
            animation: fadeInUp 0.6s ease-out 0.5s both;
        }

        .review-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 1.2rem 3.5rem;
            border-radius: 50px;
            font-size: 1.15rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3), 0 3px 10px rgba(139, 69, 19, 0.15), inset 0 -2px 0 rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
        }

        .review-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 35px rgba(139, 69, 19, 0.4), 0 5px 15px rgba(139, 69, 19, 0.2), inset 0 -2px 0 rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #A0522D, #F4A460);
        }

        .review-btn:active {
            transform: translateY(-1px) scale(1);
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.3), 0 2px 8px rgba(139, 69, 19, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .testimoni-section {
            max-width: 1200px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
        }

        .testimoni-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(139, 69, 19, 0.1);
            animation: fadeInUp 0.6s ease-out;
        }

        .testimoni-grid { display: flex; flex-direction: column; gap: 2rem; }

        .testimoni-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAFAFA 100%);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04), inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 6px solid var(--accent);
            position: relative;
            overflow: hidden;
        }

        .testimoni-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(244, 164, 96, 0.1), transparent);
            border-radius: 50%;
            transform: translate(30%, -30%);
            pointer-events: none;
        }

        .testimoni-card:hover {
            transform: translateX(8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border-left-color: var(--secondary);
        }

        .testimoni-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .testimoni-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .testimoni-rating { color: #FFB800; font-size: 1.1rem; letter-spacing: 2px; }
        .testimoni-text { color: #555; line-height: 1.7; font-style: italic; font-size: 1.05rem; }

        nav {
            position: fixed;
            top: 82px;
            left: 0;
            right: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px var(--shadow);
            z-index: 9999;
            transition: all 0.3s ease;
        }

        nav.scrolled {
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.15);
            background: rgba(255, 255, 255, 1);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            justify-content: center;
            list-style: none;
            flex: 2;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--cream);
            font-family: 'Outfit', sans-serif;
            font-weight: 500;
            font-style: normal;
            transition: color 0.3s ease;
            position: relative;
            letter-spacing: 0.5px;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: width 0.3s ease;
        }

        .nav-links a:hover { color: var(--secondary); }

        .nav-links a:hover::after,
        .nav-links a.active::after { width: 100%; }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 2rem;
            position: relative;
            z-index: 2;
        }

        .section {
            display: none;
            animation: fadeIn 0.5s ease;
            position: relative;
            z-index: 2;
        }

        .section.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 2.5rem;
            color: #8B5A3C;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(139, 90, 60, 0.1);
            position: relative;
            animation: fadeInUp 0.6s ease-out;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            border-radius: 2px;
            box-shadow: 0 2px 4px rgba(212, 175, 55, 0.3);
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.5rem;
            margin-top: 2rem;
        }

        .review-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FEFEFE 100%);
            padding: 2.5rem;
            border-radius: 25px;
            box-shadow: 0 10px 35px rgba(139, 69, 19, 0.1), 0 3px 10px rgba(139, 69, 19, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(139, 69, 19, 0.05);
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '"';
            position: absolute;
            top: 10px;
            right: 20px;
            font-family: 'Playfair Display', serif;
            font-size: 8rem;
            color: rgba(244, 164, 96, 0.1);
            line-height: 1;
            pointer-events: none;
        }

        .review-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
            border-color: var(--accent);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .reviewer-info { display: flex; align-items: center; gap: 1rem; }

        .reviewer-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--accent), var(--secondary));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            font-family: 'Playfair Display';
        }

        .reviewer-details h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--dark);
            margin: 0;
            font-weight: 700;
        }

        .reviewer-time { font-size: 0.75rem; color: #999; margin-top: 2px; }

        .review-text {
            color: #555;
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .stars { color: #FFD700; font-size: 1.1rem; letter-spacing: 2px; }

        .star-rating { display: flex; gap: 0.5rem; justify-content: center; margin: 1rem 0; }

        .star {
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            line-height: 1;
        }

        .star:hover, .star:hover ~ .star { color: #FFD700; }
        .star.active { color: #FFD700 !important; }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .filter-wrapper { position: relative; min-width: 250px; }

        .sort-select {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-right: 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(139, 90, 60, 0.2);
            border-radius: 15px;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #8B5A3C;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 90, 60, 0.1);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%238B5A3C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 20px;
        }

        .sort-select:hover {
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 6px 20px rgba(139, 90, 60, 0.15);
            transform: translateY(-2px);
        }

        .sort-select:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 6px 25px rgba(212, 175, 55, 0.3);
        }

        .sort-select option { padding: 1rem; background: white; color: #8B5A3C; font-weight: 500; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .products-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        }

        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: visible;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 3px solid #D4AF37;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: #E6C200;
        }

        .product-card::before {
            content: '◆';
            position: absolute;
            top: -12px;
            left: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            z-index: 5;
            pointer-events: none;
        }

        .product-card::after {
            content: '◆';
            position: absolute;
            bottom: -12px;
            right: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            z-index: 5;
            pointer-events: none;
        }

        .product-image-wrapper {
            position: relative;
            padding-top: 75%;
            overflow: hidden;
            background: #f8f9fa;
        }

        .quick-add-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(-10px);
            font-size: 1.2rem;
        }

        .product-card:hover .quick-add-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .quick-add-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .product-image-wrapper::before {
            content: '◆';
            position: absolute;
            top: -12px;
            right: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            opacity: 1;
            z-index: 5;
            pointer-events: none;
        }
        
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img { transform: scale(1.1); }

        .product-promo-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #FF6B6B;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
            z-index: 2;
        }

        .product-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            position: relative;
        }

        .product-info::after {
            content: '◆';
            position: absolute;
            bottom: -12px;
            left: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            opacity: 1;
            z-index: 5;
            pointer-events: none;
        }

        .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .stock-badge {
            display: inline-block;
            background: #FFF3E0;
            color: #E65100;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: 600;
            margin-bottom: 1rem;
            width: fit-content;
        }

        .price-container {
            margin-top: auto;
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .price-old { font-size: 0.9rem; color: #999; text-decoration: line-through; }
        .price-new { font-size: 1.25rem; font-weight: 700; color: var(--primary); }
        .fresh-tag { display: none; }

        .cta-button {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            z-index: 3;
        }

        .cta-button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.2);
        }
        
        .cta-button:disabled {
            background: #eee;
            color: #aaa;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .bestseller-title-section {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .bestseller-title-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            color: #5D4037;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .bestseller-title-section::after {
            content: '';
            display: block;
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            margin: 0 auto;
            border-radius: 2px;
        }

        .feature-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin: 2rem 0;
            padding: 1rem;
        }

        .highlight-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(139, 69, 19, 0.05);
            position: relative;
        }

        .highlight-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.15);
        }

        .highlight-image {
            height: 250px;
            overflow: hidden;
            position: relative;
            background: #fff8eb;
        }

        .highlight-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .highlight-card:hover .highlight-image img { transform: scale(1.1); }

        .highlight-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            opacity: 0.5;
        }

        .highlight-badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--accent);
            color: var(--dark);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            font-size: 0.85rem;
            box-shadow: 0 5px 15px rgba(244, 164, 96, 0.4);
            z-index: 2;
        }

        .highlight-info { padding: 2rem; text-align: center; }

        .highlight-info h3 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }

        .highlight-info p { color: #777; line-height: 1.7; font-size: 1.05rem; }

        @media (max-width: 768px) {
            .feature-highlights { grid-template-columns: 1fr; }
            .products-grid { grid-template-columns: 1fr; gap: 2rem; }
            .product-image { width: 150px; height: 150px; }
            .product-name { font-size: 1.3rem; }
            .price-new { font-size: 1.5rem; }
            .wheat-left, .wheat-right { width: 180px; height: 220px; }
            .batik-corner-left { top: 70px; left: 10px; width: 100px; height: 100px; }
            .batik-corner-right { bottom: 10px; right: 10px; width: 100px; height: 100px; }
            .banana-leaf-1 { width: 150px; height: 90px; }
            .banana-leaf-2 { width: 140px; height: 85px; }
        }

        /* ========== CART MODAL ========== */
        .cart-modal {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 450px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 25px rgba(0,0,0,0.3);
            z-index: 20000;
            transition: right 0.4s ease;
            overflow-y: auto;
        }

        .cart-modal.active {
            display: block;
            right: 0;
        }

        .cart-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 19999;
            pointer-events: none;
        }

        .cart-overlay.active {
            display: block;
            pointer-events: auto;
        }

        .cart-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; }

        .close-cart {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .close-cart:hover { transform: rotate(90deg); }

        .cart-items { padding: 1.5rem; }

        .cart-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            animation: slideInRight 0.4s ease;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            overflow: hidden;
        }

        .cart-item-details { flex: 1; }
        .cart-item-name { font-weight: 600; margin-bottom: 0.5rem; }
        .cart-item-price { color: var(--secondary); font-weight: 600; }

        .quantity-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-top: 0.5rem;
        }

        .quantity-btn {
            background: var(--accent);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover { background: var(--secondary); transform: scale(1.1); }

        .remove-item {
            color: #FF4444;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .remove-item:hover { color: #CC0000; }

        .cart-summary {
            padding: 1.5rem;
            border-top: 2px solid var(--accent);
            background: var(--light-cream);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .summary-row.total {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            border-top: 2px solid var(--primary);
            padding-top: 1rem;
        }

        .checkout-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 1.25rem;
            border-radius: 30px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .checkout-btn:hover { background: var(--dark); transform: scale(1.02); }

        .empty-cart { text-align: center; padding: 3rem 1rem; color: #999; }
        .empty-cart-icon { font-size: 4rem; margin-bottom: 1rem; }

        /* ========== CHECKOUT FORM ========== */
        .checkout-form {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px var(--shadow);
        }

        .form-group { margin-bottom: 1.5rem; }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--accent);
            border-radius: 8px;
            font-family: 'Lora', serif;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* ========== ABOUT SECTION ========== */
        .about-container { padding: 4rem 1.5rem; max-width: 1200px; margin: 0 auto; }

        .about-header { text-align: center; margin-bottom: 4rem; }

        .about-script-title {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            color: var(--secondary);
            margin-bottom: -1rem;
            display: block;
        }

        .about-main-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            margin-bottom: 5rem;
        }

        .about-images-wrapper { position: relative; padding: 2rem; }

        .about-img-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 8px solid white;
            transition: all 0.5s ease;
        }

        .about-img-large { width: 100%; height: 500px; object-fit: cover; }

        .about-img-small {
            position: absolute;
            bottom: -2rem;
            right: -1rem;
            width: 60%;
            height: 300px;
            object-fit: cover;
            z-index: 2;
        }

        .about-img-frame:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            border-color: var(--light-cream);
        }

        .about-text-content { display: flex; flex-direction: column; gap: 1.5rem; }

        .about-description { font-size: 1.15rem; line-height: 1.8; color: var(--text); text-align: justify; }

        .vision-mission-section {
            background: var(--light-cream);
            padding: 4rem;
            border-radius: 30px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid rgba(139, 69, 19, 0.05);
        }

        .vm-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 4rem; }

        .vm-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent);
            display: inline-block;
        }

        .mission-list { list-style: none; padding: 0; }

        .mission-item { margin-bottom: 1rem; display: flex; gap: 1rem; align-items: flex-start; }

        .mission-number {
            background: var(--secondary);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
            margin-top: 0.2rem;
        }

        .mission-text { font-size: 1.05rem; color: var(--text); line-height: 1.6; }

        @media (max-width: 992px) {
            .about-grid, .vm-grid { grid-template-columns: 1fr; gap: 3rem; }
            .about-header .about-script-title { font-size: 2.8rem; }
            .about-header .about-main-title { font-size: 2.2rem; }
            .about-images-wrapper { order: 2; }
            .about-text-content { order: 1; }
            .about-img-large { height: 400px; }
            .about-img-small { height: 250px; }
            .vision-mission-section { padding: 2rem; }
        }

        /* ========== MODALS ========== */
        .message-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 30000;
            animation: popIn 0.5s ease;
        }

        .message-modal.active { display: block; }

        .message-modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 90vw;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .message-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text);
        }

        .message-close-btn:hover { color: var(--primary); }

        .message-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 29999;
            display: none;
            pointer-events: none;
        }

        .message-overlay.active {
            display: block;
            pointer-events: auto;
        }

        .form-group { margin-bottom: 1.5rem; }

        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary); }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus { outline: none; border-color: var(--primary); }

        .submit-msg-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-msg-btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .success-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #ffffff 0%, #fffaf0 100%);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1);
            text-align: center;
            z-index: 30000;
            animation: popIn 0.5s ease;
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .success-message.active { display: block; }

        @keyframes popIn {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }

        .success-icon {
            font-size: 3.5rem;
            color: #4CAF50;
            margin-bottom: 1.5rem;
            animation: scaleIn 0.6s ease 0.2s both;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            100% { transform: scale(1); }
        }

        /* ========== WHY CHOOSE US ========== */
        .why-choose-section { padding: 5rem 0; background-color: transparent; position: relative; }

        .why-choose-title {
            text-align: center;
            font-size: 2.5rem;
            color: #4A2C2A;
            margin-bottom: 4rem;
            position: relative;
        }

        .why-choose-title::after {
            content: '';
            display: block;
            width: 300px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .zigzag-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

        .zigzag-row {
            display: flex;
            align-items: center;
            gap: 3rem;
            margin-bottom: 4rem;
        }

        @media (max-width: 900px) {
            .zigzag-row { flex-direction: column; gap: 2rem; }
            .zigzag-row.reverse .zigzag-image { order: 1; }
            .zigzag-row.reverse .zigzag-card { order: 2; }
        }

        .zigzag-image { flex: 1; position: relative; z-index: 1; }

        .zigzag-image img {
            width: 100%;
            height: auto;
            max-height: 350px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 10px 25px rgba(44, 24, 16, 0.4);
            transform: rotate(-2deg);
            border: 4px solid white;
            transition: transform 0.3s ease;
        }
        
        .zigzag-row.reverse .zigzag-image img { transform: rotate(2deg); }
        .zigzag-image:hover img { transform: scale(1.02) rotate(0deg); z-index: 5; }

        .zigzag-card {
            flex: 1.2;
            background: linear-gradient(to bottom right, #fff9f0, #fff5e6);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.1);
            position: relative;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .zigzag-card::before {
            content: '🌿';
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 2rem;
            opacity: 0.1;
            transform: rotate(45deg);
            pointer-events: none;
        }
        
        .zigzag-card::after {
            content: '🌿';
            position: absolute;
            bottom: 10px;
            left: 15px;
            font-size: 2rem;
            opacity: 0.1;
            transform: rotate(-135deg);
            pointer-events: none;
        }

        .speech-pointer {
            position: absolute;
            width: 30px;
            height: 30px;
            background: #fff9f0;
            transform: rotate(45deg);
            z-index: 10;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            border-left: 1px solid rgba(212, 175, 55, 0.3);
        }

        .zigzag-row:not(.reverse) .speech-pointer {
            top: 40%;
            left: -16px;
            background: #fff9f0;
            box-shadow: -3px 3px 5px rgba(139, 69, 19, 0.05);
        }

        .zigzag-row.reverse .speech-pointer {
            top: 40%;
            right: -16px;
            border-bottom: none;
            border-left: none;
            border-top: 1px solid rgba(212, 175, 55, 0.3);
            border-right: 1px solid rgba(212, 175, 55, 0.3);
            box-shadow: 3px -3px 5px rgba(139, 69, 19, 0.05);
        }

        @media (max-width: 900px) { .speech-pointer { display: none; } }

        .card-header { display: flex; align-items: flex-start; gap: 1.2rem; margin-bottom: 1.2rem; }

        .title-group { flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }

        .icon-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #E6C265 0%, #D4AF37 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.4);
            flex-shrink: 0;
            border: 3px solid rgba(255,255,255,0.4);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #5D4037;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            text-align: left;
        }

        .title-underline {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #D4AF37, transparent);
            border-radius: 2px;
            margin-top: 0.25rem;
        }

        .card-desc {
            font-family: 'Lora', serif;
            font-size: 1rem;
            color: #6D4C41;
            line-height: 1.7;
            margin-left: calc(60px + 1.2rem);
        }
        
        @media (max-width: 500px) {
            .card-desc { margin-left: 0; }
            .card-header { flex-direction: column; align-items: center; text-align: center; }
            .title-underline { margin: 0.5rem auto; }
            .card-title { text-align: center; }
        }

        /* ========== ORDERS SECTION ========== */
        .orders-container { max-width: 1000px; margin: 0 auto; }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #D4AF37;
            transition: all 0.3s ease;
        }

        .order-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.12); transform: translateY(-2px); }

        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .order-number { display: flex; flex-direction: column; }
        .order-number-label { font-size: 0.85rem; color: #999; margin: 0; }
        .order-number-value { font-size: 1.1rem; font-weight: 700; color: #333; margin: 0.25rem 0 0 0; }

        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .order-detail-item { display: flex; flex-direction: column; }
        .order-detail-label { font-size: 0.85rem; color: #999; margin: 0; }
        .order-detail-value { font-size: 0.95rem; color: #333; margin: 0.25rem 0 0 0; }
        .order-products { margin-bottom: 1rem; }
        .order-products-label { font-size: 0.85rem; color: #999; margin: 0 0 0.5rem 0; }
        .order-products-value { font-size: 0.95rem; color: #333; margin: 0; }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .order-total { display: flex; flex-direction: column; }
        .order-total-label { font-size: 0.85rem; color: #999; margin: 0; }
        .order-total-value { font-size: 1.2rem; font-weight: 700; color: #D4AF37; margin: 0.25rem 0 0 0; }

        .order-action-btn {
            background: #8B4513;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .order-action-btn:hover {
            background: #6B3410;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
        }

        @media (max-width: 768px) {
            .order-header { flex-direction: column; gap: 1rem; }
            .order-details { grid-template-columns: 1fr; }
            .order-footer { flex-direction: column; gap: 1rem; align-items: flex-start; }
            .order-action-btn { width: 100%; }
        }

        /* ========== FOOTER ========== */
        footer {
            background: linear-gradient(135deg, #4A3428 0%, #6B4E3D 100%);
            color: #F5F5DC;
            padding: 40px 2rem 25px;
            margin-top: 3rem;
            position: relative;
            z-index: 5;
            overflow: hidden;
        }
        
        /* Wood/bread texture overlay */
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(139, 90, 60, 0.03) 3px, rgba(139, 90, 60, 0.03) 6px);
            opacity: 0.3;
            z-index: 1;
            pointer-events: none;
        }
        
        /* Subtle overlay for better text readability */
        footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
            pointer-events: none;
        }
        
        /* Left decorative image - wheat/bread ingredients */
        .footer-deco-left {
            position: absolute;
            left: -50px;
            bottom: 0;
            width: 250px;
            height: 100%;
            z-index: 2;
            pointer-events: none;
            opacity: 0.28;
            background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=600&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0.9) 0%, transparent 100%);
        }
        
        /* Right decorative image - artisan bread */
        .footer-deco-right {
            position: absolute;
            right: -50px;
            bottom: 0;
            width: 280px;
            height: 100%;
            z-index: 2;
            pointer-events: none;
            opacity: 0.3;
            background-image: url('https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=400&h=600&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            mask-image: linear-gradient(to left, rgba(0,0,0,0.9) 0%, transparent 100%);
            -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0.9) 0%, transparent 100%);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 55% 45%;
            gap: 2.5rem;
            align-items: start;
            position: relative;
            z-index: 3;
        }

        .footer-info h3 { 
            font-family: 'Playfair Display', serif; 
            font-size: 1.6rem; 
            margin-bottom: 0.5rem; 
            color: #E8748A;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        
        .footer-info > p {
            color: #F5F5DC;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            font-family: 'Lora', serif;
        }

        .footer-contact { 
            display: flex; 
            flex-direction: column; 
            gap: 1rem; 
        }

        .contact-item { 
            display: flex; 
            align-items: flex-start; 
            gap: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .contact-item:hover {
            transform: translateX(3px);
        }
        
        .contact-icon { 
            font-size: 1.1rem; 
            min-width: 26px; 
            text-align: center;
            color: #E8748A;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.5));
        }

        /* ========== PROMO MODAL ========== */
        .promo-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 20000;
            backdrop-filter: blur(5px);
            padding: 20px;
        }

        .promo-modal-overlay.active {
            display: flex;
            animation: fadeInModal 0.3s ease forwards;
        }

        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .promo-modal {
            background: #FFFDF9;
            width: 100%;
            max-width: 1000px;
            max-height: 90vh;
            border-radius: 24px;
            position: relative;
            padding: 40px 30px;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(212, 175, 55, 0.3);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .promo-modal-overlay.active .promo-modal {
            transform: translateY(0);
        }

        .promo-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .promo-modal-close:hover {
            background: #ea580c;
            color: white;
            transform: rotate(90deg);
        }

        .promo-modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: #4a2c0a;
            text-align: center;
            margin-bottom: 35px;
            font-weight: 700;
        }

        .promo-products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .promo-product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .promo-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .promo-product-img-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
        }

        .promo-quick-add {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 38px;
            height: 38px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .promo-quick-add:hover {
            background: #4a2c0a;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(74, 44, 10, 0.3);
        }

        .promo-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 2;
        }

        .promo-product-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .promo-product-subtitle {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8b5a2b;
            margin-bottom: 5px;
            font-weight: 600;
            display: block;
            min-height: 2rem; /* Increased for long subtitles */
        }

        .promo-product-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: #4a2c0a;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
            min-height: 3.25rem; /* Ensure consistent height for 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .promo-product-pricing {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 1.5rem;
        }

        .promo-price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
        }

        .promo-price-current {
            color: #ea580c;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .promo-stock-status {
            background: #d1fae5;
            color: #065f46;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 15px;
            display: inline-block;
            align-self: flex-start;
            min-height: 27px; /* Consistent height */
        }

        .promo-label-bottom {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 15px;
            min-height: 2.5rem; /* Ensure consistent height for 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .promo-buy-btn {
            background: #4a2c0a;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
            margin-top: auto;
        }

        .promo-buy-btn:hover {
            background: #2c1806;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(74, 44, 10, 0.3);
        }

        @media (max-width: 768px) {
            .promo-products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                overflow-x: hidden;
            }
            .promo-modal {
                padding: 30px 20px;
                width: 95%;
            }
            .promo-modal-title {
                font-size: 1.6rem;
            }
        }
        
        .contact-details { flex: 1; }
        
        .contact-label { 
            font-weight: 700; 
            color: #E8748A;
            font-size: 0.75rem; 
            margin-bottom: 0.2rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-family: 'Outfit', sans-serif;
        }
        
        .contact-value { 
            color: #F5F5DC;
            line-height: 1.5; 
            word-break: break-word;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
            font-size: 0.85rem;
        }
        
        .contact-value a { 
            transition: all 0.3s ease; 
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #E8748A;
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-value a:hover { 
            color: #FF9DB5;
            text-shadow: 0 0 10px rgba(232, 116, 138, 0.5);
            transform: scale(1.02);
        }

        .footer-map { 
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 
                0 8px 25px rgba(0, 0, 0, 0.4),
                0 0 0 2px rgba(232, 116, 138, 0.2);
            height: 260px;
            background: rgba(0, 0, 0, 0.3);
            position: relative;
            transition: all 0.3s ease;
        }
        
        .footer-map:hover {
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.5),
                0 0 0 2px rgba(232, 116, 138, 0.3);
            transform: translateY(-2px);
        }
        
        .footer-map iframe { 
            width: 100%; 
            height: 100%; 
            border: none;
            filter: brightness(0.95) contrast(1.05) saturate(0.9);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(232, 116, 138, 0.2);
            font-size: 0.8rem;
            color: rgba(245, 245, 220, 0.7);
            position: relative;
            z-index: 3;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
        }
        
        /* Wheat divider ornament */
        .footer-bottom::before {
            content: '🌾';
            display: block;
            font-size: 1.2rem;
            margin-bottom: 0.6rem;
            opacity: 0.5;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.5));
        }

        @media (max-width: 768px) {
            footer { 
                padding: 35px 1.5rem 25px;
            }
            .footer-content { 
                grid-template-columns: 1fr; 
                gap: 2rem; 
            }
            .footer-map { height: 240px; }
            .footer-deco-left,
            .footer-deco-right { 
                width: 180px;
                opacity: 0.2;
            }
            .footer-info h3 { font-size: 1.4rem; }
        }
        
        @media (max-width: 480px) {
            footer { 
                padding: 30px 1rem 20px;
            }
            .footer-map { height: 220px; }
            .footer-info h3 { font-size: 1.3rem; }
            .contact-icon { font-size: 1rem; }
            .footer-deco-left,
            .footer-deco-right { 
                width: 140px;
                opacity: 0.18;
            }
            .footer-deco-left {
                left: -20px;
            }
            .footer-deco-right {
                right: -20px;
            }
        }

        /* ========== MOBILE NAV ========== */
        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.25rem;
            background: none;
            border: none;
            color: var(--cream);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            pointer-events: auto !important;
        }

        .nav-menu { display: none; }

        @media (max-width: 768px) {
            body { padding-top: 80px; }
            header { padding: 0.6rem 4%; }
            .menu-toggle { display: block; }
            nav { display: none; }
            .nav-menu {
                display: none;
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.75rem 1rem;
                background: var(--light-cream);
                position: fixed;
                top: 72px;
                right: 1rem;
                z-index: 9998;
                border-radius: 8px;
                box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            }
            .nav-menu a { color: var(--text); padding: 0.5rem 0.75rem; text-decoration: none; }
            .nav-menu.open { display: flex; }

            .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
            .status-pending { background: #f5f5f5; color: #616161; }
            .status-pending_admin { background: #eeeeee; color: #424242; }
            .status-shipping_set { background: #e8eaf6; color: #3f51b5; }
            .status-pending_payment { background: #fffde7; color: #fbc02d; }
            .status-payment_confirmed { background: #e8f5e9; color: #4caf50; }
            .status-processing { background: #fff3e0; color: #ff9800; }
            .status-scheduled { background: #e3f2fd; color: #1976d2; }
            .status-out_for_delivery { background: #f3e5f5; color: #9c27b0; }
            .status-shipped { background: #e0f2f1; color: #009688; }
            .status-delivered { background: #c8e6c9; color: #2e7d32; }
            .status-cancelled { background: #ffebee; color: #d32f2f; }

            .hero { padding: 4rem 1.5rem; min-height: 40vh; text-align: left; }
            .nav-links { display: none; }
            .products-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .cart-modal { max-width: 100%; }
            .section-title { font-size: 2rem; }
            .container { padding: 2rem 1rem; }

            .product-card { border: 2.5px solid #D4AF37; border-radius: 15px; }
            .product-card::before { top: -10px; left: -10px; font-size: 1.5rem; }
            .product-card::after { bottom: -10px; right: -10px; font-size: 1.5rem; }
            .product-image-wrapper { padding-top: 100% !important; min-height: 400px !important; }
            .product-image { width: 100% !important; height: 100% !important; }
            .product-image img { width: 100% !important; height: 100% !important; object-fit: cover !important; }
            .product-image-wrapper::before { top: -10px; right: -10px; font-size: 1.5rem; }
            .product-info::after { bottom: -10px; left: -10px; font-size: 1.5rem; }
            .product-info { padding: 1.2rem; }
            .product-name { font-size: 1.1rem; }
            .product-description { font-size: 0.85rem; }
            .price-new { font-size: 1.1rem; }
            .cta-button { padding: 0.8rem; font-size: 0.9rem; }
        }

        /* ========== SECTION DIVIDER ========== */
        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            margin: 3rem 0;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Ornamen Gandum Kiri Bawah -->
    <svg class="wheat-left" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
        <g fill="#C9A86A">
            <path d="M100 300 Q95 200 90 100 Q88 50 85 0" stroke="#A67C52" stroke-width="3" fill="none"/>
            <ellipse cx="75" cy="50" rx="8" ry="15" transform="rotate(-30 75 50)"/>
            <ellipse cx="70" cy="80" rx="9" ry="16" transform="rotate(-35 70 80)"/>
            <ellipse cx="68" cy="110" rx="8" ry="15" transform="rotate(-30 68 110)"/>
            <ellipse cx="65" cy="140" rx="9" ry="16" transform="rotate(-35 65 140)"/>
            <ellipse cx="63" cy="170" rx="8" ry="15" transform="rotate(-30 63 170)"/>
            <ellipse cx="105" cy="60" rx="8" ry="15" transform="rotate(30 105 60)"/>
            <ellipse cx="110" cy="90" rx="9" ry="16" transform="rotate(35 110 90)"/>
            <ellipse cx="112" cy="120" rx="8" ry="15" transform="rotate(30 112 120)"/>
            <ellipse cx="115" cy="150" rx="9" ry="16" transform="rotate(35 115 150)"/>
            <ellipse cx="117" cy="180" rx="8" ry="15" transform="rotate(30 117 180)"/>
        </g>
        <g fill="#C9A86A" opacity="0.7">
            <path d="M130 280 Q128 190 125 90 Q123 45 120 10" stroke="#A67C52" stroke-width="2.5" fill="none"/>
            <ellipse cx="112" cy="40" rx="7" ry="13" transform="rotate(-25 112 40)"/>
            <ellipse cx="108" cy="70" rx="7" ry="14" transform="rotate(-30 108 70)"/>
            <ellipse cx="138" cy="50" rx="7" ry="13" transform="rotate(25 138 50)"/>
            <ellipse cx="142" cy="80" rx="7" ry="14" transform="rotate(30 142 80)"/>
        </g>
    </svg>

    <!-- Ornamen Gandum Kanan Atas -->
    <svg class="wheat-right" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
        <g fill="#C9A86A">
            <path d="M100 300 Q95 200 90 100 Q88 50 85 0" stroke="#A67C52" stroke-width="3" fill="none"/>
            <ellipse cx="75" cy="50" rx="8" ry="15" transform="rotate(-30 75 50)"/>
            <ellipse cx="70" cy="80" rx="9" ry="16" transform="rotate(-35 70 80)"/>
            <ellipse cx="68" cy="110" rx="8" ry="15" transform="rotate(-30 68 110)"/>
            <ellipse cx="105" cy="60" rx="8" ry="15" transform="rotate(30 105 60)"/>
            <ellipse cx="110" cy="90" rx="9" ry="16" transform="rotate(35 110 90)"/>
        </g>
    </svg>

    <!-- Motif Batik Parang Kiri Atas -->
    <svg class="batik-corner-left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <pattern id="parang" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M0 20 Q10 10 20 20 T40 20" stroke="#8B5A3C" stroke-width="2" fill="none"/>
            <path d="M0 30 Q10 20 20 30 T40 30" stroke="#8B5A3C" stroke-width="1.5" fill="none"/>
            <circle cx="10" cy="15" r="2" fill="#8B5A3C"/>
            <circle cx="30" cy="15" r="2" fill="#8B5A3C"/>
        </pattern>
        <rect width="100" height="100" fill="url(#parang)"/>
    </svg>

    <!-- Motif Batik Parang Kanan Bawah -->
    <svg class="batik-corner-right" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <pattern id="parang2" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M0 20 Q10 10 20 20 T40 20" stroke="#8B5A3C" stroke-width="2" fill="none"/>
            <path d="M0 30 Q10 20 20 30 T40 30" stroke="#8B5A3C" stroke-width="1.5" fill="none"/>
            <circle cx="10" cy="15" r="2" fill="#8B5A3C"/>
            <circle cx="30" cy="15" r="2" fill="#8B5A3C"/>
        </pattern>
        <rect width="100" height="100" fill="url(#parang2)"/>
    </svg>

    <!-- Siluet Daun Pisang 1 -->
    <svg class="banana-leaf banana-leaf-1" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 60 Q50 20 100 40 Q150 60 190 50 Q150 80 100 70 Q50 60 10 80 Z" fill="#8B5A3C" opacity="0.3"/>
        <path d="M20 60 L100 45 M40 65 L100 50 M60 68 L100 55" stroke="#8B5A3C" stroke-width="1" opacity="0.2"/>
    </svg>

    <!-- Siluet Daun Pisang 2 -->
    <svg class="banana-leaf banana-leaf-2" viewBox="0 0 180 110" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 55 Q45 15 90 35 Q135 55 170 45 Q135 75 90 65 Q45 55 10 75 Z" fill="#8B5A3C" opacity="0.3"/>
        <path d="M20 55 L90 42 M35 60 L90 47 M50 63 L90 52" stroke="#8B5A3C" stroke-width="1" opacity="0.2"/>
    </svg>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="/images/budess.jpg" alt="Dapoer Budess" class="logo-img">
        </div>

        <ul class="nav-links">
            <li><a href="javascript:void(0)" class="active" onclick="showSection('home')">Beranda</a></li>
            <li><a href="javascript:void(0)" onclick="showSection('products')">Menu</a></li>
            <li><a href="javascript:void(0)" onclick="showSection('bestseller')">Best Seller</a></li>
            <li><a href="javascript:void(0)" onclick="showSection('profile')">Profile</a></li>
        </ul>

        <div class="header-actions">
            <button class="cart-btn" onclick="toggleCart()">
                🛒 Keranjang
                <span class="cart-count" id="cartCount">0</span>
            </button>
            <button class="message-btn" onclick="openMessageModal()" style="position: relative;">
                💬 Pesan
                <span class="cart-count" id="msgBadge" style="display: none;">!</span>
                <span id="msgPulse" style="display: none; position: absolute; top: -5px; right: -5px; width: 12px; height: 12px; background: #ff4444; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 rgba(255, 68, 68, 0.4); animation: pulse-red 2s infinite;"></span>
            </button>
            @auth
             @endif
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>

        <nav class="nav-menu" id="navMenu">
            <a href="javascript:void(0)" onclick="showSection('home')">Beranda</a>
            <a href="javascript:void(0)" onclick="showSection('products')">Menu</a>
            <a href="javascript:void(0)" onclick="showSection('bestseller')">Best Seller</a>
            <a href="javascript:void(0)" onclick="showSection('profile')">Profile</a>
        </nav>
    </header>

    <!-- HERO SLIDER SECTION -->
    <div class="hero-slider" id="heroSlider">
        <div class="slide slide-1 active" style="background-image: url('/images/hero/slide1.jpg');">
            <div class="slide-content">
                <h1>Nikmatnya <br>Roti Manis Premium</h1>
                <p>Rasakan kelembutan roti yang dibuat dengan sepenuh hati. Topping keju dan coklat berlimpah yang lumer di mulut.</p>
                <a href="javascript:void(0)" onclick="showSection('products')" class="hero-btn">Pesan Sekarang</a>
            </div>
        </div>

        <div class="slide slide-2" style="background-image: url('/images/hero/slide2.jpg');">
            <div class="slide-content">
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic;">Dapoer Budess</h1>
                <p>Nikmati kelembutan roti artisan kelas dunia yang dibuat dengan resep rahasia. Setiap potongnya menjanjikan kelezatan yang tak terlupakan dan aroma yang menggugah selera.</p>
                <a href="javascript:void(0)" onclick="showSection('products')" class="hero-btn">Eksplorasi Menu</a>
            </div>
        </div>

        <div class="slide slide-3" style="background-image: url('/images/hero/slide3.jpg');">
            <div class="slide-content">
                <h1>Dibuat Segar <br>Setiap Hari</h1>
                <p>Kami menjamin kesegaran setiap potong roti. Resep autentik dan proses pemanggangan sempurna.</p>
                <a href="javascript:void(0)" onclick="showSection('bestseller')" class="hero-btn">Cek Best Seller</a>
            </div>
        </div>

        <button class="slider-nav prev-slide" onclick="moveSlide(-1)">&#10094;</button>
        <button class="slider-nav next-slide" onclick="moveSlide(1)">&#10095;</button>

        <div class="slider-dots">
            <div class="dot active" onclick="currentSlide(0)"></div>
            <div class="dot" onclick="currentSlide(1)"></div>
            <div class="dot" onclick="currentSlide(2)"></div>
        </div>
    </div>

    <!-- PROMO Section -->
    @if(isset($promo) && $promo->is_active)
    <section class="promo-section" id="promoBanner">
        <div class="promo-card">
            <div class="promo-content">
                <!-- LEFT COLUMN -->
                <div class="promo-left">
                    <!-- Badge Row -->
                    <div class="promo-badges">
                        @if($promo->badge_text)
                            <span class="promo-badge">{{ $promo->badge_text }}</span>
                        @endif
                        @if($promo->discount_badge_text)
                            <span class="discount-badge">{{ $promo->discount_badge_text }}</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <div class="promo-title-section">
                        <div class="wheat-ornament-left">🌾</div>
                        <h2 class="promo-title">
                            {!! nl2br(e($promo->title)) !!}
                        </h2>
                    </div>

                    <!-- Description -->
                    <p class="promo-subtitle">
                        {{ $promo->subtitle }}
                    </p>

                    <!-- Price -->
                    <div class="promo-pricing">
                        <span class="price-original">Rp {{ number_format($promo->price_original, 0, ',', '.') }}</span>
                        <span class="price-discount">Rp {{ number_format($promo->price_promo, 0, ',', '.') }}</span>
                        <span class="price-save">🏷 Hemat Rp {{ number_format($promo->price_original - $promo->price_promo, 0, ',', '.') }}</span>
                    </div>

                    <!-- Countdown Timer -->
                    @if($promo->end_time && $promo->end_time->isFuture())
                    <div id="promo-countdown" class="promo-countdown" data-endtime="{{ $promo->end_time->format('Y-m-d H:i:s') }}">
                        <div class="timer-item">
                            <span id="timer-days">00</span>
                            <small>Hari</small>
                        </div>
                        <div class="timer-item">
                            <span id="timer-hours">00</span>
                            <small>Jam</small>
                        </div>
                        <div class="timer-item">
                            <span id="timer-mins">00</span>
                            <small>Menit</small>
                        </div>
                        <div class="timer-item">
                            <span id="timer-secs">00</span>
                            <small>Detik</small>
                        </div>
                    </div>
                    @endif

                    <!-- Features -->
                    <div class="promo-features">
                        <div class="feature-item">
                            <div class="feature-icon">🌾</div>
                            <div class="feature-title">Bahan Berkualitas</div>
                            <div class="feature-subtitle">Pilihan terbaik setiap hari</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🔥</div>
                            <div class="feature-title">Freshly Baked</div>
                            <div class="feature-subtitle">Dibuat setiap hari</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">❤️</div>
                            <div class="feature-title">1000+ Pelanggan</div>
                            <div class="feature-subtitle">Puas dengan rasa kami</div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="promo-cta-container">
                        <button class="promo-cta" onclick="openPromoModal()">
                            🛒 PESAN SEKARANG – TERBATAS!
                        </button>
                        <p class="promo-note">⏰ Promo hanya hari ini!</p>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="promo-right">
                    <!-- Wheat Decoration -->
                    <div class="wheat-decoration">🌾</div>

                    <!-- Best Seller Badge -->
                    <div class="best-seller-badge">
                        <div class="crown">👑</div>
                        <div class="text">BEST<br>SELLER</div>
                        <div class="stars">★★★</div>
                    </div>

                    <!-- Product Images -->
                    <div class="promo-images">
                        <!-- GAMBAR 1 - UTAMA (BESAR, ATAS KANAN) -->
                        <div class="promo-image-item promo-image-main">
                            <img src="{{ $promo->image_main ? '/' . $promo->image_main : '/images/besar1.jpg' }}" alt="{{ $promo->title }}" onerror="this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&q=80'">
                        </div>

                        <!-- GAMBAR 2 - KECIL (BAWAH KIRI) -->
                        <div class="promo-image-item promo-image-second">
                            <img src="{{ $promo->image_second ? '/' . $promo->image_second : '/images/besar 2.jpg' }}" alt="{{ $promo->title }}" onerror="this.src='https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&q=80'">
                        </div>

                        <!-- GAMBAR 3 - SEDANG (BAWAH KANAN) -->
                        <div class="promo-image-item promo-image-third">
                            <img src="{{ $promo->image_third ? '/' . $promo->image_third : '/images/besar 3.jpg' }}" alt="{{ $promo->title }}" onerror="this.src='https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400&q=80'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- PROMO MODAL HTML -->
    <div id="promoModalOverlay" class="promo-modal-overlay" onclick="closePromoModal(event)">
        <div class="promo-modal" onclick="event.stopPropagation()">
            <div class="promo-modal-close" onclick="closePromoModal(event)">✕</div>
            <h2 class="promo-modal-title">🔥 Pilih Roti Promo Hari Ini</h2>
            <div style="text-align: center; margin-bottom: 25px; margin-top: -20px;">
                <button onclick="toggleCart()" style="background: #fffcf0; border: 1px solid #D4AF37; padding: 8px 15px; border-radius: 20px; color: #4a2c0a; font-weight: 600; cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    🛒 Lihat Keranjang (<span id="promoModalCartCount">0</span>)
                </button>
            </div>
            
            <div class="promo-products-grid">
                @if(isset($modalProducts) && $modalProducts->count() > 0)
                    @foreach($modalProducts as $p)
                    <div class="promo-product-card">
                        <div class="promo-product-img-wrapper">
                            @if($p->badge)
                                <span class="promo-product-badge">{{ $p->badge }}</span>
                            @endif
                            <div class="promo-quick-add" onclick="directBuyPromo('{{ $p->name }}', {{ (int)$p->price_promo }})" title="Tambah ke Keranjang">🛒</div>
                            <img src="{{ $p->image ? '/' . $p->image : 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&h=500&fit=crop' }}" class="promo-product-img" alt="{{ $p->name }}">
                        </div>
                        <div class="promo-product-body">
                            @if($p->subtitle)
                                <span class="promo-product-subtitle">{{ $p->subtitle }}</span>
                            @endif
                            <h3 class="promo-product-name">{{ $p->name }}</h3>
                            <div class="promo-product-pricing">
                                @if($p->price_original && $p->price_original > $p->price_promo)
                                    <span class="promo-price-original">Rp {{ number_format($p->price_original, 0, ',', '.') }}</span>
                                @endif
                                <span class="promo-price-current">Rp {{ number_format($p->price_promo, 0, ',', '.') }}</span>
                            </div>
                            @if($p->stock_label)
                                <span class="promo-stock-status">{{ $p->stock_label }}</span>
                            @endif
                            @if($p->bottom_label)
                                <p class="promo-label-bottom">{{ $p->bottom_label }}</p>
                            @endif
                            <button class="promo-buy-btn" onclick="directBuyPromo('{{ $p->name }}', {{ (int)$p->price_promo }})">🛒 Beli</button>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Home Section -->
        <section id="home" class="section active">
            <div class="why-choose-section">
                <h2 class="section-title why-choose-title">Mengapa Memilih Roti Kami?</h2>
                <div class="zigzag-container">
                    <div class="zigzag-row" data-aos="fade-up">
                        <div class="zigzag-image">
                            <img src="/images/panggang.jpg" alt="Roti Fresh dari Oven">
                        </div>
                        <div class="zigzag-card">
                            <div class="speech-pointer"></div>
                            <div class="card-header">
                                <div class="icon-circle">✓</div>
                                <div class="title-group">
                                    <h3 class="card-title">Bahan Berkualitas <br>& Proses Higienis</h3>
                                    <div class="title-underline"></div>
                                </div>
                            </div>
                            <p class="card-desc">Kami menggunakan bahan-bahan pilihan berkualitas tinggi dan dipanggang segar setiap hari dengan standar kebersihan yang ketat untuk menjaga rasa dan kelembutannya.</p>
                        </div>
                    </div>
                    <div class="zigzag-row reverse" data-aos="fade-up">
                        <div class="zigzag-card">
                            <div class="speech-pointer"></div>
                            <div class="card-header">
                                <div class="icon-circle">❤️</div>
                                <div class="title-group">
                                    <h3 class="card-title">Lezat, Segar <br>& Terjangkau</h3>
                                    <div class="title-underline"></div>
                                </div>
                            </div>
                            <p class="card-desc">Roti kami menawarkan cita rasa lezat dan segar dengan harga yang ramah di kantong. Cocok untuk sarapan, camilan, dan momen spesial Anda bersama keluarga.</p>
                        </div>
                        <div class="zigzag-image">
                            <img src="/images/keluarga.jpg" alt="Momen Menikmati Roti Bersama">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <h2 class="section-title">🔥 Roti Paling Laris</h2>
            <div class="products-grid" id="bestsellerHome"></div>

            <div class="section-divider"></div>

            <h2 class="section-title">💬 Ulasan Pelanggan</h2>
            <div class="reviews-grid" id="mainReviewsGrid">
                @if(isset($reviews) && count($reviews) > 0)
                    @foreach($reviews as $review)
                        <div class="review-card" style="animation: fadeInUp 0.5s ease;">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        {{ substr($review->display_name ?? $review->order->customer_name, 0, 1) }}
                                    </div>
                                    <div class="reviewer-details">
                                        <h4>{{ $review->display_name ?? $review->order->customer_name }}</h4>
                                        <div class="reviewer-time">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="stars">
                                    @for($i = 0; $i < $review->rating; $i++)★@endfor
                                    <span style="color: #ddd;">@for($i = 0; $i < (5 - $review->rating); $i++)★@endfor</span>
                                </div>
                            </div>
                            <p class="review-text">"{{ $review->comment ?? '' }}"</p>
                            @if($review->media_urls)
                                <div style="display: flex; gap: 8px; margin-top: 15px; flex-wrap: wrap;">
                                    @foreach($review->media_urls as $url)
                                        <div style="width: 60px; height: 60px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" onclick="window.open('/' + '{{ $url }}', '_blank')">
                                            <img src="/{{ $url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; width: 100%; grid-column: 1/-1; padding: 2rem; color: #666;">
                        Belum ada ulasan yang ditampilkan saat ini.
                    </div>
                @endif
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="section">
            <h2 class="section-title">Katalog Roti Lengkap</h2>
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <select id="sortFilter" onchange="applySortFilter()" style="padding: 0.8rem 1rem; border: 2px solid #D4AF37; border-radius: 12px; font-size: 0.95rem; background: white; cursor: pointer; min-width: 250px;">
                    <option value="newest">📌 Terbaru Ditambahkan</option>
                    <option value="bestseller">🔥 Paling Laris</option>
                    <option value="price-low">💰 Harga Terendah</option>
                    <option value="price-high">💎 Harga Tertinggi</option>
                    <option value="name-asc">A-Z Nama Produk</option>
                    <option value="name-desc">Z-A Nama Produk</option>
                </select>
            </div>
            <div class="products-grid" id="productsGrid"></div>
        </section>

        <!-- Best Seller Section -->
        <section id="bestseller" class="section">
            <h2 class="section-title">Roti Paling Laris</h2>
            <div class="products-grid" id="bestsellerGrid"></div>
        </section>

        <!-- Checkout Section -->
        <section id="checkout" class="section">
            <h2 class="section-title">Checkout</h2>
            <div class="checkout-form">
                <h3 style="margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">Informasi Pengiriman</h3>
                <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="customer_name" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon (WA) *</label>
                            <input type="tel" name="customer_phone" required placeholder="08xx xxxx xxxx">
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="margin-bottom: 1rem; display: block;">Metode Pengambilan *</label>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label style="flex: 1; padding: 1rem; border: 2px solid var(--primary); border-radius: 8px; cursor: pointer; transition: all 0.3s; background: #fffcf5;" id="shipping-delivery">
                                <input type="radio" name="shipping_method" value="delivery" checked onclick="toggleAddressFields('delivery')">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">🚚 Dianter Penjual</span>
                                <span style="font-size: 0.85rem; color: #666;">Kami antar pesanan ke lokasi Anda</span>
                            </label>
                            <label style="flex: 1; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" id="shipping-pickup">
                                <input type="radio" name="shipping_method" value="pickup" onclick="toggleAddressFields('pickup')">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">🏠 Mengambil Langsung ke Toko</span>
                                <span style="font-size: 0.85rem; color: #666;">Ambil pesanan Anda di gerai kami</span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="addressSection">
                        <div class="form-group">
                            <label>Kota *</label>
                            <input type="text" name="city" required placeholder="Contoh: Kota Bogor">
                        </div>
                        <div class="form-group">
                            <label>Nama Jalan *</label>
                            <input type="text" name="street" required placeholder="Contoh: Jl. Mawar Indah">
                        </div>
                        <div class="form-group">
                            <label>Detail Rumah / Patokan (Opsional)</label>
                            <input type="text" name="house_details" placeholder="Contoh: Rumah pagar hitam, ada pohon mangga">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nomor Rumah *</label>
                                <input type="text" name="house_number" required placeholder="No. 12A">
                            </div>
                            <div class="form-group">
                                <label>RT / RW *</label>
                                <input type="text" name="rt_rw" required placeholder="005/002">
                            </div>
                        </div>
                        <div style="background: #fff8e1; border-left: 4px solid #ffca28; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px;" id="shippingInfoBox">
                            <p style="margin: 0; font-size: 0.9rem; color: #856404; font-weight: 600;">🚚 Informasi Pengiriman:</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #856404;">Ongkos kirim akan ditentukan oleh Admin setelah pesanan masuk. Kami akan menghubungi Anda melalui chat/WA.</p>
                        </div>
                    </div>

                    <div id="pickupSection" style="display: none;">
                        <div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px;">
                            <p style="margin: 0 0 1rem 0; font-size: 1rem; color: #2e7d32; font-weight: 700;">📍 Lokasi Pengambilan</p>
                            <div style="background: white; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                                <p style="margin: 0.5rem 0; color: #333; font-size: 0.95rem;"><strong>Dapoer Budess Bakery</strong></p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Jl. Wates Dalam No.61, RT.02/RW.05</p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Pasirmulya, Kec. Bogor Bar., Kota Bogor</p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Jawa Barat 16118</p>
                            </div>
                            <div style="background: white; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                                <p style="margin: 0.5rem 0; color: #333; font-size: 0.95rem;"><strong>⏰ Jam Operasional:</strong></p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Senin - Minggu: 07:00 - 13:00 WIB</p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Libur pada hari raya nasional</p>
                            </div>
                            <div style="background: white; padding: 1rem; border-radius: 6px;">
                                <p style="margin: 0.5rem 0; color: #333; font-size: 0.95rem;"><strong>📞 Hubungi Kami:</strong></p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">WhatsApp: +62 821-1997-9538</p>
                                <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">Email: destidwinursanti.d3@gmail.com</p>
                            </div>
                            <p style="margin: 1rem 0 0 0; font-size: 0.85rem; color: #558b2f; font-weight: 600;">✓ Pesanan siap diambil sesuai waktu yang disepakati dengan Admin</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan Pesanan (Opsional)</label>
                        <textarea rows="2" name="notes" placeholder="Contoh: Pencet Bel/Hubungi No. Telp/Catatan Lainnya"></textarea>
                    </div>

                    <div class="form-group">
                        <label style="margin-bottom: 1rem; display: block;">Metode Pembayaran *</label>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <label style="flex: 1; padding: 1rem; border: 2px solid var(--primary); border-radius: 8px; cursor: pointer; transition: all 0.3s; background: #fffcf5;">
                                <input type="radio" name="payment_method" value="QRIS" checked style="cursor: pointer;">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">📱 QRIS (E-Wallet/Banking)</span>
                                <span style="font-size: 0.85rem; color: #666;">Transfer via QRIS, E-Wallet, atau Banking</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="checkout-btn">Proses Pesanan</button>
                </form>
            </div>
        </section>

        <!-- Pesanan Saya Section -->
        <section id="orders" class="section">
            <h2 class="section-title">📦 Pesanan Saya</h2>
            <div class="orders-container">
                <div style="text-align: center; padding: 3rem 1rem; color: #999;">
                    <p style="font-size: 1.1rem; margin-bottom: 0.5rem;">Belum ada pesanan</p>
                    <p style="font-size: 0.9rem;">Masukkan nomor WhatsApp Anda di bagian Chat untuk melihat status pesanan</p>
                </div>
            </div>
        </section>

        <!-- Profile Section -->
        <section id="profile" class="section">
            <div class="about-container">
                <div class="about-header" data-aos="fade-up">
                    <span class="about-script-title">Profile</span>
                    <h2 class="about-main-title">Dapoer Budess</h2>
                </div>

                <div class="about-grid">
                    <div class="about-images-wrapper" data-aos="fade-right">
                        <div class="about-img-frame about-img-large">
                            <img src="/images/rocil.jpg" alt="Proses Pembuatan Roti" class="about-img-large">
                        </div>
                    </div>
                    <div class="about-text-content" data-aos="fade-left">
                        <div class="about-description">
                            <p>Berdiri sejak tahun 2014, Dapoer Budess hadir sebagai roti rumahan dengan cita rasa khas yang selalu dirindukan. Kami menghadirkan berbagai varian favorit seperti coklat, keju, coklat keju, sosis keju, pisang coklat, pisang keju, hingga pisang coklat keju — semuanya dibuat dengan bahan pilihan dan proses yang penuh ketelatenan.</p>
                            <p style="margin-top: 1rem;">Keunikan kami bukan hanya pada rasa, tetapi pada identitas yang melekat di setiap gigitan. Tanpa perlu melihat siapa yang menjualnya, pelanggan sudah tahu — ini pasti roti Dapoer Budess.</p>
                            <p style="margin-top: 1rem;">Dengan sentuhan hangat khas masakan rumah, Dapoer Budess ingin menjadi bagian dari momen indah Anda, baik sebagai teman sarapan pagi, camilan sore yang manis, maupun hantaran berharga untuk orang-orang tersayang.</p>
                        </div>
                    </div>
                </div>

                <div class="vision-mission-section" data-aos="fade-up">
                    <div class="vm-grid">
                        <div class="vision-box">
                            <h3 class="vm-title">Visi Kami</h3>
                            <p class="about-description">Menjadi brand roti rumahan terpercaya yang dikenal karena cita rasa khas, kualitas terbaik, dan konsistensi rasa yang selalu membuat pelanggan kembali.</p>
                        </div>
                        <div class="mission-box">
                            <h3 class="vm-title">Misi Kami</h3>
                            <ul class="mission-list">
                                <li class="mission-item"><div class="mission-number">1</div><div class="mission-text">Menghadirkan roti berkualitas dengan bahan pilihan terbaik.</div></li>
                                <li class="mission-item"><div class="mission-number">2</div><div class="mission-text">Menjaga konsistensi rasa dan tekstur di setiap produksi.</div></li>
                                <li class="mission-item"><div class="mission-number">3</div><div class="mission-text">Memberikan harga yang terjangkau tanpa mengurangi kualitas.</div></li>
                                <li class="mission-item"><div class="mission-number">4</div><div class="mission-text">Mengembangkan inovasi varian rasa sesuai selera pelanggan.</div></li>
                                <li class="mission-item"><div class="mission-number">5</div><div class="mission-text">Menjadikan setiap produk sebagai simbol kehangatan dan kepuasan pelanggan.</div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Cart Overlay & Modal -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
    <div class="cart-modal" id="cartModal">
        <div class="cart-header">
            <h2 class="cart-title">Keranjang Belanja</h2>
            <button class="close-cart" onclick="toggleCart()">×</button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <p>Keranjang Anda masih kosong</p>
            </div>
        </div>
        <div class="cart-summary" id="cartSummary" style="display: none;">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span id="subtotal">Rp 0</span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span id="total">Rp 0</span>
            </div>
            <button class="checkout-btn" onclick="goToCheckout()">Lanjut ke Checkout</button>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="message-modal" id="messageModal">
        <div class="message-modal-content">
            <button class="message-close-btn" onclick="closeMessageModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1rem;">💬 Chat dengan Admin</h2>
            
            <div id="messageLoginSection" style="margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #666;">Masukkan nomor Whatsapp Anda untuk melihat pesan & status pesanan:</p>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <input type="tel" id="searchPhone" placeholder="08123456789" style="flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px;">
                    <button type="button" id="btnSearchChat" onclick="loadChatThread()" style="padding: 0.75rem 1rem; background: var(--accent); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; min-width: 80px;">Cari</button>
                </div>
                <div id="chatLoading" style="display: none; color: var(--primary); font-size: 0.9rem; margin-bottom: 0.5rem;">⏳ Menghubungkan...</div>
                <div id="chatSearchInfo" style="display: none; padding: 0.5rem; background: #fff3cd; color: #856404; font-size: 0.85rem; border-radius: 4px; margin-bottom: 1rem;"></div>
            </div>

            <div id="orderHistorySection" style="display: none; margin-bottom: 1rem; max-height: 200px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 1rem; background: #fffcf5;">
                <h3 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.5rem; position: sticky; top: 0; background: #fffcf5; padding-bottom: 0.5rem; border-bottom: 1px solid #eee;">📦 Riwayat Pesanan</h3>
                <div id="orderHistoryList"></div>
            </div>

            <div id="chatThreadSection" style="display: none; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9; overflow: hidden; flex-direction: column; height: 400px;">
                <div style="background: #fff; padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.9rem; color: #666;">Nomor: <span id="currentChatPhone" style="font-weight: bold;"></span></span>
                        <span id="autoRefreshIndicator" style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; color: #4CAF50; background: #e8f5e9; padding: 2px 8px; border-radius: 10px;">
                            <span style="animation: pulse 2s ease-in-out infinite;">●</span> Auto-refresh
                        </span>
                    </div>
                    <button type="button" onclick="logoutChat()" style="background: none; border: none; color: #d32f2f; font-size: 0.8rem; cursor: pointer; text-decoration: underline;">Ganti Nomor</button>
                </div>
                <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem; background: white;"></div>
                <div style="border-top: 1px solid #eee; padding: 1rem; background: #f9f9f9;">
                    <form id="chatMessageForm" onsubmit="sendChatMessage(event)" style="display: flex; gap: 0.5rem;">
                        <textarea id="chatInput" placeholder="Ketik pesan Anda..." required style="flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; resize: none; height: 60px;"></textarea>
                        <button type="submit" style="padding: 0.75rem 1rem; background: var(--primary); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; align-self: flex-end;">Kirim</button>
                    </form>
                </div>
            </div>

            <div id="firstMessageSection" style="display: none;">
                <form id="messageForm" onsubmit="sendMessage(event)">
                    <div class="form-group">
                        <label for="senderName">Nama Anda</label>
                        <input type="text" id="senderName" name="senderName" required placeholder="Masukkan nama Anda">
                    </div>
                    <div class="form-group">
                        <label for="senderPhone">Nomor Whatsapp/Telepon</label>
                        <input type="tel" id="senderPhone" name="senderPhone" required placeholder="Contoh: 08123456789">
                    </div>
                    <div class="form-group">
                        <label for="senderMessage">Pesan</label>
                        <textarea id="senderMessage" name="senderMessage" required placeholder="Tulis pesan Anda di sini..." rows="5"></textarea>
                    </div>
                    <button type="submit" class="submit-msg-btn">Kirim Pesan Pertama</button>
                </form>
            </div>
        </div>
    </div>
    <div class="message-overlay" id="messageOverlay" onclick="closeMessageModal()"></div>

    <!-- Success Message -->
    <div class="success-message" id="successMessage">
        <div class="success-icon">✓</div>
        <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 0.3rem; font-size: 2rem; font-weight: 700;">Pesanan Berhasil!</h2>
        <p style="color: #999; font-size: 0.95rem; margin-bottom: 2rem;">Terima kasih telah berbelanja di Dapoer Budess</p>
        <div id="orderInfo" style="margin: 2rem 0;">
            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #e9ecef;">
                <p style="margin-bottom: 0.5rem; color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Nomor Pesanan</p>
                <p id="orderNumber" style="font-size: 1.4em; font-weight: 700; color: var(--primary); margin: 0; font-family: 'Courier New', monospace;"></p>
            </div>
            <div style="background: linear-gradient(135deg, #fffbf0 0%, #fff9f5 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #ffe8cc;">
                <p style="margin-bottom: 0.8rem; color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Status Pesanan</p>
                <div style="display: inline-block; background: linear-gradient(135deg, #020202ff 0%, #ff9800 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                    <span id="orderStatus">⏳ Menunggu Konfirmasi</span>
                </div>
            </div>
            <div style="background: #f0f9ff; padding: 1.2rem; border-radius: 12px; border-left: 4px solid #2196F3; text-align: left; margin-bottom: 2rem;">
                <p style="margin: 0.5rem 0; color: #555; font-size: 0.9rem;"><strong style="color: #2196F3;">📱 Cek Status:</strong> Hubungi customer service melalui tombol chat atau cek nomor antrian Anda.</p>
                <p style="margin: 0.5rem 0; color: #555; font-size: 0.9rem;"><strong style="color: #2196F3;">⏱️ Admin akan merespon pesanan Anda dalam beberapa menit.</strong></p>
            </div>
        </div>
        <div style="margin-top: 2rem;">
            <button class="checkout-btn" style="width: 100%; background: linear-gradient(135deg, #8B4513 0%, #6B3410 100%); color: white; border: none; padding: 1rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem;" onclick="closeSuccess()">← Kembali ke Beranda</button>
        </div>
    </div>

    <!-- Purchase Modal -->
    <div class="message-modal" id="purchaseModal">
        <div class="message-modal-content" style="max-width: 400px; text-align: center;">
            <button class="message-close-btn" onclick="closePurchaseModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1rem; font-size: 1.5rem;">Pilih Opsi Pembelian</h2>
            <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <p style="color: #666; margin: 0; font-size: 0.95rem;">Apa yang ingin Anda lakukan?</p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <button onclick="addToCartOnly()" style="background: #f0f0f0; color: #333; border: 2px solid #ddd; padding: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s;">🛒 Masukkan Keranjang</button>
                <button onclick="buyNow()" style="background: var(--primary); color: white; border: none; padding: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s;">⚡ Beli Sekarang</button>
            </div>
        </div>
    </div>
    <div class="message-overlay" id="purchaseOverlay" onclick="closePurchaseModal()"></div>

    <!-- CAPTCHA Modal -->
    <div class="message-modal" id="captchaModal">
        <div class="message-modal-content" style="max-width: 500px;">
            <button class="message-close-btn" onclick="closeCaptchaModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1.5rem; font-size: 1.5rem;">🔐 Verifikasi Keamanan</h2>
            <div style="background: #fff8e1; border: 2px solid #ffca28; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; text-align: center;">
                <p style="margin: 0 0 1rem 0; font-size: 0.9rem; color: #856404; font-weight: 600;">Silakan verifikasi bahwa Anda bukan robot</p>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-callback="onRecaptchaSuccess" style="display: flex; justify-content: center; margin-bottom: 1rem;"></div>
                <p style="margin: 0; font-size: 0.75rem; color: #999;">Dilindungi oleh reCAPTCHA</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button onclick="closeCaptchaModal()" style="flex: 1; padding: 0.75rem; background: #ddd; color: #333; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                <button onclick="verifyCaptchaAndCheckout()" id="verifyCaptchaBtn" style="flex: 1; padding: 0.75rem; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; opacity: 0.6; cursor: not-allowed;" disabled>✓ Verifikasi</button>
            </div>
        </div>
    </div>
    <div class="message-overlay" id="captchaOverlay" onclick="closeCaptchaModal()"></div>

    

    <!-- Review Modal -->
    <div id="reviewModal" class="message-modal">
        <div class="message-modal-content" style="max-width: 600px;">
            <button class="message-close-btn" onclick="closeReviewModal()">×</button>
            <h2 style="text-align: center; color: var(--primary); margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">Beri Ulasan</h2>
            <form id="reviewForm" onsubmit="submitReview(event)">
                <input type="hidden" id="reviewOrderId" name="order_id">
                <div class="form-group" style="text-align: center;">
                    <label>Rating Produk</label>
                    <div class="star-rating" id="starRating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" value="5">
                </div>
                <div class="form-group">
                    <label>Nama Tampilan (Opsional)</label>
                    <input type="text" name="display_name" placeholder="Nama yang ingin ditampilkan">
                </div>
                <div class="form-group">
                    <label>Ulasan Anda</label>
                    <textarea name="comment" rows="4" placeholder="Ceritakan pengalaman Anda menikmati roti kami..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Foto/Video (Opsional)</label>
                    <input type="file" name="media[]" id="mediaInput" multiple accept="image/*,video/*" onchange="previewMedia()">
                    <div id="mediaPreview" style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;"></div>
                </div>
                <button type="submit" class="submit-msg-btn">Kirim Ulasan</button>
            </form>
        </div>
    </div>
    <div id="reviewOverlay" class="message-overlay" onclick="closeReviewModal()"></div>

    <!-- Footer -->
    <footer>
        <!-- Left decorative image - wheat/bread ingredients -->
        <div class="footer-deco-left"></div>
        
        <!-- Right decorative image - artisan bread -->
        <div class="footer-deco-right"></div>
        
        <div class="footer-content">
            <div class="footer-info">
                <h3>📍 Dapoer Budess Bakery</h3>
                <p>Toko roti premium dengan bahan pilihan berkualitas tinggi dan harga terjangkau.</p>
                <div class="footer-contact">
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <div class="contact-label">Lokasi</div>
                            <div class="contact-value">Jl. Wates Dalam No.61, RT.02/RW.05, Pasirmulya, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16118</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <div class="contact-label">Telepon</div>
                            <div class="contact-value">+62 821-1997-9538</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div class="contact-details">
                            <div class="contact-label">Email</div>
                            <div class="contact-value">destidwinursanti.d3@gmail.com</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">⏰</div>
                        <div class="contact-details">
                            <div class="contact-label">Jam Operasional</div>
                            <div class="contact-value">Senin – Minggu: 07:00 – 13:00 WIB<br>Libur pada hari raya nasional</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📱</div>
                        <div class="contact-details">
                            <div class="contact-label">Instagram</div>
                            <div class="contact-value">
                                <a href="https://www.instagram.com/dapoer_budess?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1 1 12.324 0 6.162 6.162 0 0 1-12.324 0zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm4.965-10.322a1.44 1.44 0 1 1 2.881.001 1.44 1.44 0 0 1-2.881-.001z"/></svg>
                                    @dapoer_budess
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253666.18359730975!2d106.60825187562773!3d-6.58032243989468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x611ecf1a7e8f91ab%3A0x3354703eaba33357!2sRoti%20Panggang%20Dapoer%20Budess!5e0!3m2!1sen!2sus!4v1770439312098!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Dapoer Budess Bakery. Semua hak dilindungi. | Premium Quality & Fresh Every Day</p>
        </div>
    </footer>

    <script>
        const products = @json($products);
        let cart = [];
        let currentPhone = localStorage.getItem('customerPhone') || null;
        let isFetchingChat = false;
        let messagePollingInterval;
        let lastMessageCount = 0;

        function normalizePhone(phone) {
            if (!phone) return '';
            let normalized = phone.replace(/[^0-9]/g, '');
            if (normalized.startsWith('62')) normalized = '0' + normalized.substring(2);
            else if (!normalized.startsWith('0') && normalized.length > 0) normalized = '0' + normalized;
            return normalized;
        }

        function renderProducts(containerId, filterBestseller, sortOption = null) {
            const container = document.getElementById(containerId);
            if (!container) return;
            
            if (!Array.isArray(products)) {
                console.error('Products data is not an array:', products);
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #ef4444;">Terjadi kesalahan saat memuat data produk.</div>';
                return;
            }

            let filteredProducts = filterBestseller ? products.filter(p => p && p.bestseller) : [...products];
            if (sortOption) {
                switch(sortOption) {
                    case 'bestseller': filteredProducts.sort((a, b) => (b.total_sold || 0) - (a.total_sold || 0)); break;
                    case 'price-low': filteredProducts.sort((a, b) => (a.effective_price || 0) - (b.effective_price || 0)); break;
                    case 'price-high': filteredProducts.sort((a, b) => (b.effective_price || 0) - (a.effective_price || 0)); break;
                    case 'name-asc': filteredProducts.sort((a, b) => (a.name || '').localeCompare(b.name || '')); break;
                    case 'name-desc': filteredProducts.sort((a, b) => (b.name || '').localeCompare(a.name || '')); break;
                    case 'newest': filteredProducts.sort((a, b) => b.id - a.id); break;
                }
            } else if (filterBestseller) {
                filteredProducts.sort((a, b) => (b.total_sold || 0) - (a.total_sold || 0));
            }
            if (filteredProducts.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #888; font-style: italic;">Belum ada produk yang ditampilkan saat ini.</div>';
                return;
            }
            const taglines = ["⚡ Promo terbatas!", "🔥 Stok menipis!", "✨ Fresh setiap hari!", "💯 Favorit pelanggan!", "🎉 Harga spesial hari ini!"];
            container.innerHTML = filteredProducts.map(product => {
                if (!product) return '';
                const randomTagline = taglines[Math.floor(Math.random() * taglines.length)];
                let badgeText = "";
                if (product.is_discount_active) {
                    badgeText = product.discount_type === 'percentage' ? `DISKON ${Math.round(product.discount_value)}%` : "PROMO";
                } else if (product.bestseller) {
                    badgeText = "🔥 TERLARIS";
                }
                const stockStatus = product.stock_status || {};
                const stockColors = { green: '#10b981', yellow: '#f59e0b', red: '#ef4444', orange: '#f97316' };
                const stockBgColors = { green: '#d1fae5', yellow: '#fef3c7', red: '#fee2e2', orange: '#ffedd5' };
                const buttonText = stockStatus.is_preorder ? '📅 Pre-Order untuk Besok' : '🛒 Beli';
                const buttonDisabled = !stockStatus.can_order;
                
                // Sanitize description for template literal
                const description = (product.description || 'Lembut, manis, dan fresh setiap hari').replace(/[`$\\]/g, '\\$&');
                const name = (product.name || 'Produk Roti').replace(/[`$\\]/g, '\\$&');

                return `
                    <div class="product-card" data-category="${product.category}">
                        ${badgeText ? `<div class="product-promo-badge">${badgeText}</div>` : ''}
                        <div class="product-image-wrapper">

                            <div class="product-image">
                                ${product.image ? `<img src="${product.image}" alt="${name}" style="width:100%;height:100%;object-fit:cover;">` : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#ccc;font-size:3rem;">🍞</div>`}
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">${name}</h3>
                            <p class="product-description">${description}</p>
                            ${stockStatus.label ? `<div style="margin:0.75rem 0;padding:0.5rem 0.75rem;background:${stockBgColors[stockStatus.color] || '#f3f4f6'};border-left:3px solid ${stockColors[stockStatus.color] || '#9ca3af'};border-radius:0.5rem;font-size:0.8rem;font-weight:600;color:${stockColors[stockStatus.color] || '#4b5563'};text-align:center;">${stockStatus.label}</div>` : ''}
                            <div class="price-container">
                                ${product.is_discount_active ? `<span class="price-old">Rp ${(product.price || 0).toLocaleString('id-ID')}</span><span class="price-new">Rp ${(product.effective_price || 0).toLocaleString('id-ID')}</span>` : `<span class="price-new">Rp ${(product.price || 0).toLocaleString('id-ID')}</span>`}
                            </div>
                            <p style="font-size:0.8rem;color:#888;margin-bottom:0.75rem;">${randomTagline}</p>
                            <button class="cta-button" onclick="addToCart(${product.id}, ${!!stockStatus.is_preorder})" ${buttonDisabled ? 'disabled' : ''}>${buttonText}</button>
                        </div>
                    </div>`;
            }).join('');
        }

        function applySortFilter() {
            renderProducts('productsGrid', false, document.getElementById('sortFilter').value);
        }

        function addToCart(productId, isPreorder = false) {
            const product = products.find(p => p.id === productId);
            if (!product.stock_status || !product.stock_status.can_order) {
                alert('Maaf, produk ini saat ini tidak tersedia untuk dipesan.');
                return;
            }
            
            // Jika pre-order, langsung redirect ke halaman pre-order
            if (isPreorder) {
                // Simpan produk ke localStorage sebagai cart
                const cart = [{
                    id: productId,
                    name: product.name,
                    price: product.effective_price,
                    quantity: 1
                }];
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Redirect ke halaman pre-order
                window.location.href = '/preorder';
                return;
            }
            
            // Untuk instant order, lanjutkan seperti biasa
            window.selectedProduct = { id: productId, name: product.name, price: product.effective_price, isPreorder: false };
            showPurchaseModal();
        }

        function showPurchaseModal() {
            document.getElementById('purchaseModal').classList.add('active');
            document.getElementById('purchaseOverlay').classList.add('active');
        }

        function closePurchaseModal() {
            document.getElementById('purchaseModal').classList.remove('active');
            document.getElementById('purchaseOverlay').classList.remove('active');
        }

        function addToCartOnly() {
            // Langsung tambah ke keranjang tanpa CAPTCHA
            const product = products.find(p => p.id === window.selectedProduct.id);
            const existingItem = cart.find(item => item.id === window.selectedProduct.id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ 
                    ...product, 
                    quantity: 1, 
                    price: product.effective_price, 
                    original_price: product.price, 
                    is_discounted: product.is_discount_active, 
                    is_preorder: window.selectedProduct.isPreorder 
                });
            }
            
            updateCart();
            closePurchaseModal();
            showNotification(`${product.name} ditambahkan ke keranjang!`);
        }

        function buyNow() {
            // Tambah ke keranjang lalu lanjut ke checkout (yang akan memicu CAPTCHA)
            const product = products.find(p => p.id === window.selectedProduct.id);
            const existingItem = cart.find(item => item.id === window.selectedProduct.id);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ 
                    ...product, 
                    quantity: 1, 
                    price: product.effective_price, 
                    original_price: product.price, 
                    is_discounted: product.is_discount_active, 
                    is_preorder: window.selectedProduct.isPreorder 
                });
            }
            
            updateCart();
            closePurchaseModal();
            goToCheckout(true);
        }

        function executePendingCartAction() {
            // Handle different pending actions
            if (window.pendingCartAction === 'go_to_checkout') {
                // Just go to checkout page (no product to add)
                showSection('checkout');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Auto-fill saved phone if exists
                const savedPhone = localStorage.getItem('customerPhone');
                if (savedPhone) {
                    const phoneInput = document.querySelector('input[name="customer_phone"]');
                    if (phoneInput && !phoneInput.value) {
                        phoneInput.value = savedPhone;
                    }
                }
            }
            
            window.pendingCartAction = null;
        }

        function showCaptchaModal() {
            document.getElementById('captchaModal').classList.add('active');
            document.getElementById('captchaOverlay').classList.add('active');
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            window.recaptchaToken = null;
            const btn = document.getElementById('verifyCaptchaBtn');
            if (btn) { btn.disabled = true; btn.style.opacity = '0.6'; btn.style.cursor = 'not-allowed'; }
        }

        function closeCaptchaModal() {
            document.getElementById('captchaModal').classList.remove('active');
            document.getElementById('captchaOverlay').classList.remove('active');
            if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
            window.recaptchaToken = null;
        }

        function verifyCaptchaAndCheckout() {
            const token = window.recaptchaToken;
            if (!token) { showNotification('Silakan selesaikan verifikasi reCAPTCHA terlebih dahulu'); return; }
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/checkout', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ _token: csrfToken, recaptcha_token: token, verify_only: true })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success || data.captcha_verified) {
                    closeCaptchaModal();
                    showNotification('✓ Verifikasi berhasil!');
                    
                    // Execute pending cart action after verification
                    setTimeout(() => {
                        if (window.pendingCartAction) {
                            executePendingCartAction();
                        }
                    }, 500);
                } else {
                    showNotification('❌ Verifikasi gagal. Silakan coba lagi.');
                    setTimeout(() => { if (typeof grecaptcha !== 'undefined') grecaptcha.reset(); window.recaptchaToken = null; }, 500);
                }
            })
            .catch(err => { console.error(err); showNotification('Terjadi kesalahan saat verifikasi.'); });
        }

        function updateCart() {
            const cartCount = document.getElementById('cartCount');
            const cartItems = document.getElementById('cartItems');
            const cartSummary = document.getElementById('cartSummary');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            // Update cart count in promo modal if exists
            const promoCartCount = document.getElementById('promoModalCartCount');
            if (promoCartCount) promoCartCount.textContent = totalItems;

            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart"><div class="empty-cart-icon">🛒</div><p>Keranjang Anda masih kosong</p></div>';
                cartSummary.style.display = 'none';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="cart-item">
                        <div class="cart-item-image">${item.image ? `<img src="${item.image}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;">` : '🍞'}</div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">Rp ${item.price.toLocaleString('id-ID')}</div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <span style="padding: 0 1rem;">${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                                <button class="remove-item" onclick="removeItem(${item.id})">Hapus</button>
                            </div>
                        </div>
                    </div>`).join('');
                updateTotals();
                cartSummary.style.display = 'block';
            }
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('total').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) removeItem(productId);
                else updateCart();
            }
        }

        function removeItem(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        function toggleCart() {
            document.getElementById('cartModal').classList.toggle('active');
            document.getElementById('cartOverlay').classList.toggle('active');
        }

        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        function showSlide(n) {
            if (n >= slides.length) slideIndex = 0;
            if (n < 0) slideIndex = slides.length - 1;
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            slides[slideIndex].classList.add('active');
            dots[slideIndex].classList.add('active');
        }

        function moveSlide(n) { slideIndex += n; showSlide(slideIndex); }
        function currentSlide(n) { slideIndex = n; showSlide(slideIndex); }
        window.addEventListener('load', () => showSlide(0));

        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
            document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active'));
            const navLink = document.querySelector(`.nav-links a[onclick*="${sectionId}"]`);
            if (navLink) navLink.classList.add('active');
            const promoBanner = document.getElementById('promoBanner');
            if (promoBanner) promoBanner.style.display = (sectionId === 'checkout' || sectionId === 'profile') ? 'none' : 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            const navMenu = document.getElementById('navMenu');
            if (navMenu) navMenu.classList.remove('open');
        }

        function goToCheckout(skipToggle = false) {
            if (cart.length === 0) { 
                alert('Keranjang Anda masih kosong!'); 
                return; 
            }
            
            // SECURITY: Check max items per order (10 items)
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            if (totalItems > 10) {
                showNotification('⚠️ Maksimal 10 item per pesanan. Silakan kurangi jumlah item di keranjang.');
                return;
            }
            
            // SECURITY: Check checkout cooldown (rate limiting)
            const lastCheckoutTime = localStorage.getItem('lastCheckoutTime');
            const now = Date.now();
            const cooldownSeconds = 10; // 10 seconds cooldown
            
            if (lastCheckoutTime) {
                const timeSinceLastCheckout = (now - parseInt(lastCheckoutTime)) / 1000;
                if (timeSinceLastCheckout < cooldownSeconds) {
                    const remainingTime = Math.ceil(cooldownSeconds - timeSinceLastCheckout);
                    showNotification(`⏳ Mohon tunggu ${remainingTime} detik sebelum melakukan pesanan lagi.`);
                    return;
                }
            }
            
            // Set pending action to go to checkout
            window.pendingCartAction = 'go_to_checkout';
            if (!skipToggle) toggleCart();
            
            // Show CAPTCHA modal for verification
            showCaptchaModal();
        }

        function toggleAddressFields(method) {
            const addressSection = document.getElementById('addressSection');
            const pickupSection = document.getElementById('pickupSection');
            const deliveryLabel = document.getElementById('shipping-delivery');
            const pickupLabel = document.getElementById('shipping-pickup');
            const shippingInfoBox = document.getElementById('shippingInfoBox');
            if (method === 'delivery') {
                deliveryLabel.style.borderColor = 'var(--primary)';
                deliveryLabel.style.backgroundColor = '#fffcf5';
                pickupLabel.style.borderColor = '#ddd';
                pickupLabel.style.backgroundColor = 'white';
                addressSection.style.display = 'block';
                pickupSection.style.display = 'none';
                if (shippingInfoBox) shippingInfoBox.style.display = 'block';
                addressSection.querySelectorAll('input').forEach(input => { if (input.name !== 'house_details') input.required = true; });
            } else {
                pickupLabel.style.borderColor = 'var(--primary)';
                pickupLabel.style.backgroundColor = '#fffcf5';
                deliveryLabel.style.borderColor = '#ddd';
                deliveryLabel.style.backgroundColor = 'white';
                addressSection.style.display = 'none';
                pickupSection.style.display = 'block';
                if (shippingInfoBox) shippingInfoBox.style.display = 'none';
                addressSection.querySelectorAll('input').forEach(input => { input.required = false; });
            }
        }

        function handleCheckoutSubmit(event) {
            event.preventDefault();
            
            // SECURITY: Frontend validation
            const formData = new FormData(event.target);
            const customerName = formData.get('customer_name');
            const customerPhone = formData.get('customer_phone');
            
            // 1. Validate name (required, min 3 chars)
            if (!customerName || customerName.trim().length < 3) {
                showNotification('⚠️ Nama harus diisi minimal 3 karakter.');
                return;
            }
            
            // 2. Validate phone (only digits, min 10 chars)
            const phoneDigits = customerPhone.replace(/[^0-9]/g, '');
            if (phoneDigits.length < 10) {
                showNotification('⚠️ Nomor telepon harus minimal 10 digit.');
                return;
            }
            
            // 3. Check max items per order
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            if (totalItems > 10) {
                showNotification('⚠️ Maksimal 10 item per pesanan.');
                return;
            }
            
            // 4. Check stock availability
            for (let item of cart) {
                const product = products.find(p => p.id === item.id);
                if (product && product.stock < item.quantity) {
                    alert(`Stok ${product.name} tidak cukup!`);
                    return;
                }
            }
            
            // 5. Check checkout cooldown (rate limiting)
            const lastCheckoutTime = localStorage.getItem('lastCheckoutTime');
            const now = Date.now();
            const cooldownSeconds = 10;
            
            if (lastCheckoutTime) {
                const timeSinceLastCheckout = (now - parseInt(lastCheckoutTime)) / 1000;
                if (timeSinceLastCheckout < cooldownSeconds) {
                    const remainingTime = Math.ceil(cooldownSeconds - timeSinceLastCheckout);
                    showNotification(`⏳ Mohon tunggu ${remainingTime} detik sebelum melakukan pesanan lagi.`);
                    return;
                }
            }
            
            // Set checkout timestamp for rate limiting
            localStorage.setItem('lastCheckoutTime', now.toString());
            
            processCheckout(formData);
        }

        function processCheckout(formData) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);
            const items = cart.map(item => ({ product_id: item.id, product_name: item.name, price: item.price, quantity: item.quantity }));
            formData.append('items_json', JSON.stringify(items));
            
            // Show loading state
            const submitBtn = document.querySelector('#checkoutForm button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.textContent : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Memproses...';
            }
            
            fetch('/checkout', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;
                if (!response.ok) {
                    // Check if user is blocked
                    if (response.status === 403 || (data && data.blocked)) {
                        throw new Error('BLOCKED');
                    }
                    throw new Error(data?.message || `Error ${response.status}`);
                }
                return data;
            })
            .then(data => {
                if (data && data.success) {
                    document.getElementById('orderNumber').textContent = data.order_number || '-';
                    const phone = normalizePhone(formData.get('customer_phone'));
                    if (phone) { localStorage.setItem('customerPhone', phone); currentPhone = phone; }
                    cart = [];
                    updateCart();
                    document.getElementById('checkoutForm').reset();
                    // Redirect to payment page instead of opening modal
                    window.location.href = '/payment/' + data.order_id;
                } else {
                    alert('Gagal memproses pesanan: ' + (data?.message || 'Unknown error'));
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalBtnText;
                    }
                }
            })
            .catch(error => { 
                console.error(error); 
                
                // Handle blocked user
                if (error.message === 'BLOCKED') {
                    showBlockedMessage();
                } else {
                    alert('Checkout Gagal: ' + error.message);
                }
                
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
        }
        
        function showBlockedMessage() {
            const message = `
                <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                     background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); 
                     z-index: 100000; max-width: 500px; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                    <h2 style="color: #d32f2f; margin-bottom: 1rem; font-family: 'Playfair Display', serif;">Akses Dibatasi Sementara</h2>
                    <p style="color: #666; margin-bottom: 1.5rem; line-height: 1.6;">
                        Silakan hubungi admin untuk konfirmasi pesanan Anda.
                    </p>
                    <button onclick="this.parentElement.remove()" 
                            style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; 
                                   border-radius: 8px; cursor: pointer; font-weight: 600;">
                        Tutup
                    </button>
                </div>
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                     background: rgba(0,0,0,0.5); z-index: 99999;" 
                     onclick="this.nextElementSibling.remove(); this.remove();"></div>
            `;
            document.body.insertAdjacentHTML('beforeend', message);
        }

        function closeSuccess() {
            document.getElementById('successMessage').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
            showSection('home');
        }

        function showNotification(message) {
            const n = document.createElement('div');
            n.style.cssText = 'position:fixed;top:100px;right:20px;background:var(--primary);color:white;padding:1rem 1.5rem;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.3);z-index:50000;animation:slideInRight 0.4s ease;font-family:Outfit,sans-serif;';
            n.textContent = message;
            document.body.appendChild(n);
            setTimeout(() => { n.style.animation = 'slideOutRight 0.4s ease'; setTimeout(() => n.remove(), 400); }, 2000);
        }

        function openMessageModal() {
            document.getElementById('messageModal').classList.add('active');
            document.getElementById('messageOverlay').classList.add('active');
            const pulse = document.getElementById('msgPulse');
            if (pulse) pulse.style.display = 'none'; // Hide pulse when opened
            const savedPhone = localStorage.getItem('customerPhone');
            if (savedPhone) { currentPhone = savedPhone; loadChatThread(savedPhone); }
            else resetMessageModal();
        }

        function logoutChat() {
            if (confirm('Apakah Anda yakin ingin mengganti nomor?')) {
                localStorage.removeItem('customerPhone');
                currentPhone = null;
                stopMessagePolling();
                resetMessageModal();
            }
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.remove('active');
            document.getElementById('messageOverlay').classList.remove('active');
        }

        function resetMessageModal() {
            document.getElementById('messageLoginSection').style.display = 'block';
            document.getElementById('chatThreadSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'none';
            document.getElementById('searchPhone').value = '';
        }

        async function loadChatThread(manualPhone = null) {
            if (isFetchingChat) return;
            let phone = manualPhone || document.getElementById('searchPhone').value;
            const loading = document.getElementById('chatLoading');
            const searchBtn = document.getElementById('btnSearchChat');
            const infoBox = document.getElementById('chatSearchInfo');
            if (!phone || !phone.trim()) { const s = localStorage.getItem('customerPhone'); if (s) phone = s; else return; }
            phone = normalizePhone(phone);
            isFetchingChat = true;
            if (loading) loading.style.display = 'block';
            if (searchBtn) { searchBtn.disabled = true; searchBtn.originalText = searchBtn.textContent; searchBtn.textContent = '...'; }
            if (infoBox) infoBox.style.display = 'none';
            try {
                const response = await fetch(`/order-status/${encodeURIComponent(phone)}`);
                if (response.status === 404) {
                    if (infoBox) { infoBox.textContent = "Nomor belum terdaftar. Silakan isi form di bawah."; infoBox.style.display = 'block'; }
                    setTimeout(() => { showFirstMessageForm(phone); if (loading) loading.style.display = 'none'; if (searchBtn) { searchBtn.disabled = false; searchBtn.textContent = searchBtn.originalText || 'Cari'; } isFetchingChat = false; }, 500);
                    return;
                }
                const rawText = await response.text();
                let data;
                try { data = JSON.parse(rawText); } catch (e) {
                    const lb = rawText.lastIndexOf('}');
                    if (lb !== -1) { try { data = JSON.parse(rawText.substring(0, lb + 1)); } catch (e2) { throw new Error('Invalid JSON'); } }
                    else throw new Error('No JSON found');
                }
                if (data.success) {
                    currentPhone = phone;
                    localStorage.setItem('customerPhone', phone);
                    document.getElementById('currentChatPhone').textContent = phone;
                    document.getElementById('messageLoginSection').style.display = 'none';
                    document.getElementById('firstMessageSection').style.display = 'none';
                    fetch('/messages/mark-read', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ phone }) }).catch(e => console.warn(e));
                    displayStatusAndChat(data);
                    startMessagePolling();
                } else { showFirstMessageForm(phone); }
            } catch (error) {
                console.error(error);
                if (infoBox) { infoBox.textContent = "Gagal memuat chat. Pastikan koneksi internet aktif."; infoBox.style.display = 'block'; infoBox.style.background = '#f8d7da'; infoBox.style.color = '#721c24'; }
            } finally {
                isFetchingChat = false;
                if (loading) loading.style.display = 'none';
                if (searchBtn) { searchBtn.disabled = false; searchBtn.textContent = searchBtn.originalText || 'Cari'; }
            }
        }

        function displayStatusAndChat(data) {
            document.getElementById('messageLoginSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'none';
            const orderSection = document.getElementById('orderHistorySection');
            const orderList = document.getElementById('orderHistoryList');
            if (data.orders && data.orders.length > 0) {
                orderSection.style.display = 'block';
                orderList.innerHTML = data.orders.map(order => {
                    let actionButtons = '';
                    if (order.payment_method === 'QRIS' && order.payment_status !== 'paid' && order.status !== 'cancelled') {
                    }
                    return `
                        <div style="padding:1rem;border-bottom:1px solid #eee;margin-bottom:0.5rem;background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.5rem;">
                                <strong style="color:var(--primary);">${order.order_number}</strong>
                                <span class="status-badge status-${order.status}">${order.status_label || order.status}</span>
                            </div>
                            <div style="font-size:0.95rem;color:#444;margin-bottom:0.75rem;">
                                ${order.items.map(i => `<div style="display:flex;justify-content:space-between;"><span>${i.quantity}x ${i.product_name}</span><span>Rp ${i.price}</span></div>`).join('')}
                            </div>
                            ${order.estimated_delivery_date ? `
                                <div style="background:#e1f5fe;border:1px solid #b3e5fc;padding:0.75rem;border-radius:8px;margin:0.75rem 0;color:#01579b;">
                                    <div style="font-weight: bold; margin-bottom: 4px;">📍 Estimasi ${order.shipping_method === 'pickup' ? 'Siap Diambil' : 'Pengiriman'}:</div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span>📅 ${order.estimated_delivery_date}</span>
                                        <span>⏰ ${order.estimated_delivery_time} WIB</span>
                                    </div>
                                    ${order.estimated_delivery_message ? `<div style="margin-top: 4px; font-size: 0.85rem; font-style: italic;">📝 ${order.estimated_delivery_message}</div>` : ''}
                                </div>
                            ` : ''}
                            <div style="font-size:0.85rem;color:#666;display:flex;justify-content:space-between;align-items:center;margin-top:0.75rem;">
                                <span>${order.created_at}</span>
                                <div style="display:flex;gap:0.5rem;">
                                    ${actionButtons}
                                    ${(order.status === 'delivered' || order.status === 'completed') && !order.has_reviewed ? `<button onclick="openReviewModal('${order.id}')" style="background:var(--accent);color:white;border:none;padding:4px 12px;border-radius:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">★ Beri Ulasan</button>` : ''}
                                    ${order.has_reviewed ? '<span style="color:#4CAF50;font-size:0.85rem;font-weight:600;">✓ Diulas</span>' : ''}
                                </div>
                            </div>
                        </div>`;
                }).join('');
            } else { orderSection.style.display = 'none'; }

            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';
            document.getElementById('chatThreadSection').style.display = 'flex';
            const msgs = data.notifications || data.messages || [];
            msgs.forEach(msg => {
                const messageDiv = document.createElement('div');
                const sender = msg.sender || msg.sender_type;
                messageDiv.style.cssText = `display:flex;justify-content:${sender === 'user' ? 'flex-end' : 'flex-start'};`;
                const bubble = document.createElement('div');
                bubble.style.cssText = `max-width:70%;padding:0.75rem 1rem;border-radius:8px;word-wrap:break-word;background:${sender === 'user' ? '#e3f2fd' : '#f3e5f5'};color:${sender === 'user' ? '#1565c0' : '#6a1b9a'};`;
                const timeStr = msg.created_at_formatted || msg.created_at;
                const statusHtml = sender === 'user' ? (msg.is_read ? `<span style="margin-left:6px;font-size:0.85rem;color:#00bcd4;font-weight:bold;letter-spacing:-3px;">✓✓</span>` : `<span style="margin-left:6px;font-size:0.85rem;color:#999;">✓</span>`) : '';
                bubble.innerHTML = `<div>${msg.message}</div><div style="font-size:0.75rem;opacity:0.7;margin-top:0.25rem;display:flex;justify-content:space-between;align-items:center;"><span>${timeStr}</span>${statusHtml}</div>`;
                messageDiv.appendChild(bubble);
                chatMessages.appendChild(messageDiv);
            });
            setTimeout(() => { chatMessages.scrollTop = chatMessages.scrollHeight; }, 100);
            if (lastMessageCount === 0) lastMessageCount = msgs.length;
        }

        function showFirstMessageForm(phone = '') {
            document.getElementById('messageLoginSection').style.display = 'none';
            document.getElementById('chatThreadSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'block';
            if (phone) document.getElementById('senderPhone').value = phone;
        }

        function sendMessage(event) {
            event.preventDefault();
            const phone = normalizePhone(document.getElementById('senderPhone').value);
            const name = document.getElementById('senderName').value;
            const message = document.getElementById('senderMessage').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/messages', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ name, phone, message })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification('Pesan berhasil dikirim!');
                    localStorage.setItem('customerPhone', phone);
                    currentPhone = phone;
                    document.getElementById('messageForm').reset();
                    loadChatThread(phone);
                } else { showNotification('Gagal mengirim pesan, coba lagi'); }
            })
            .catch(error => { console.error(error); showNotification('Terjadi kesalahan saat mengirim pesan'); });
        }

        async function sendChatMessage(event) {
            event.preventDefault();
            const phone = normalizePhone(currentPhone || document.getElementById('searchPhone').value);
            const message = document.getElementById('chatInput').value.trim();
            if (!phone || !message) { alert('Pesan tidak boleh kosong'); return; }
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ name: 'User', phone, message })
                });
                const data = await response.json();
                if (data.success) {
                    document.getElementById('chatInput').value = '';
                    await checkNewMessages();
                    setTimeout(() => { const cb = document.getElementById('chatMessages'); if (cb) cb.scrollTop = cb.scrollHeight; }, 200);
                    if (!messagePollingInterval) startMessagePolling();
                } else { alert('Gagal mengirim pesan: ' + (data.message || 'Unknown error')); }
            } catch (error) { console.error(error); alert('Gagal mengirim pesan'); }
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.remove('active');
            document.getElementById('reviewOverlay').classList.remove('active');
        }

        function initStarRating() {
            const container = document.getElementById('starRating');
            if (!container) return;
            const stars = container.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('ratingValue').value = this.getAttribute('data-value');
                    highlightStars(this.getAttribute('data-value'));
                });
                star.addEventListener('mouseover', function() { highlightStars(this.getAttribute('data-value')); });
            });
            container.addEventListener('mouseleave', function() { highlightStars(document.getElementById('ratingValue').value); });
        }

        function openReviewModal(orderId) {
            document.getElementById('reviewOrderId').value = orderId;
            document.getElementById('reviewModal').classList.add('active');
            document.getElementById('reviewOverlay').classList.add('active');
            document.getElementById('reviewForm').reset();
            document.getElementById('mediaPreview').innerHTML = '';
            setTimeout(initStarRating, 100);
            highlightStars(5);
        }

        function highlightStars(value) {
            document.querySelectorAll('.star').forEach(star => {
                star.classList.toggle('active', parseInt(star.getAttribute('data-value')) <= parseInt(value));
            });
        }

        function openUploadModal(orderId) {
            document.getElementById('uploadOrderId').value = orderId;
            document.getElementById('uploadModal').classList.add('active');
            document.getElementById('uploadOverlay').classList.add('active');
            document.getElementById('uploadProofForm').reset();
            document.getElementById('proofPreviewPlaceholder').style.display = 'block';
            document.getElementById('proofPreviewImg').style.display = 'none';
            document.getElementById('proofPreviewImg').src = '';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
            document.getElementById('uploadOverlay').classList.remove('active');
        }

        function previewProof() {
            const input = document.getElementById('proofInput');
            const placeholder = document.getElementById('proofPreviewPlaceholder');
            const img = document.getElementById('proofPreviewImg');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; img.style.display = 'block'; placeholder.style.display = 'none'; };
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function submitPaymentProof(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = 'Mengupload...';
            btn.disabled = true;
            try {
                const response = await fetch('/api/upload-payment-proof', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: formData });
                const data = await response.json();
                if (data.success) { showNotification(data.message); closeUploadModal(); loadChatThread(); }
                else { alert(data.message || 'Gagal mengupload bukti pembayaran'); }
            } catch (error) { console.error(error); alert('Terjadi kesalahan saat mengupload bukti'); }
            finally { btn.textContent = originalText; btn.disabled = false; }
        }

        function previewMedia() {
            const input = document.getElementById('mediaInput');
            const preview = document.getElementById('mediaPreview');
            preview.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width:60px;height:60px;object-fit:cover;border-radius:6px;';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        async function submitReview(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const phone = localStorage.getItem('customerPhone');
            if (!phone) { alert('Sesi kadaluarsa, silakan cek status pesanan lagi.'); return; }
            formData.append('phone', phone);
            try {
                const response = await fetch('/reviews', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: formData });
                const data = await response.json();
                if (data.success) { showNotification(data.message); closeReviewModal(); setTimeout(() => window.location.reload(), 1000); }
                else { alert(data.message || 'Gagal mengirim ulasan.'); }
            } catch (error) { console.error(error); alert('Terjadi kesalahan saat mengirim ulasan.'); }
        }

        function startMessagePolling() {
            if (messagePollingInterval) clearInterval(messagePollingInterval);
            checkNewMessages();
            messagePollingInterval = setInterval(checkNewMessages, 3000);
        }

        function stopMessagePolling() {
            if (messagePollingInterval) clearInterval(messagePollingInterval);
            lastMessageCount = 0;
        }

        async function checkNewMessages() {
            if (!currentPhone) return;
            try {
                const messageModal = document.getElementById('messageModal');
                const chatThreadSection = document.getElementById('chatThreadSection');
                const isChatOpen = messageModal && messageModal.classList.contains('active') && chatThreadSection && chatThreadSection.style.display !== 'none';
                if (isChatOpen) {
                    const response = await fetch(`/order-status/${encodeURIComponent(currentPhone)}`);
                    if (response.ok) {
                        const raw = await response.text();
                        let data;
                        try { data = JSON.parse(raw); } catch (e) {
                            const lb = raw.lastIndexOf('}');
                            if (lb !== -1) { try { data = JSON.parse(raw.substring(0, lb + 1)); } catch (e2) { return; } } else return;
                        }
                        const msgs = data.notifications || data.messages || [];
                        const currentCount = msgs.length;
                        
                        // Deteksi jika ada pesan baru dari admin
                        if (currentCount > lastMessageCount && lastMessageCount > 0) {
                            const lastMsg = msgs[msgs.length - 1];
                            if (lastMsg.sender_type === 'admin') {
                                showNewMessageNotification(lastMsg.message.substring(0, 50) + (lastMsg.message.length > 50 ? '...' : ''));
                            }
                        }

                        const chatContainer = document.getElementById('chatMessages');
                        const wasAtBottom = chatContainer ? (chatContainer.scrollHeight - chatContainer.scrollTop <= chatContainer.clientHeight + 100) : true;
                        displayStatusAndChat(data);
                        lastMessageCount = currentCount;
                        if (chatContainer && wasAtBottom) setTimeout(() => { chatContainer.scrollTop = chatContainer.scrollHeight; }, 100);
                    }
                    await fetch('/messages/mark-read', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ phone: currentPhone }) });
                    document.getElementById('msgBadge').style.display = 'none';
                } else {
                    const response = await fetch(`/messages/unread/${encodeURIComponent(currentPhone)}`);
                    const data = await response.json();
                    const badge = document.getElementById('msgBadge');
                    const pulse = document.getElementById('msgPulse');
                    if (badge) { 
                        if (data.unread_count > 0) { 
                            badge.style.display = 'flex'; 
                            badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count; 
                            if (pulse) pulse.style.display = 'block'; // Show pulse when unread
                        } else { 
                            badge.style.display = 'none'; 
                            if (pulse) pulse.style.display = 'none'; // Hide pulse when no unread
                        } 
                    }
                }
            } catch (error) { console.error('[Polling] Error:', error); }
        }

        let originalTitle = document.title;
        let titleInterval = null;

        function showNewMessageNotification(messageText = "Pesan baru diterima!") {
            // 1. Audio Notification
            try {
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
                audio.play();
            } catch (e) { console.warn("Audio play failed:", e); }

            // 2. Visual Pop-up in Web Page
            const n = document.createElement('div');
            n.style.cssText = 'position:fixed;top:100px;right:20px;background:linear-gradient(135deg,#4CAF50,#45a049);color:white;padding:1rem 1.5rem;border-radius:12px;box-shadow:0 8px 25px rgba(76,175,80,0.4);z-index:50000;font-family:Outfit,sans-serif;font-weight:600;display:flex;align-items:center;gap:0.5rem;animation:slideInRight 0.3s ease-out;cursor:pointer;';
            n.innerHTML = `💬 ${messageText}`;
            n.onclick = () => { openMessageModal(); n.remove(); stopTitleFlash(); };
            document.body.appendChild(n);
            setTimeout(() => { if(n.parentElement) { n.style.animation = 'slideOutRight 0.3s ease-out'; setTimeout(() => n.remove(), 300); } }, 5000);

            // 3. Tab Title Flashing (Optimal for when user is on another tab)
            startTitleFlash();
        }

        function startTitleFlash() {
            if (titleInterval) return;
            let isFlash = false;
            titleInterval = setInterval(() => {
                document.title = isFlash ? `(1) Pesan Baru! 🍞` : originalTitle;
                isFlash = !isFlash;
            }, 1000);
            
            // Stop flashing when user focuses back or clicks anywhere
            window.addEventListener('focus', stopTitleFlash, { once: true });
            document.addEventListener('click', stopTitleFlash, { once: true });
        }

        function stopTitleFlash() {
            if (titleInterval) {
                clearInterval(titleInterval);
                titleInterval = null;
                document.title = originalTitle;
            }
        }

        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (header) header.classList.toggle('scrolled', window.scrollY > 50);
        });

        function onRecaptchaSuccess(token) {
            window.recaptchaToken = token;
            const btn = document.getElementById('verifyCaptchaBtn');
            if (btn) { btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; }
        }

        /* PROMO MODAL FUNCTIONS */
        function openPromoModal() {
            document.getElementById('promoModalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scroll
        }

        function closePromoModal(event) {
            document.getElementById('promoModalOverlay').classList.remove('active');
            document.body.style.overflow = ''; // Restore scroll
        }

        function directBuyPromo(productName, price) {
            // Normalize name: remove multiple spaces and trim
            const normalize = (str) => str.toLowerCase().replace(/\s+/g, ' ').trim();
            const normalizedTarget = normalize(productName);
            
            // Find product in products array with normalized matching
            const product = products.find(p => normalize(p.name) === normalizedTarget);
            
            if (!product) {
                // If not found in main products, create a temporary item
                // This shouldn't happen if names are synced
                const tempProduct = {
                    id: 'promo-' + Date.now(),
                    name: productName,
                    price: price,
                    effective_price: price,
                    image: null,
                    is_discount_active: false
                };
                
                const existingItem = cart.find(item => item.name === productName);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ ...tempProduct, quantity: 1 });
                }
            } else {
                const existingItem = cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ 
                        ...product, 
                        quantity: 1, 
                        price: product.effective_price, 
                        original_price: product.price, 
                        is_discounted: product.is_discount_active, 
                        is_preorder: false 
                    });
                }
            }
            
            updateCart();
            closePromoModal();
            toggleCart(); // Show cart after adding
            showNotification(`✅ ${productName} ditambahkan ke keranjang!`);
        }

        function toggleMenu() {
            const m = document.getElementById('navMenu');
            if (m) m.classList.toggle('open');
        }

        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse-red {
                0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.7); }
                70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 68, 68, 0); }
                100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0); }
            }
            @keyframes slideInRight { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
            @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        `;
        document.head.appendChild(style);

        function initPromoCountdown() {
            const countdownEl = document.getElementById('promo-countdown');
            if (!countdownEl) return;

            const endTimeStr = countdownEl.getAttribute('data-endtime');
            if (!endTimeStr) return;

            const endTime = new Date(endTimeStr).getTime();
            const daysEl = document.getElementById('timer-days');
            const hoursEl = document.getElementById('timer-hours');
            const minsEl = document.getElementById('timer-mins');
            const secsEl = document.getElementById('timer-secs');
            
            // Reference to the days item container to hide it if needed
            const daysItem = daysEl ? daysEl.closest('.timer-item') : null;

            function update() {
                const now = new Date().getTime();
                const diff = endTime - now;

                if (diff <= 0) {
                    if (daysEl) daysEl.textContent = '00';
                    if (hoursEl) hoursEl.textContent = '00';
                    if (minsEl) minsEl.textContent = '00';
                    if (secsEl) secsEl.textContent = '00';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);

                if (daysEl) {
                    daysEl.textContent = days.toString().padStart(2, '0');
                    // Hide days if 0, show if > 0
                    if (daysItem) {
                        daysItem.style.display = days > 0 ? 'flex' : 'none';
                    }
                }
                
                // If days are hidden, add days to hours (optional, but user might want "48 hours")
                // For now, let's keep it simple: if days > 0, show everything. If days = 0, hide days item.
                
                if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                if (minsEl) minsEl.textContent = mins.toString().padStart(2, '0');
                if (secsEl) secsEl.textContent = secs.toString().padStart(2, '0');
            }

            update();
            setInterval(update, 1000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initPromoCountdown();
            renderProducts('productsGrid', false);
            renderProducts('bestsellerGrid', true);
            renderProducts('bestsellerHome', true);
            initStarRating();
            const savedPhone = localStorage.getItem('customerPhone');
            if (savedPhone) { currentPhone = savedPhone; startMessagePolling(); }
            updateCart(); // Initialize cart counts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggleCart();
                    closeMessageModal();
                    closeReviewModal();
                    closeCaptchaModal();
                    closeUploadModal();
                    closePurchaseModal();
                    document.getElementById('successMessage').classList.remove('active');
                    closePromoModal();
                }
            });
            const chatInput = document.getElementById('chatInput');
            if (chatInput) {
                chatInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendChatMessage(new Event('submit')); }
                });
            }
            setTimeout(() => { document.body.style.pointerEvents = 'auto'; }, 100);
        });
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 1000, once: true, offset: 100 });</script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>