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

        /* Status Badges */
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

        /* Background Pattern Batik Halus - Dapoer Nusantara */
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
            pointer-events: none;
        }

        /* Tekstur Kertas Handmade */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' /%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='100' height='100' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }

        /* Ornamen Gandum Kiri Bawah */
        .wheat-left {
            position: fixed;
            bottom: -20px;
            left: -20px;
            width: 280px;
            height: 350px;
            opacity: 0.25;
            z-index: 1;
            pointer-events: none;
        }

        /* Ornamen Gandum Kanan Atas */
        .wheat-right {
            position: fixed;
            top: -20px;
            right: -20px;
            width: 280px;
            height: 350px;
            opacity: 0.25;
            z-index: 1;
            pointer-events: none;
            transform: rotate(180deg);
        }

        /* Motif Batik Sudut Kiri Atas */
        .batik-corner-left {
            position: fixed;
            top: 80px;
            left: 20px;
            width: 150px;
            height: 150px;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none;
        }

        /* Motif Batik Sudut Kanan Bawah */
        .batik-corner-right {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 150px;
            height: 150px;
            opacity: 0.08;
            z-index: 1;
            pointer-events: none;
            transform: rotate(180deg);
        }

        /* Siluet Daun Pisang */
        .banana-leaf {
            position: fixed;
            opacity: 0.06;
            z-index: 1;
            pointer-events: none;
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

        /* Header */
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

        /* Header saat scroll - shadow lebih tebal */
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
            pointer-events: none; /* Badge shouldn't block button click */
        }
        
        .header-actions {
            pointer-events: auto !important;
            z-index: 10001;
        }

        .cart-btn {
            pointer-events: auto !important;
            position: relative;
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

        /* Hero Slider */
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

        /* Overlay Styles - Lebih gelap untuk keterbacaan */
        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
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

        /* Slide Content */
        .slide-content {
            position: relative;
            z-index: 10;
            max-width: 650px;
            color: #fff;
            padding-left: 2rem;
        }

        /* Typography - Desktop */
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

        /* CTA Button */
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

        /* Navigation Arrows */
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

        /* Dots Indicator */
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

        /* Slide Variations */
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

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 0;
            }
            
            .hero-slider {
                height: 75vh;
                min-height: 500px;
            }
            
            .slide {
                padding: 0 5%;
                align-items: center;
            }
            
            .slide-content {
                padding-left: 0;
                padding-right: 0;
                max-width: 100%;
            }
            
            .slide h1 {
                font-size: 3rem;
                margin-bottom: 1rem;
                line-height: 1.2;
            }
            
            .slide p {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
                line-height: 1.6;
                max-width: 100%;
            }
            
            .hero-btn {
                padding: 0.8rem 1.8rem;
                font-size: 0.85rem;
            }
            
            .slider-nav {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.1rem;
            }
            
            .prev-slide { left: 1rem; }
            .next-slide { right: 1rem; }
            
            .slider-dots {
                bottom: 1.5rem;
                gap: 0.6rem;
            }
            
            .dot {
                width: 9px;
                height: 9px;
            }
            
            /* Center all slides on mobile */
            .slide-2, .slide-3 {
                justify-content: center;
                text-align: center;
            }
            
            .slide-3 .slide-content {
                padding-right: 0;
            }
        }
        
        /* Extra small mobile */
        @media (max-width: 480px) {
            .hero-slider {
                height: 75vh;
                min-height: 450px;
            }
            
            .slide h1 {
                font-size: 1.7rem;
            }
            
            .slide p {
                font-size: 0.9rem;
            }
            
            .hero-btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.8rem;
            }

            /* Success Message Mobile */
            .success-message {
                padding: 2rem 1.5rem;
                width: 95%;
                max-width: 100%;
            }

            .success-message h2 {
                font-size: 1.6rem !important;
            }

            .success-icon {
                font-size: 3rem !important;
                margin-bottom: 1rem !important;
            }
        }

        /* PROMO SECTION - Roti Sobek */
        .promo-section {
            background: transparent;
            padding: 1.5rem 2%;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeInScale 0.6s ease-out 0.3s both;
            margin: 0;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .promo-card {
            background: linear-gradient(135deg, 
                #F5EDE3 0%, 
                #FFF8DC 50%, 
                #F5EDE3 100%);
            border-radius: 20px;
            padding: 1.8rem;
            box-shadow: 
                0 15px 40px rgba(139, 90, 60, 0.15),
                0 0 30px rgba(212, 175, 55, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: visible;
            border: 2px solid #D4AF37;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
            max-width: 1000px;
            margin: 0 auto;
        }

        .promo-card:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 20px 50px rgba(139, 90, 60, 0.2),
                0 0 40px rgba(212, 175, 55, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .promo-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            align-items: center;
        }

        /* Badge Container */
        .promo-badges {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .promo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, #FF6B6B, #FF5252);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 15px rgba(255, 107, 107, 0.3);
            font-family: 'Outfit', sans-serif;
        }

        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 15px rgba(76, 175, 80, 0.3);
            font-family: 'Outfit', sans-serif;
        }

        /* Title */
        .promo-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #6B4423;
            margin: 0;
            line-height: 1.2;
            text-shadow: 1px 1px 2px rgba(139, 90, 60, 0.1);
        }

        /* Subtitle */
        .promo-subtitle {
            font-size: 0.95rem;
            color: #8B6F47;
            font-weight: 500;
            line-height: 1.5;
            margin: 0;
            max-width: 600px;
        }

        /* Pricing Section */
        .promo-pricing {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .price-original {
            font-size: 1.1rem;
            color: #B8A89A;
            text-decoration: line-through;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            opacity: 0.8;
        }

        .price-discount {
            font-size: 2.2rem;
            color: #FF5252;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            text-shadow: 0 2px 4px rgba(255, 82, 82, 0.2);
        }

        .price-save {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.15), rgba(255, 152, 0, 0.1));
            color: #FF9800;
            padding: 0.5rem 1.1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            border: 2px solid rgba(255, 152, 0, 0.3);
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.15);
        }

        /* Images Grid */
        .promo-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            width: 100%;
            margin-top: 0.8rem;
        }

        .promo-image-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
            height: 140px;
        }

        .promo-image-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        .promo-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .promo-image-item:hover img {
            transform: scale(1.05);
        }

        /* Button */
        .promo-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #FF9800, #FF8C00);
            color: white;
            border: none;
            padding: 0.9rem 2.4rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.35);
            font-family: 'Outfit', sans-serif;
            margin-top: 0.8rem;
        }

        .promo-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255, 152, 0, 0.45);
            background: linear-gradient(135deg, #FFB74D, #FFA500);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .promo-section {
                padding: 1rem 0;
            }

            .promo-card {
                padding: 1.2rem;
                border-radius: 12px;
                margin: 0 1rem;
                box-shadow: 
                    0 10px 30px rgba(139, 90, 60, 0.12),
                    0 0 20px rgba(212, 175, 55, 0.08),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
            }

            .promo-content {
                gap: 0.8rem;
            }

            .promo-title {
                font-size: 1.6rem;
                margin-bottom: 0.3rem;
            }

            .promo-subtitle {
                font-size: 0.85rem;
                line-height: 1.4;
                margin-bottom: 0.5rem;
            }

            .promo-pricing {
                gap: 0.8rem;
                margin-bottom: 0.5rem;
            }

            .price-original {
                font-size: 0.95rem;
            }

            .price-discount {
                font-size: 1.8rem;
            }

            .price-save {
                font-size: 0.75rem;
                padding: 0.4rem 0.9rem;
            }

            .promo-images {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.6rem;
                margin-top: 0.6rem;
            }

            .promo-image-item {
                height: 100px;
                border-radius: 10px;
            }

            .promo-cta {
                padding: 0.7rem 1.8rem;
                font-size: 0.8rem;
                margin-top: 0.6rem;
                box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
            }

            .promo-badges {
                gap: 0.5rem;
                margin-bottom: 0.3rem;
            }

            .promo-badge,
            .discount-badge {
                padding: 0.4rem 0.9rem;
                font-size: 0.65rem;
                letter-spacing: 0.3px;
            }
        }

        /* Extra Small Mobile */
        @media (max-width: 480px) {
            .promo-card {
                padding: 1rem;
                margin: 0 0.75rem;
            }

            .promo-title {
                font-size: 1.4rem;
            }

            .promo-subtitle {
                font-size: 0.8rem;
            }

            .price-discount {
                font-size: 1.6rem;
            }

            .price-original {
                font-size: 0.9rem;
            }

            .price-save {
                font-size: 0.7rem;
                padding: 0.35rem 0.8rem;
            }

            .promo-images {
                gap: 0.5rem;
            }

            .promo-image-item {
                height: 90px;
            }

            .promo-cta {
                padding: 0.65rem 1.6rem;
                font-size: 0.75rem;
            }

            .promo-badge,
            .discount-badge {
                padding: 0.35rem 0.8rem;
                font-size: 0.6rem;
            }

            /* Product Card Extra Small Mobile */
            .product-image-wrapper {
                padding-top: 120%;
                min-height: 280px;
            }

            .products-grid {
                gap: 1.2rem;
            }

            /* Success Message Mobile - 768px */
            .success-message {
                width: 90%;
            }

            .success-message > div[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
        }

        /* Review Button */
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
            box-shadow: 
                0 8px 25px rgba(139, 69, 19, 0.3),
                0 3px 10px rgba(139, 69, 19, 0.15),
                inset 0 -2px 0 rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
        }

        .review-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 
                0 12px 35px rgba(139, 69, 19, 0.4),
                0 5px 15px rgba(139, 69, 19, 0.2),
                inset 0 -2px 0 rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #A0522D, #F4A460);
        }

        .review-btn:active {
            transform: translateY(-1px) scale(1);
            box-shadow: 
                0 6px 20px rgba(139, 69, 19, 0.3),
                0 2px 8px rgba(139, 69, 19, 0.15),
                inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Testimoni Section */
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

        .testimoni-grid {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .testimoni-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAFAFA 100%);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.08),
                0 2px 8px rgba(0, 0, 0, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
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
        }

        .testimoni-card:hover {
            transform: translateX(8px);
            box-shadow: 
                0 15px 40px rgba(0, 0, 0, 0.12),
                0 4px 12px rgba(0, 0, 0, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
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

        .testimoni-rating {
            color: #FFB800;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .testimoni-text {
            color: #555;
            line-height: 1.7;
            font-style: italic;
            font-size: 1.05rem;
        }

        /* Navigation */
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

        /* Nav saat scroll */
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

        .nav-links a:hover {
            color: var(--secondary);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        /* Main Content */
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

        .section.active {
            display: block;
        }

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

        /* Reviews Grid */
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
            box-shadow: 
                0 10px 35px rgba(139, 69, 19, 0.1),
                0 3px 10px rgba(139, 69, 19, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(139, 69, 19, 0.05);
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(244, 164, 96, 0.08), transparent 70%);
            border-radius: 50%;
        }

        .review-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 15px 45px rgba(139, 69, 19, 0.15),
                0 5px 15px rgba(139, 69, 19, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: rgba(244, 164, 96, 0.3);
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

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

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

        .reviewer-time {
            font-size: 0.75rem;
            color: #999;
            margin-top: 2px;
        }

        .review-text {
            color: #555;
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .stars {
            color: #FFD700;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        /* Star Rating Input */
        .star-rating {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin: 1rem 0;
        }

        .star {
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            line-height: 1;
        }

        .star:hover,
        .star:hover ~ .star {
            color: #FFD700;
        }

        .star.active {
            color: #FFD700 !important;
        }

        /* Filter Container */
        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .filter-wrapper {
            position: relative;
            min-width: 250px;
        }

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

        .sort-select option {
            padding: 1rem;
            background: white;
            color: #8B5A3C;
            font-weight: 500;
        }

        /* Product Grid */
        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        /* Original Clean Product Card */
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

        /* Ornamen Sudut Atas Kiri */
        .product-card::before {
            content: '◆';
            position: absolute;
            top: -12px;
            left: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            z-index: 5;
        }

        /* Ornamen Sudut Bawah Kanan */
        .product-card::after {
            content: '◆';
            position: absolute;
            bottom: -12px;
            right: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            z-index: 5;
        }

        /* Image Wrapper */
        .product-image-wrapper {
            position: relative;
            padding-top: 75%; /* 4:3 Aspect Ratio */
            overflow: hidden;
            background: #f8f9fa;
        }

        /* Ornamen Sudut Atas Kanan */
        .product-image-wrapper::before {
            content: '◆';
            position: absolute;
            top: -12px;
            right: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            opacity: 1;
            z-index: 5;
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

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        /* Promo Badge */
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

        /* Info Section */
        .product-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            position: relative;
        }

        /* Ornamen Sudut Bawah Kiri */
        .product-info::after {
            content: '◆';
            position: absolute;
            bottom: -12px;
            left: -12px;
            font-size: 1.8rem;
            color: #D4AF37;
            opacity: 1;
            z-index: 5;
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
        
        /* Stock Badge (Simple) */
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

        /* Price */
        .price-container {
            margin-top: auto;
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .price-old {
            font-size: 0.9rem;
            color: #999;
            text-decoration: line-through;
        }

        .price-new {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .fresh-tag {
            display: none; /* Hide in simple mode */
        }

        /* Button */
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

        /* Best Seller Title */
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

        /* Standalone Best Seller (Feature Highlights) */
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

        .highlight-card:hover .highlight-image img {
            transform: scale(1.1);
        }

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

        .highlight-info {
            padding: 2rem;
            text-align: center;
        }

        .highlight-info h3 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }

        .highlight-info p {
            color: #777;
            line-height: 1.7;
            font-size: 1.05rem;
        }

        @media (max-width: 768px) {
            .feature-highlights {
                grid-template-columns: 1fr;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .product-image {
                width: 150px;
                height: 150px;
            }
            
            .product-name {
                font-size: 1.3rem;
            }
            
            .price-new {
                font-size: 1.5rem;
            }

            /* Responsive Ornamen Mobile */
            .wheat-left, .wheat-right {
                width: 180px;
                height: 220px;
            }

            .batik-corner-left {
                top: 70px;
                left: 10px;
                width: 100px;
                height: 100px;
            }

            .batik-corner-right {
                bottom: 10px;
                right: 10px;
                width: 100px;
                height: 100px;
            }

            .banana-leaf-1 {
                width: 150px;
                height: 90px;
            }

            .banana-leaf-2 {
                width: 140px;
                height: 85px;
            }
        }

        /* Shopping Cart */
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
            z-index: 2000;
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
            z-index: 1999;
            pointer-events: none; /* Safety: Prevent blocking when not active */
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

        .cart-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
        }

        .close-cart {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .close-cart:hover {
            transform: rotate(90deg);
        }

        .cart-items {
            padding: 1.5rem;
        }

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
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .cart-item-price {
            color: var(--secondary);
            font-weight: 600;
        }

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

        .quantity-btn:hover {
            background: var(--secondary);
            transform: scale(1.1);
        }

        .remove-item {
            color: #FF4444;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .remove-item:hover {
            color: #CC0000;
        }

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

        .checkout-btn:hover {
            background: var(--dark);
            transform: scale(1.02);
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Checkout Form */
        .checkout-form {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px var(--shadow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Tentang Kami Section Styling */
        .about-container {
            padding: 4rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-header {
            text-align: center;
            margin-bottom: 4rem;
        }

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

        .about-images-wrapper {
            position: relative;
            padding: 2rem;
        }

        .about-img-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 8px solid white;
            transition: all 0.5s ease;
        }

        .about-img-large {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

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

        .about-text-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .about-description {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--text);
            text-align: justify;
        }

        .vision-mission-section {
            background: var(--light-cream);
            padding: 4rem;
            border-radius: 30px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid rgba(139, 69, 19, 0.05);
        }

        .vm-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 4rem;
        }

        .vm-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent);
            display: inline-block;
        }

        .mission-list {
            list-style: none;
            padding: 0;
        }

        .mission-item {
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

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
            flex-shrink: 0;
        }

        .mission-text {
            color: var(--text);
            line-height: 1.6;
        }

        .vm-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent);
            display: inline-block;
        }

        .mission-list {
            list-style: none;
            padding: 0;
        }

        .mission-item {
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

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

        .mission-text {
            font-size: 1.05rem;
            color: var(--text);
            line-height: 1.6;
        }

        @media (max-width: 992px) {
            .about-grid, .vm-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            .about-header .about-script-title { font-size: 2.8rem; }
            .about-header .about-main-title { font-size: 2.2rem; }
            .about-images-wrapper { order: 2; }
            .about-text-content { order: 1; }
            .about-img-large { height: 400px; }
            .about-img-small { height: 250px; }
            .vision-mission-section { padding: 2rem; }
        }

        /* Success Message */
        /* Message Modal */
        .message-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3000;
            animation: popIn 0.5s ease;
        }

        .message-modal.active {
            display: block;
        }

        .message-modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 90%;
            position: relative;
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

        .message-close-btn:hover {
            color: var(--primary);
        }

        .message-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2999;
            display: none;
            pointer-events: none; /* Safety */
        }

        .message-modal.active ~ .message-overlay,
        .message-overlay.active {
            display: block;
            pointer-events: auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary);
        }

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
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

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
            z-index: 3000;
            animation: popIn 0.5s ease;
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .success-message.active {
            display: block;
        }

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

        

        /* Why Choose Us Section - Reference Lookalike */
        .why-choose-section {
            padding: 5rem 0;
            background-color: transparent;
            position: relative;
        }

        .why-choose-title {
            text-align: center;
            font-size: 2.5rem;
            color: #4A2C2A;
            margin-bottom: 4rem;
            text-shadow: none;
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

        .zigzag-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .zigzag-row {
            display: flex;
            align-items: center;
            gap: 3rem;
            margin-bottom: 4rem;
        }

        @media (max-width: 900px) {
            .zigzag-row {
                flex-direction: column;
                gap: 2rem;
            }
            .zigzag-row, .zigzag-row.reverse {
                flex-direction: column;
            }
             .zigzag-row.reverse .zigzag-image { order: 1; }
             .zigzag-row.reverse .zigzag-card { order: 2; }
        }

        /* IMAGE STYLES */
        .zigzag-image {
            flex: 1;
            position: relative;
            z-index: 1;
        }

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
        
        .zigzag-row.reverse .zigzag-image img {
             transform: rotate(2deg);
        }

        .zigzag-image:hover img {
            transform: scale(1.02) rotate(0deg);
            z-index: 5;
        }

        /* CARD STYLES */
        .zigzag-card {
            flex: 1.2;
            background: #FFFBF5;
            background-image: 
                radial-gradient(#D4AF37 0.5px, transparent 0.5px), 
                radial-gradient(#D4AF37 0.5px, #FFFBF5 0.5px);
            background-size: 20px 20px; 
            background-position: 0 0, 10px 10px;
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
        }
        
        .zigzag-card::after {
            content: '🌿';
            position: absolute;
            bottom: 10px;
            left: 15px;
            font-size: 2rem;
            opacity: 0.1;
            transform: rotate(-135deg);
        }

        /* SPEECH BUBBLE POINTER */
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

        @media (max-width: 900px) {
            .speech-pointer { display: none; }
        }

        /* CARD HEADER CONTENT */
        .card-header {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            margin-bottom: 1.2rem;
        }

        .title-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

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
            .card-desc {
                margin-left: 0;
            }
            .card-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .title-underline {
                margin: 0.5rem auto;
            }
            .card-title {
                text-align: center;
            }
        }

        /* Reviews Section */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .review-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px var(--shadow);
            transition: all 0.3s ease;
            border-left: 4px solid var(--accent);
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px var(--shadow);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .reviewer-info h4 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
        }

        .reviewer-time {
            font-size: 0.85rem;
            color: #999;
        }

        .stars {
            color: #FFB800;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .review-text {
            color: #666;
            line-height: 1.6;
            font-size: 0.95rem;
            font-style: italic;
        }

        /* Section Divider */
        .section-divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            margin: 3rem 0;
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 4rem 1.5rem;
                min-height: 40vh;
                text-align: left;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .promo-section {
                margin: -2rem 1rem 2rem;
                padding: 0;
            }

            .promo-card {
                padding: 2rem;
            }

            .promo-title {
                font-size: 2rem;
            }

            .promo-subtitle {
                font-size: 1rem;
            }

            .promo-pricing {
                gap: 1rem;
            }

            .price-original {
                font-size: 1.3rem;
            }

            .price-discount {
                font-size: 2.2rem;
            }

            .price-save {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }

            .promo-cta {
                width: 100%;
                justify-content: center;
                padding: 1.2rem 2rem;
                font-size: 1rem;
            }

            .promo-content {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            /* Filter Mobile */
            .filter-container {
                padding: 0;
                margin-bottom: 1.5rem;
            }

            .filter-wrapper {
                min-width: 100%;
            }

            .sort-select {
                font-size: 0.9rem;
                padding: 0.9rem 1.2rem;
                padding-right: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }

            .reviews-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .cart-modal {
                max-width: 100%;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 2rem;
            }

            .container {
                padding: 2rem 1rem;
            }

            .benefit-card {
                padding: 1.5rem;
            }

            .review-card {
                padding: 1.25rem;
            }

            /* Product Card Mobile */
            .product-card {
                border: 2.5px solid #D4AF37;
                border-radius: 15px;
            }

            .product-card::before {
                top: -10px;
                left: -10px;
                font-size: 1.5rem;
            }

            .product-card::after {
                bottom: -10px;
                right: -10px;
                font-size: 1.5rem;
            }

            .product-image-wrapper {
                padding-top: 100% !important;
                min-height: 400px !important;
            }

            .product-image {
                width: 100% !important;
                height: 100% !important;
            }

            .product-image img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }

            .product-image-wrapper::before {
                top: -10px;
                right: -10px;
                font-size: 1.5rem;
            }

            .product-info::after {
                bottom: -10px;
                left: -10px;
                font-size: 1.5rem;
            }

            .product-info {
                padding: 1.2rem;
            }

            .product-name {
                font-size: 1.1rem;
            }

            .product-description {
                font-size: 0.85rem;
            }

            .price-new {
                font-size: 1.1rem;
            }

            .cta-button {
                padding: 0.8rem;
                font-size: 0.9rem;
            }
        }

        /* Orders Section */
        .orders-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #D4AF37;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .order-number {
            display: flex;
            flex-direction: column;
        }

        .order-number-label {
            font-size: 0.85rem;
            color: #999;
            margin: 0;
        }

        .order-number-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
            margin: 0.25rem 0 0 0;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-ready {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .order-detail-item {
            display: flex;
            flex-direction: column;
        }

        .order-detail-label {
            font-size: 0.85rem;
            color: #999;
            margin: 0;
        }

        .order-detail-value {
            font-size: 0.95rem;
            color: #333;
            margin: 0.25rem 0 0 0;
        }

        .order-products {
            margin-bottom: 1rem;
        }

        .order-products-label {
            font-size: 0.85rem;
            color: #999;
            margin: 0 0 0.5rem 0;
        }

        .order-products-value {
            font-size: 0.95rem;
            color: #333;
            margin: 0;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .order-total {
            display: flex;
            flex-direction: column;
        }

        .order-total-label {
            font-size: 0.85rem;
            color: #999;
            margin: 0;
        }

        .order-total-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #D4AF37;
            margin: 0.25rem 0 0 0;
        }

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
            .order-header {
                flex-direction: column;
                gap: 1rem;
            }

            .order-details {
                grid-template-columns: 1fr;
            }

            .order-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
                boolval
            }

            .order-action-btn {
                width: 100%;
            }
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, rgba(61, 40, 23, 0.9) 0%, rgba(90, 58, 42, 0.9) 50%, rgba(42, 24, 16, 0.9) 100%);
            color: var(--cream);
            padding: 3rem 2rem;
            margin-top: 4rem;
            position: relative;
            z-index: 5;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .footer-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--accent);
        }

        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .contact-icon {
            font-size: 1.5rem;
            min-width: 30px;
            text-align: center;
        }

        .contact-details {
            flex: 1;
        }

        .contact-label {
            font-weight: 600;
            color: var(--accent);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .contact-value {
            color: var(--cream);
            line-height: 1.6;
            word-break: break-word;
        }

        .contact-value a {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .contact-value a:hover {
            color: #FFE4B5;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            transform: scale(1.05);
        }

        .footer-map {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            height: 300px;
        }

        .footer-map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 248, 220, 0.2);
            font-size: 0.9rem;
            color: rgba(255, 248, 220, 0.8);
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-map {
                height: 250px;
            }
        }

        /* Mobile menu toggle: hidden on desktop, shown on small screens */
        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.25rem;
            background: none;
            border: none;
            color: var(--cream);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .nav-menu { display: none; }

        @media (max-width: 768px) {
            body {
                padding-top: 80px; /* Kurangi padding di mobile */
            }
            
            header {
                padding: 0.6rem 4%;
            }
            
            .menu-toggle { display: block; }
            /* Hide the main desktop nav on small screens */
            nav { display: none; }
            /* Mobile nav styles */
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

            /* Status Badges */
            .status-badge {
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
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
        <path d="M10 60 Q50 20 100 40 Q150 60 190 50 Q150 80 100 70 Q50 60 10 80 Z" 
              fill="#8B5A3C" opacity="0.3"/>
        <path d="M20 60 L100 45 M40 65 L100 50 M60 68 L100 55" 
              stroke="#8B5A3C" stroke-width="1" opacity="0.2"/>
    </svg>

    <!-- Siluet Daun Pisang 2 -->
    <svg class="banana-leaf banana-leaf-2" viewBox="0 0 180 110" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 55 Q45 15 90 35 Q135 55 170 45 Q135 75 90 65 Q45 55 10 75 Z" 
              fill="#8B5A3C" opacity="0.3"/>
        <path d="M20 55 L90 42 M35 60 L90 47 M50 63 L90 52" 
              stroke="#8B5A3C" stroke-width="1" opacity="0.2"/>
    </svg>

    <!-- Header -->
    <header>
       <div class="logo">
    <img src="{{ asset('images/budess.jpg') }}" alt="Dapoer Budess" class="logo-img">
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
            <button class="message-btn" onclick="openMessageModal()">
                💬 Pesan
                <span class="cart-count" id="msgBadge" style="display: none;">!</span>
            </button>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="admin-btn">
                        ⚙️ Admin
                    </a>
                @endif
            @endauth
<!-- TOGGLE BUTTON (MOBILE ONLY) -->
<div class="menu-toggle" onclick="toggleMenu()">
    ☰
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
        
        <!-- SLIDE 1: PROMO UTAMA (Left Aligned) -->
        <div class="slide slide-1 active" style="background-image: url('{{ asset('images/hero/slide1.jpg') }}');">
            <div class="slide-content">
                <h1>Nikmatnya <br>Roti Manis Premium</h1>
                <p>Rasakan kelembutan roti yang dibuat dengan sepenuh hati. Topping keju dan coklat berlimpah yang lumer di mulut.</p>
                <a href="javascript:void(0)" onclick="showSection('products')" class="hero-btn">Pesan Sekarang</a>
            </div>
        </div>

        <!-- SLIDE 2: BRANDING (Center Aligned) -->
        <div class="slide slide-2"
     style="background-image: url('{{ asset('images/hero/slide2.jpg') }}');">
    <div class="slide-content">
        <h1 style="font-family: 'Playfair Display', serif; font-style: italic;">
            Dapoer Budess
        </h1>
        <p>
            Nikmati kelembutan roti artisan kelas dunia yang dibuat dengan resep rahasia.
            Setiap potongnya menjanjikan kelezatan yang tak terlupakan dan aroma yang menggugah selera.
        </p>
        <a href="javascript:void(0)" onclick="showSection('products')" class="hero-btn">
            Eksplorasi Menu
        </a>
    </div>
</div>


        <!-- SLIDE 3: KUALITAS (Right Aligned) -->
        <div class="slide slide-3" style="background-image: url('{{ asset('images/hero/slide3.jpg') }}');">
            <div class="slide-content">
                <h1>Dibuat Segar <br>Setiap Hari</h1>
                <p>Kami menjamin kesegaran setiap potong roti. Resep autentik dan proses pemanggangan sempurna.</p>
                <a href="javascript:void(0)" onclick="showSection('bestseller')" class="hero-btn">Cek Best Seller</a>
            </div>
        </div>

        <!-- NAVIGATION -->
        <button class="slider-nav prev-slide" onclick="moveSlide(-1)">&#10094;</button>
        <button class="slider-nav next-slide" onclick="moveSlide(1)">&#10095;</button>

        <!-- DOTS -->
        <div class="slider-dots">
            <div class="dot active" onclick="currentSlide(0)"></div>
            <div class="dot" onclick="currentSlide(1)"></div>
            <div class="dot" onclick="currentSlide(2)"></div>
        </div>

    </div>

    <!-- PROMO Section -->
    <!-- PROMO Section - Roti Sobek -->
    <section class="promo-section" id="promoBanner">
        <div class="promo-card">
            <div class="promo-content">
                <!-- Badges -->
                <div class="promo-badges">
                    <span class="promo-badge">🔥 SPESIAL</span>
                    <span class="discount-badge">HEMAT 20%</span>
                </div>
                
                <!-- Title -->
                <h2 class="promo-title">Roti Sobek Lezat!</h2>
                
                <!-- Subtitle -->
                <p class="promo-subtitle">Roti sobek premium dengan tekstur lembut dan rasa yang menggugah selera. Sempurna untuk sarapan atau camilan Anda!</p>
                
                <!-- Pricing -->
                <div class="promo-pricing">
                    <span class="price-original">Rp 7.000</span>
                    <span class="price-discount">Rp 28.000</span>
                    <span class="price-save">💰 Hemat Rp 2.400</span>
                </div>
                
                <!-- Images Grid -->
                <div class="promo-images">
                    <div class="promo-image-item">
                        <img src="{{ asset('images/besar1.jpg') }}" alt="Roti Sobek 1" onerror="this.src='https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&q=80'">
                    </div>
                    <div class="promo-image-item">
                        <img src="{{ asset('images/besar 2.jpg') }}" alt="Roti Sobek 2" onerror="this.src='https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&q=80'">
                    </div>
                    <div class="promo-image-item">
                        <img src="{{ asset('images/besar 3.jpg') }}" alt="Roti Sobek 3" onerror="this.src='https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400&q=80'">
                    </div>
                </div>
                
                <!-- Button -->
                <button class="promo-cta" onclick="showSection('products')">
                    🛒 PESAN SEKARANG
                </button>
            </div>
        </div>
    </section>

    <!-- Main Container -->
    <div class="container">
        <!-- Home Section -->
        <section id="home" class="section active">
            <!-- Why Choose Us Section - Reference Lookalike -->
            <div class="why-choose-section">
                <h2 class="section-title why-choose-title">Mengapa Memilih Roti Kami?</h2>
                
                <div class="zigzag-container">
                    
                    <!-- Row 1: Image Left, Card Right -->
                    <div class="zigzag-row" data-aos="fade-up">
                        <div class="zigzag-image">
                            <img src="{{ asset('images/panggang.jpg') }}" alt="Roti Fresh dari Oven">
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
                            <p class="card-desc">
                                Kami menggunakan bahan-bahan pilihan berkualitas tinggi dan dipanggang segar setiap hari 
                                dengan standar kebersihan yang ketat untuk menjaga rasa dan kelembutannya.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Row 2: Card Left, Image Right -->
                    <!-- Added 'reverse' class to trigger Right-side pointer CSS -->
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
                            <p class="card-desc">
                                Roti kami menawarkan cita rasa lezat dan segar dengan harga yang ramah di kantong. 
                                Cocok untuk sarapan, camilan, dan momen spesial Anda bersama keluarga.
                            </p>
                        </div>
                        
                        <div class="zigzag-image">
                            <!-- Image: Using rooti.jpg for variety -->
                            <img src="{{ asset('images/keluarga.jpg') }}" alt="Momen Menikmati Roti Bersama">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <!-- Automated Best Seller Section on Home -->
            <h2 class="section-title">🔥 Roti Paling Laris</h2>
            <div class="products-grid" id="bestsellerHome"></div>

            <div class="section-divider"></div>

            <!-- Reviews Section -->
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
                                    @for($i = 0; $i < $review->rating; $i++)
                                        ★
                                    @endfor
                                    <span style="color: #ddd;">
                                        @for($i = 0; $i < (5 - $review->rating); $i++)
                                            ★
                                        @endfor
                                    </span>
                                </div>
                            </div>
                            <p class="review-text">"{{ $review->comment ?? '' }}"</p>
                            
                            @if($review->media_urls)
                                <div style="display: flex; gap: 8px; margin-top: 15px; flex-wrap: wrap;">
                                    @foreach($review->media_urls as $url)
                                        <div style="width: 60px; height: 60px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" onclick="window.open('{{ asset($url) }}', '_blank')">
                                            <img src="{{ asset($url) }}" style="width: 100%; height: 100%; object-fit: cover;">
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
            
            <!-- Sort Controls -->
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
                        <div class="shipping-options" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label class="payment-option active" style="flex: 1; padding: 1rem; border: 2px solid var(--primary); border-radius: 8px; cursor: pointer; transition: all 0.3s; background: #fffcf5;" id="shipping-delivery">
                                <input type="radio" name="shipping_method" value="delivery" checked onclick="toggleAddressFields('delivery')">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">🚚 Dianter Penjual</span>
                                <span style="font-size: 0.85rem; color: #666;">Kami antar pesanan ke lokasi Anda</span>
                            </label>
                            <label class="payment-option" style="flex: 1; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" id="shipping-pickup">
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
                        <textarea rows="2" name="notes" placeholder="Contoh:Pencet Bel/Hubungi No. Telp/Catatan Lainnya"></textarea>
                    </div>

                    <div class="form-group">
                        <label style="margin-bottom: 1rem; display: block;">Metode Pembayaran *</label>
                        <div class="payment-options" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <label class="payment-option" style="flex: 1; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                                <input type="radio" name="payment_method" value="COD" checked onclick="selectPayment('COD')">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">🏠 COD (Bayar di Tempat)</span>
                                <span style="font-size: 0.85rem; color: #666;">Bayar tunai di tempat</span>
                            </label>
                            <label class="payment-option" id="qrisPaymentOption" style="flex: 1; padding: 1rem; border: 2px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s; display: none;">
                                <input type="radio" name="payment_method" value="QRIS" onclick="selectPayment('QRIS')">
                                <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">📱 QRIS (E-Wallet/Banking)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="checkout-btn">Proses Pesanan</button>
                    <!-- Hidden field fields for compatibility if needed, but we handle in JS -->
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

            <!-- Sample Order Card (untuk referensi) -->
            <div style="display: none;" class="sample-order-card">
                <div style="background: white; border-radius: 15px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 4px solid #D4AF37;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #999;">Nomor Pesanan</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 1.1rem; font-weight: 700; color: #333;">#ORD-2026-001</p>
                        </div>
                        <div style="background: #e8f5e9; color: #2e7d32; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            ✅ Siap Diambil di Toko
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #999;">Metode Pengambilan</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.95rem; color: #333;">🏪 Ambil Sendiri di Toko</p>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #999;">Tanggal Pesanan</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.95rem; color: #333;">19 Feb 2026</p>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #999;">Produk</p>
                        <p style="margin: 0; font-size: 0.95rem; color: #333;">Roti Sobek x2, Roti Manis x1</p>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #eee;">
                        <div>
                            <p style="margin: 0; font-size: 0.85rem; color: #999;">Total</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 1.2rem; font-weight: 700; color: #D4AF37;">Rp 28.800</p>
                        </div>
                        <button style="background: #8B4513; color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                            Hubungi Admin
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profile (Tentang Kami) Section -->
        <section id="profile" class="section">
            <div class="about-container">
                <div class="about-header" data-aos="fade-up">
                    <span class="about-script-title">Profile</span>
                    <h2 class="about-main-title">Dapoer Budess</h2>
                </div>

                <div class="about-grid">
                    <div class="about-images-wrapper" data-aos="fade-right">
                        <div class="about-img-frame about-img-large">
                            <img src="{{ asset('images/rocil.jpg') }}" alt="Proses Pembuatan Roti" class="about-img-large">
                        </div>
                    </div>

                    <div class="about-text-content" data-aos="fade-left">
                        <div class="about-description">
                            <p>
                                Berdiri sejak tahun 2014, Dapoer Budess hadir sebagai roti rumahan dengan cita rasa khas yang selalu dirindukan. Kami menghadirkan berbagai varian favorit seperti coklat, keju, coklat keju, sosis keju, pisang coklat, pisang keju, hingga pisang coklat keju — semuanya dibuat dengan bahan pilihan dan proses yang penuh ketelatenan.
                            <p style="margin-top: 1rem;">
                               Keunikan kami bukan hanya pada rasa, tetapi pada identitas yang melekat di setiap gigitan. Tanpa perlu melihat siapa yang menjualnya, pelanggan sudah tahu — ini pasti roti Dapoer Budess.
                               Dari aroma yang harum, tekstur yang lembut, hingga rasa yang khas dan konsisten, pelanggan dapat langsung mengenali siapa pembuatnya.
                            </p>
                            <p style="margin-top: 1rem;">
                                Dengan sentuhan hangat khas masakan rumah, Dapoer Budess ingin menjadi bagian dari momen indah Anda, baik sebagai teman sarapan pagi, camilan sore yang manis, maupun hantaran berharga untuk orang-orang tersayang.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="vision-mission-section" data-aos="fade-up">
                    <div class="vm-grid">
                        <div class="vision-box">
                            <h3 class="vm-title">Visi Kami</h3>
                            <p class="about-description">
                                Menjadi brand roti rumahan terpercaya yang dikenal karena cita rasa khas, kualitas terbaik, dan konsistensi rasa yang selalu membuat pelanggan kembali.
                            </p>
                        </div>
                        <div class="mission-box">
                            <h3 class="vm-title">Misi Kami</h3>
                            <ul class="mission-list">
                                <li class="mission-item">
                                    <div class="mission-number">1</div>
                                    <div class="mission-text">Menghadirkan roti berkualitas dengan bahan pilihan terbaik.</div>
                                </li>
                                <li class="mission-item">
                                    <div class="mission-number">2</div>
                                    <div class="mission-text">Menjaga konsistensi rasa dan tekstur di setiap produksi.</div>
                                </li>
                                <li class="mission-item">
                                    <div class="mission-number">3</div>
                                    <div class="mission-text">Memberikan harga yang terjangkau tanpa mengurangi kualitas.</div>
                                </li>
                                <li class="mission-item">
                                    <div class="mission-number">4</div>
                                    <div class="mission-text">Mengembangkan inovasi varian rasa sesuai selera pelanggan..</div>
                                </li>
                                <li class="mission-item">
                                    <div class="mission-number">5</div>
                                    <div class="mission-text">Menjadikan setiap produk sebagai simbol kehangatan dan kepuasan pelanggan.</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>



    <!-- Shopping Cart Modal -->
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

    <!-- Message Modal - Chat Interface -->
    <div class="message-modal" id="messageModal">
        <div class="message-modal-content">
            <button class="message-close-btn" onclick="closeMessageModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1rem;">💬 Chat dengan Admin</h2>
            
            <!-- Search/Login Section -->
            <div id="messageLoginSection" style="margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #666;">Masukkan nomor Whatsapp Anda untuk melihat pesan & status pesanan:</p>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <input type="tel" id="searchPhone" placeholder="08123456789" style="flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px;">
                    <button type="button" id="btnSearchChat" onclick="loadChatThread()" style="padding: 0.75rem 1rem; background: var(--accent); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; min-width: 80px;">Cari</button>
                </div>
                <div id="chatLoading" style="display: none; color: var(--primary); font-size: 0.9rem; margin-bottom: 0.5rem;">
                    ⏳ Menghubungkan...
                </div>
                <div id="chatSearchInfo" style="display: none; padding: 0.5rem; background: #fff3cd; color: #856404; font-size: 0.85rem; border-radius: 4px; margin-bottom: 1rem;"></div>
            </div>

            <!-- Order History Section -->
            <div id="orderHistorySection" style="display: none; margin-bottom: 1rem; max-height: 200px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 1rem; background: #fffcf5;">
                <h3 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.5rem; position: sticky; top: 0; background: #fffcf5; padding-bottom: 0.5rem; border-bottom: 1px solid #eee;">📦 Riwayat Pesanan</h3>
                <div id="orderHistoryList"></div>
            </div>

            <!-- Chat Thread -->
            <div id="chatThreadSection" style="display: none; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9; overflow: hidden; flex-direction: column; height: 400px;">
                <!-- Chat Header -->
                <div style="background: #fff; padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.9rem; color: #666;">Nomor: <span id="currentChatPhone" style="font-weight: bold;"></span></span>
                        <span id="autoRefreshIndicator" style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; color: #4CAF50; background: #e8f5e9; padding: 2px 8px; border-radius: 10px;">
                            <span style="animation: pulse 2s ease-in-out infinite;">●</span> Auto-refresh
                        </span>
                    </div>
                    <button type="button" onclick="logoutChat()" style="background: none; border: none; color: #d32f2f; font-size: 0.8rem; cursor: pointer; text-decoration: underline;">Ganti Nomor</button>
                </div>
                <!-- Chat Messages -->
                <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem; background: white;">
                </div>

                <!-- Chat Input -->
                <div style="border-top: 1px solid #eee; padding: 1rem; background: #f9f9f9;">
                    <form id="chatMessageForm" onsubmit="sendChatMessage(event)" style="display: flex; gap: 0.5rem;">
                        <textarea id="chatInput" placeholder="Ketik pesan Anda..." required style="flex: 1; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; resize: none; height: 60px;"></textarea>
                        <button type="submit" style="padding: 0.75rem 1rem; background: var(--primary); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; align-self: flex-end;">Kirim</button>
                    </form>
                </div>
            </div>

            <!-- First Message Form -->
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
        <div class="message-overlay" onclick="closeMessageModal()"></div>
    </div>

    <div class="success-message" id="successMessage">
        <div class="success-icon">✓</div>
        <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 0.3rem; font-size: 2rem; font-weight: 700;">Pesanan Berhasil!</h2>
        <p style="color: #999; font-size: 0.95rem; margin-bottom: 2rem;">Terima kasih telah berbelanja di Dapoer Budess</p>
        
        <div id="orderInfo" style="margin: 2rem 0;">
            <!-- Order Number Card -->
            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #e9ecef;">
                <p style="margin-bottom: 0.5rem; color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Nomor Pesanan</p>
                <p id="orderNumber" style="font-size: 1.4em; font-weight: 700; color: var(--primary); margin: 0; font-family: 'Courier New', monospace;"></p>
            </div>

            <!-- Status Card -->
            <div style="background: linear-gradient(135deg, #fffbf0 0%, #fff9f5 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #ffe8cc;">
                <p style="margin-bottom: 0.8rem; color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Status Pesanan</p>
                <div style="display: inline-block; background: linear-gradient(135deg, #020202ff 0%, #ff9800 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                    <span id="orderStatus">⏳ Menunggu Konfirmasi</span>
                </div>
            </div>

            <!-- Info Box -->
            <div style="background: #f0f9ff; padding: 1.2rem; border-radius: 12px; border-left: 4px solid #2196F3; text-align: left; margin-bottom: 2rem;">
                <p style="margin: 0.5rem 0; color: #555; font-size: 0.9rem;">
                    <strong style="color: #2196F3;">📱 Cek Status:</strong> Hubungi customer service melalui tombol chat atau cek nomor antrian Anda.
                </p>
                <p style="margin: 0.5rem 0; color: #555; font-size: 0.9rem;">
                    <strong style="color: #2196F3;">⏱️ Admin akan merespon pesanan Anda dalam beberapa menit.</strong>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 2rem;">
            <button class="checkout-btn" style="width: 100%; background: linear-gradient(135deg, #8B4513 0%, #6B3410 100%); color: white; border: none; padding: 1rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem;" onclick="closeSuccess()">← Kembali ke Beranda</button>
        </div>
    </div>

    <script>
        // Product Data
        const products = @json($products);

        let cart = [];
        let currentPhone = localStorage.getItem('customerPhone') || null;
        let isFetchingChat = false;

        function normalizePhone(phone) {
            if (!phone) return '';
            let normalized = phone.replace(/[^0-9]/g, '');
            if (normalized.startsWith('62')) {
                normalized = '0' + normalized.substring(2);
            } else if (!normalized.startsWith('0') && normalized.length > 0) {
                normalized = '0' + normalized;
            }
            return normalized;
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            // No cleanup needed for now
        });

        // Render Products
        function renderProducts(containerId, filterBestseller, sortOption = null) {
            const container = document.getElementById(containerId);
            let filteredProducts = filterBestseller ? products.filter(p => p.bestseller) : products;
            
            // Apply sorting if specified
            if (sortOption) {
                switch(sortOption) {
                    case 'bestseller':
                        filteredProducts = filteredProducts.sort((a, b) => b.total_sold - a.total_sold);
                        break;
                    case 'price-low':
                        filteredProducts = filteredProducts.sort((a, b) => a.effective_price - b.effective_price);
                        break;
                    case 'price-high':
                        filteredProducts = filteredProducts.sort((a, b) => b.effective_price - a.effective_price);
                        break;
                    case 'name-asc':
                        filteredProducts = filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                        break;
                    case 'name-desc':
                        filteredProducts = filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
                        break;
                    case 'newest':
                    default:
                        // Keep original order (newest first)
                        break;
                }
            } else if (filterBestseller) {
                // If it's a bestseller section, sort by total_sold descending
                filteredProducts = filteredProducts.sort((a, b) => b.total_sold - a.total_sold);
            }

            if (filteredProducts.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #888; font-style: italic;">Belum ada produk yang ditampilkan saat ini.</div>';
                return;
            }

            // Marketing taglines array
            const taglines = [
                "⚡ Promo terbatas!",
                "🔥 Stok menipis!",
                "✨ Fresh setiap hari!",
                "💯 Favorit pelanggan!",
                "🎉 Harga spesial hari ini!"
            ];

            container.innerHTML = filteredProducts.map(product => {
                // Get random tagline
                const randomTagline = taglines[Math.floor(Math.random() * taglines.length)];
                
                // Determine badge text
                let badgeText = "";
                if (product.is_discount_active) {
                    if (product.discount_type === 'percentage') {
                        badgeText = `DISKON ${Math.round(product.discount_value)}%`;
                    } else {
                        badgeText = "PROMO";
                    }
                } else if (product.bestseller) {
                    badgeText = "🔥 TERLARIS";
                }
                
                // Stock status styling
                const stockStatus = product.stock_status || {};
                const stockColors = {
                    'green': '#10b981',
                    'yellow': '#f59e0b',
                    'red': '#ef4444',
                    'orange': '#f97316'
                };
                const stockBgColors = {
                    'green': '#d1fae5',
                    'yellow': '#fef3c7',
                    'red': '#fee2e2',
                    'orange': '#ffedd5'
                };
                
                // Button configuration
                const buttonText = stockStatus.is_preorder ? '📅 Pre-Order untuk Besok' : '🛒 Beli';
                const buttonDisabled = !stockStatus.can_order;
                
                return `
                    <div class="product-card" data-category="${product.category}">
                        <!-- Promo Badge -->
                        ${badgeText ? `<div class="product-promo-badge">${badgeText}</div>` : ''}
                        
                        <!-- Product Image Wrapper (Circular) -->
                        <div class="product-image-wrapper">
                            <div class="product-image">
                                ${product.image ? 
                                    `<img src="${product.image}" alt="${product.name}" style="width:100%; height:100%; object-fit:cover;">` : 
                                    `<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:3rem;">🍞</div>`
                                }
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <p class="product-description">${product.description || 'Lembut, manis, and fresh setiap hari'}</p>
                            
                            <!-- Stock Status Badge -->
                            ${stockStatus.label ? `
                                <div style="margin: 0.75rem 0; padding: 0.5rem 0.75rem; background: ${stockBgColors[stockStatus.color]}; border-left: 3px solid ${stockColors[stockStatus.color]}; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; color: ${stockColors[stockStatus.color]}; text-align: center;">
                                    ${stockStatus.label}
                                </div>
                            ` : ''}
                            
                            <!-- Pricing -->
                            <div class="price-container">
                                ${product.is_discount_active ? 
                                    `<span class="price-old">Rp ${product.price.toLocaleString('id-ID')}</span>
                                     <span class="price-new">Rp ${product.effective_price.toLocaleString('id-ID')}</span>` :
                                    `<span class="price-new">Rp ${product.price.toLocaleString('id-ID')}</span>`
                                }
                            </div>
                            
                            <!-- Marketing Tagline -->
                            <p class="marketing-tagline">${randomTagline}</p>
                            
                            <!-- CTA Button -->
                            <button class="cta-button" onclick="addToCart(${product.id}, ${stockStatus.is_preorder})" ${buttonDisabled ? 'disabled' : ''}>
                                ${buttonText}
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Apply Sort Filter
        function applySortFilter() {
            const sortValue = document.getElementById('sortFilter').value;
            renderProducts('productsGrid', false, sortValue);
        }

        // Add to Cart
        function addToCart(productId, isPreorder = false) {
            const product = products.find(p => p.id === productId);
            
            // Check if product can be ordered
            if (!product.stock_status || !product.stock_status.can_order) {
                alert('Maaf, produk ini saat ini tidak tersedia untuk dipesan.');
                return;
            }
            
            // Store product info for modal
            window.selectedProduct = { 
                id: productId,
                name: product.name,
                price: product.effective_price,
                isPreorder: isPreorder
            };
            
            // Show purchase option modal
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
            showNotification(`${product.name} ditambahkan ke keranjang!`);
            closePurchaseModal();
        }

        function buyNow() {
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
            showSection('checkout');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Update Cart
        function updateCart() {
            const cartCount = document.getElementById('cartCount');
            const cartItems = document.getElementById('cartItems');
            const cartSummary = document.getElementById('cartSummary');
            
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            // Visual feedback animation
            cartCount.classList.add('pop');
            setTimeout(() => cartCount.classList.remove('pop'), 300);
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart"><div class="empty-cart-icon">🛒</div><p>Keranjang Anda masih kosong</p></div>';
                cartSummary.style.display = 'none';
            } else {
                cartItems.innerHTML = cart.map(item => {
                    return `
                    <div class="cart-item">
                        <div class="cart-item-image">
                            ${item.image ? `<img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover;">` : '🍞'}
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">
                                ${item.is_discounted ? 
                                    `<span class="text-red-600 font-bold">Rp ${item.price.toLocaleString('id-ID')}</span> <span class="text-xs text-gray-400 line-through">Rp ${item.original_price.toLocaleString('id-ID')}</span>` 
                                    : `Rp ${item.price.toLocaleString('id-ID')}`
                                }
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <span style="padding: 0 1rem;">${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                                <button class="remove-item" onclick="removeItem(${item.id})">Hapus</button>
                            </div>
                        </div>
                    </div>
                `}).join('');
                
                updateTotals();
                cartSummary.style.display = 'block';
            }
        }





        // Update Totals (Subtotal only)
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            
            // Note: shippingCost and discountRow elements might still be in the DOM
            // but we won't show them or we'll show them as 0/hidden.
            const shippingCostEl = document.getElementById('shippingCost');
            if (shippingCostEl) shippingCostEl.textContent = 'Rp 0';
            
            const discountRow = document.getElementById('discountRow');
            if(discountRow) discountRow.style.display = 'none';

            document.getElementById('total').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        }

        // Update Quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeItem(productId);
                } else {
                    updateCart();
                }
            }
        }

        // Remove Item
        function removeItem(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Toggle Cart
        function toggleCart() {
            const cartModal = document.getElementById('cartModal');
            const cartOverlay = document.getElementById('cartOverlay');
            cartModal.classList.toggle('active');
            cartOverlay.classList.toggle('active');
        }


        // === HERO SLIDER LOGIC ===
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        
        // Init slider
        function initSlider() {
            if(slides.length > 0) {
                showSlide(slideIndex);
                // Auto-play removed as per user request
            }
        }

        function showSlide(n) {
            // Loop functionality
            if (n >= slides.length) slideIndex = 0;
            if (n < 0) slideIndex = slides.length - 1;

            // Remove active classes
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Add active class to current
            slides[slideIndex].classList.add('active');
            dots[slideIndex].classList.add('active');
        }

        function moveSlide(n) {
            slideIndex += n;
            showSlide(slideIndex);
        }

        function currentSlide(n) {
            slideIndex = n;
            showSlide(slideIndex);
        }

        // Start Slider on Load (Manual Only)
        window.addEventListener('load', initSlider);

        // Show Section
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
            
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Highlight the corresponding nav link
            const navLink = document.querySelector(`.nav-links a[onclick*="${sectionId}"]`);
            if (navLink) {
                navLink.classList.add('active');
            }
            
            // Hide promo banner on checkout and profile sections
            const promoBanner = document.getElementById('promoBanner');
            if (promoBanner) {
                if (sectionId === 'checkout' || sectionId === 'profile') {
                    promoBanner.style.display = 'none';
                } else {
                    promoBanner.style.display = 'block';
                }
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Close mobile menu if open
            const navMenu = document.getElementById('navMenu');
            // Safety check in case navMenu is missing (though unlikely)
            if (navMenu && navMenu.classList.contains('open')) {
                navMenu.classList.remove('open');
            }
        }

        // Go to Checkout
        function goToCheckout() {
            if (cart.length === 0) {
                alert('Keranjang Anda masih kosong!');
                return;
            }
            toggleCart();
            showSection('checkout');
            
            // Auto-fill nomor WhatsApp jika sudah pernah tersimpan
            const savedPhone = localStorage.getItem('customerPhone');
            if (savedPhone && savedPhone.trim()) {
                const phoneInput = document.querySelector('input[name="customer_phone"]');
                if (phoneInput && !phoneInput.value) {
                    phoneInput.value = savedPhone;
                    console.log('[Checkout] Auto-filled phone number:', savedPhone);
                }
            }
        }

        // Select Payment Style
        function selectPayment(method) {
            document.querySelectorAll('.payment-options .payment-option').forEach(opt => {
                opt.style.borderColor = '#ddd';
                opt.style.backgroundColor = 'white';
            });
            event.currentTarget.parentElement.style.borderColor = 'var(--primary)';
            event.currentTarget.parentElement.style.backgroundColor = '#fffcf5';
        }

        // Toggle Address Fields based on Shipping Method
        function toggleAddressFields(method) {
            const addressSection = document.getElementById('addressSection');
            const pickupSection = document.getElementById('pickupSection');
            const deliveryLabel = document.getElementById('shipping-delivery');
            const pickupLabel = document.getElementById('shipping-pickup');
            const shippingInfoBox = document.getElementById('shippingInfoBox');
            const qrisPaymentOption = document.getElementById('qrisPaymentOption');
            
            // Toggle UI active states
            if (method === 'delivery') {
                deliveryLabel.classList.add('active');
                deliveryLabel.style.borderColor = 'var(--primary)';
                deliveryLabel.style.backgroundColor = '#fffcf5';
                pickupLabel.classList.remove('active');
                pickupLabel.style.borderColor = '#ddd';
                pickupLabel.style.backgroundColor = 'white';
                
                addressSection.style.display = 'block';
                pickupSection.style.display = 'none';
                if(shippingInfoBox) shippingInfoBox.style.display = 'block';
                
                // Hide QRIS for delivery
                qrisPaymentOption.style.display = 'none';
                document.querySelector('input[name="payment_method"][value="COD"]').checked = true;
                
                // Set required
                addressSection.querySelectorAll('input').forEach(input => {
                    if (input.name !== 'house_details') {
                        input.required = true;
                    }
                });
            } else {
                pickupLabel.classList.add('active');
                pickupLabel.style.borderColor = 'var(--primary)';
                pickupLabel.style.backgroundColor = '#fffcf5';
                deliveryLabel.classList.remove('active');
                deliveryLabel.style.borderColor = '#ddd';
                deliveryLabel.style.backgroundColor = 'white';
                
                addressSection.style.display = 'none';
                pickupSection.style.display = 'block';
                if(shippingInfoBox) shippingInfoBox.style.display = 'none';
                
                // Show QRIS for pickup
                qrisPaymentOption.style.display = 'flex';
                
                // Remove required
                addressSection.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
            }
        }

        // Handle Checkout Submit
        function handleCheckoutSubmit(event) {
            event.preventDefault();
            
            // Validasi stok sebelum checkout
            for (let item of cart) {
                const product = products.find(p => p.id === item.id);
                if (product && product.stock < item.quantity) {
                    alert(`Stok ${product.name} tidak cukup! Stok tersedia: ${product.stock}, Anda memesan: ${item.quantity}`);
                    return;
                }
            }
            
            const formData = new FormData(event.target);
            processCheckout(formData);
        }

        // Show/Hide QRIS
        // Process Checkout (Backend Call)
        function processCheckout(formData) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Prepare order items as a string because we're using FormData for file upload
            const items = cart.map(item => ({
                product_id: item.id,
                product_name: item.name,
                price: item.price,
                quantity: item.quantity,
            }));
            formData.append('items_json', JSON.stringify(items));
            
            // Send to backend
            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    // Jika ada error validasi atau server error
                    const errorMsg = data?.message || `Error ${response.status}: Terjadi kesalahan server`;
                    throw new Error(errorMsg);
                }

                return data;
            })
            .then(data => {
                if (data && data.success) {
                    // Update order info di success message
                    document.getElementById('orderNumber').textContent = data.order_number || '-';
                    
                    // Custom message for Payment Status
                    const statusElem = document.getElementById('orderStatus');
                    if (formData.get('payment_method') === 'QRIS') {
                         statusElem.innerHTML = 'Menunggu Konfirmasi Pembayaran (QRIS)';
                         statusElem.style.color = '#FFA500';
                    } else {
                         statusElem.innerHTML = 'Menunggu Konfirmasi Admin (COD)';
                         statusElem.style.color = 'var(--primary)';
                    }

                    // Save phone for chat session
                    const phone = normalizePhone(formData.get('customer_phone'));
                    console.log('[Checkout] Saving phone to localStorage:', phone);
                    
                    if (phone) {
                        localStorage.setItem('customerPhone', phone);
                        currentPhone = phone;
                        console.log('[Checkout] Phone saved successfully');
                    }

                    document.getElementById('successMessage').classList.add('active');
                    document.getElementById('cartOverlay').classList.add('active');
                    cart = [];
                    updateCart();
                    document.getElementById('checkoutForm').reset();
                } else {
                    alert('Gagal memproses pesanan: ' + (data?.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Checkout Error:', error);
                alert('Checkout Gagal: ' + error.message);
            });
        }

        // Close Success Message
        function closeSuccess() {
            document.getElementById('successMessage').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
            showSection('home');
        }

        // Open Chat with Admin
        function openChatWithAdmin() {
            closeSuccess();
            // Scroll to chat section
            const chatSection = document.querySelector('[data-section="pesan"]');
            if (chatSection) {
                chatSection.scrollIntoView({ behavior: 'smooth' });
            } else {
                // Fallback: scroll to top and show pesan section
                window.scrollTo({ top: 0, behavior: 'smooth' });
                showSection('pesan');
            }
        }

        // Show Notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: var(--primary);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                z-index: 3000;
                animation: slideInRight 0.4s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => notification.remove(), 400);
            }, 2000);
        }

        // Message Modal Functions
        function openMessageModal() {
            console.log('[Chat] Opening message modal');
            const messageModal = document.getElementById('messageModal');
            messageModal.classList.add('active');
            
            // Auto-login Check
            const savedPhone = localStorage.getItem('customerPhone');
            if (savedPhone && savedPhone.trim()) {
                console.log('[Chat] Auto-login with:', savedPhone);
                currentPhone = savedPhone;
                loadChatThread(savedPhone);
                // Pastikan polling dimulai
                setTimeout(() => {
                    if (currentPhone && !messagePollingInterval) {
                        console.log('[Chat] Force starting polling after modal open');
                        startMessagePolling();
                    }
                }, 1000);
            } else {
                console.log('[Chat] No saved phone - showing login form');
                resetMessageModal();
            }
        }

        function logoutChat() {
            if(confirm('Apakah Anda yakin ingin mengganti nomor?')) {
                localStorage.removeItem('customerPhone');
                currentPhone = null;
                stopMessagePolling();
                resetMessageModal();
            }
        }

        function closeMessageModal() {
            console.log('[Chat] Closing message modal');
            const messageModal = document.getElementById('messageModal');
            messageModal.classList.remove('active');
            // Jangan stop polling, biarkan tetap berjalan untuk badge notification
            // stopMessagePolling();
        }

        function resetMessageModal() {
            console.log('[Chat] Resetting modal to phone search view');
            document.getElementById('messageLoginSection').style.display = 'block';
            document.getElementById('chatThreadSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'none';
            document.getElementById('searchPhone').value = '';
            document.getElementById('senderPhone').value = '';
            document.getElementById('senderName').value = '';
            document.getElementById('senderMessage').value = '';
        }

        async function loadChatThread(manualPhone = null) {
            if (isFetchingChat) return;
            let phone = manualPhone || document.getElementById('searchPhone').value;
            
            const loading = document.getElementById('chatLoading');
            const searchBtn = document.getElementById('btnSearchChat') || document.querySelector('#messageLoginSection button');
            const infoBox = document.getElementById('chatSearchInfo');
            
            if (!phone || phone.trim() === "") {
                const savedPhone = localStorage.getItem('customerPhone');
                if (savedPhone) phone = savedPhone;
                else return;
            }

            phone = normalizePhone(phone);
            isFetchingChat = true;
            if(loading) loading.style.display = 'block';
            if(searchBtn) {
                searchBtn.disabled = true;
                if(!searchBtn.originalText) searchBtn.originalText = searchBtn.textContent;
                searchBtn.textContent = '...';
            }
            if(infoBox) infoBox.style.display = 'none';
            
            try {
                console.log('[Chat] Fetching status for:', phone);
                const response = await fetch(`/order-status/${encodeURIComponent(phone)}`);
                
                if (response.status === 404) {
                    if(infoBox) {
                        infoBox.textContent = "Nomor belum terdaftar. Silakan isi form di bawah untuk mulai chat.";
                        infoBox.style.display = 'block';
                    }
                    setTimeout(() => {
                        showFirstMessageForm(phone);
                        if(loading) loading.style.display = 'none';
                        if(searchBtn) {
                            searchBtn.disabled = false;
                            searchBtn.textContent = searchBtn.originalText || 'Cari';
                        }
                        isFetchingChat = false;
                    }, 500);
                    return;
                }
                
                const rawText = await response.text();
                let data;
                try {
                    data = JSON.parse(rawText);
                } catch (e) {
                    console.error('[Chat] JSON parse failed, trying to fix raw text...', e);
                    // Try to find the last valid JSON ending
                    const lastBrace = rawText.lastIndexOf('}');
                    if (lastBrace !== -1) {
                        try {
                            data = JSON.parse(rawText.substring(0, lastBrace + 1));
                            console.log('[Chat] Successfully recovered JSON by truncating junk at the end.');
                        } catch (e2) {
                            throw new Error('Truly invalid JSON');
                        }
                    } else {
                        throw new Error('No JSON object found in response');
                    }
                }

                if (data.success) {
                    currentPhone = phone;
                    localStorage.setItem('customerPhone', phone);
                    const phoneDisplay = document.getElementById('currentChatPhone');
                    if(phoneDisplay) phoneDisplay.textContent = phone;
                    
                    document.getElementById('messageLoginSection').style.display = 'none';
                    document.getElementById('firstMessageSection').style.display = 'none';

                    fetch('/messages/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ phone: phone })
                    }).catch(e => console.warn(e));

                    displayStatusAndChat(data);
                    startMessagePolling();
                } else {
                    showFirstMessageForm(phone);
                }
            } catch (error) {
                console.error('[Chat] Error loading thread:', error);
                if(infoBox) {
                    infoBox.textContent = "Gagal memuat chat. Pastikan koneksi internet aktif.";
                    infoBox.style.display = 'block';
                    infoBox.style.background = '#f8d7da';
                    infoBox.style.color = '#721c24';
                }
            } finally {
                isFetchingChat = false;
                if (loading) loading.style.display = 'none';
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.textContent = searchBtn.originalText || 'Cari';
                }
            }
        }

        function displayStatusAndChat(data) {
            document.getElementById('messageLoginSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'none';
            document.getElementById('chatThreadSection').style.display = 'flex';
            
            // Render Order History
            const orderSection = document.getElementById('orderHistorySection');
            const orderList = document.getElementById('orderHistoryList');
            
            if (data.orders && data.orders.length > 0) {
                orderSection.style.display = 'block';
                orderList.innerHTML = data.orders.map(order => {
                    let actionButtons = '';
                    
                    // Logic untuk tombol Scan QR (QRIS Pickup)
                    if (order.payment_method === 'QRIS' && order.shipping_method === 'pickup' && !order.payment_proof && order.status !== 'cancelled' && order.status !== 'completed' && order.payment_status !== 'paid') {
                        actionButtons += `
                            <button onclick="openUploadModal('${order.id}')" style="background: var(--primary); color: white; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                📱 Scan QR Code
                            </button>
                        `;
                    }

                    return `
                    <div style="padding: 1rem; border-bottom: 1px solid #eee; margin-bottom: 0.5rem; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <strong style="color: var(--primary); font-size: 1.1rem;">${order.order_number}</strong>
                            <span class="status-badge status-${order.status}">${order.status_label || order.status}</span>
                        </div>
                        <div style="font-size: 0.95rem; color: #444; margin-bottom: 0.75rem; line-height: 1.4;">
                            ${order.items.map(i => `<div style="display: flex; justify-content: space-between;"><span>${i.quantity}x ${i.product_name}</span> <span>Rp ${i.price}</span></div>`).join('')}
                        </div>
                        
                        ${order.estimated_delivery_date ? `
                        <div style="background: #e1f5fe; border: 1px solid #b3e5fc; padding: 0.75rem; border-radius: 8px; margin: 0.75rem 0; color: #01579b;">
                            <div style="font-weight: bold; font-size: 0.85rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 4px;">🚚 Estimasi Pengantaran:</div>
                            <div style="font-size: 0.9rem;">
                                📅 <strong>${order.estimated_delivery_date}</strong><br>
                                ⏰ Jam <strong>${order.estimated_delivery_time} WIB</strong>
                            </div>
                        </div>
                         ` : ''}

                        <div style="border-top: 1px dashed #ddd; margin: 0.5rem 0; padding-top: 0.5rem; font-size: 0.85rem; color: #666;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                <span>Subtotal:</span>
                                <span>Rp ${order.subtotal}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem; font-weight: 600; color: #d32f2f;">
                                <span>Ongkos Kirim:</span>
                                <span>
                                    ${order.shipping_cost === '0' && order.status !== 'delivered' && order.status !== 'cancelled' ? 
                                        '<i style="font-weight: 400; color: #080707ff; font-size: 0.8rem;">(Menunggu konfirmasi admin)</i>' : 
                                        `+ Rp ${order.shipping_cost}`}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 1rem; font-weight: bold; color: #000; margin-top: 0.25rem;">
                                <span>Total Tagihan:</span>
                                <span>Rp ${order.total_amount}</span>
                            </div>
                        </div>

                        ${order.payment_method === 'QRIS' && order.shipping_method === 'pickup' && order.payment_status !== 'paid' && order.status !== 'cancelled' ? 
                           `<div style="background: #fff3cd; color: #856404; padding: 0.5rem; border-radius: 4px; font-size: 0.85rem; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                <span>⚠️ Scan QR Code saat tiba di Dapoer Budess</span>
                            </div>` : ''
                        }

                        <div style="font-size: 0.85rem; color: #888; display: flex; justify-content: space-between; align-items: center; margin-top: 0.75rem;">
                            <span>${order.created_at}</span>
                            <div style="display: flex; gap: 0.5rem;">
                                ${actionButtons}
                                ${(order.status === 'delivered' || order.status === 'completed') && !order.has_reviewed ? 
                                    `<button onclick="openReviewModal('${order.id}')" style="background: var(--accent); color: white; border: none; padding: 4px 12px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">★ Beri Ulasan</button>` 
                                    : ''}
                                ${order.has_reviewed ? '<span style="color: #4CAF50; font-size: 0.85rem; font-weight: 600;">✓ Diulas</span>' : ''}
                            </div>
                        </div>
                    </div>
                `;}).join('');
            } else {
                orderSection.style.display = 'none';
            }

            // Render Messages
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';
            
            // Handle both notifications (new API) and messages (old API fallback)
            const msgs = data.notifications || data.messages || [];

            msgs.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.style.display = 'flex';
                // Adjust for data structure difference: msg.sender (new) vs msg.sender_type (old)
                const sender = msg.sender || msg.sender_type;
                messageDiv.style.justifyContent = sender === 'user' ? 'flex-end' : 'flex-start';
                
                const bubble = document.createElement('div');
                bubble.style.maxWidth = '70%';
                bubble.style.padding = '0.75rem 1rem';
                bubble.style.borderRadius = '8px';
                bubble.style.wordWrap = 'break-word';
                
                if (sender === 'user') {
                    bubble.style.background = '#e3f2fd';
                    bubble.style.color = '#1565c0';
                } else {
                    bubble.style.background = '#f3e5f5';
                    bubble.style.color = '#6a1b9a';
                }

                const timeStr = msg.created_at_formatted || msg.created_at; // Support both
                const messageText = msg.message;
                
                let statusHtml = '';
                if (sender === 'user') {
                    // Checkmark Logic
                    // Single tick (gray) = Sent
                    // Double tick (bright blue) = Read
                    if (msg.is_read) {
                         // Double check
                         statusHtml = `<span style="margin-left: 6px; font-size: 0.85rem; color: #00bcd4; font-weight: bold; letter-spacing: -3px;">✓✓</span>`;
                    } else {
                         // Single check
                         statusHtml = `<span style="margin-left: 6px; font-size: 0.85rem; color: #999;">✓</span>`;
                    }
                }

                bubble.innerHTML = `
                    <div>${messageText}</div>
                    <div style="font-size: 0.75rem; opacity: 0.7; margin-top: 0.25rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>${timeStr}</span>
                        ${statusHtml}
                    </div>
                `;
                
                messageDiv.appendChild(bubble);
                chatMessages.appendChild(messageDiv);
            });

            // Scroll to bottom
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
            
            // Inisialisasi counter pesan untuk tracking pesan baru
            if (lastMessageCount === 0) {
                lastMessageCount = msgs.length;
            }
        }

        function showFirstMessageForm(phone = '') {
            document.getElementById('messageLoginSection').style.display = 'none';
            document.getElementById('chatThreadSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'block';
            if (phone) {
                document.getElementById('senderPhone').value = phone;
            }
        }

        function showLoginSection() {
            document.getElementById('messageLoginSection').style.display = 'block';
            document.getElementById('chatThreadSection').style.display = 'none';
            document.getElementById('firstMessageSection').style.display = 'none';
        }

        function sendMessage(event) {
            event.preventDefault();
            
            const phone = normalizePhone(document.getElementById('senderPhone').value);
            const name = document.getElementById('senderName').value;
            const message = document.getElementById('senderMessage').value;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Send to backend
            fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Pesan berhasil dikirim!');
                    localStorage.setItem('customerPhone', phone);
                    currentPhone = phone;
                    document.getElementById('messageForm').reset();
                    loadChatThread(phone); // Reload chat
                } else {
                    showNotification('Gagal mengirim pesan, coba lagi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengirim pesan');
            });
        }

        async function sendChatMessage(event) {
            event.preventDefault();

            const phone = normalizePhone(currentPhone || document.getElementById('searchPhone').value);
            const message = document.getElementById('chatInput').value.trim();

            if (!phone || !message) {
                alert('Pesan tidak boleh kosong');
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: 'User', // Backend will use existing thread name
                        phone: phone,
                        message: message
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('chatInput').value = '';
                    
                    // Langsung refresh chat tanpa reload penuh
                    console.log('[Chat] Message sent, refreshing chat...');
                    await checkNewMessages();
                    
                    // Auto scroll to bottom
                    setTimeout(() => {
                        const chatBox = document.getElementById('chatMessages');
                        if (chatBox) {
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                    }, 200);
                    
                    // Pastikan polling tetap berjalan
                    if (!messagePollingInterval) {
                        console.log('[Chat] Restarting polling after send');
                        startMessagePolling();
                    }
                } else {
                    alert('Gagal mengirim pesan: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('[Chat] Error sending message:', error);
                alert('Gagal mengirim pesan');
            }
        }

        // Review Functions
        function closeReviewModal() {
            document.getElementById('reviewModal').classList.remove('active');
            document.getElementById('reviewOverlay').classList.remove('active');
        }

        function resetReviewForm() {
            document.getElementById('reviewForm').reset();
            document.getElementById('mediaPreview').innerHTML = '';
            setRating(5);
        }

        // Star Rating Logic
        function initStarRating() {
            const starRatingContainer = document.getElementById('starRating');
            if (!starRatingContainer) return;
            
            const stars = starRatingContainer.querySelectorAll('.star');
            
            stars.forEach(star => {
                star.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    document.getElementById('ratingValue').value = value;
                    highlightStars(value);
                });
                
                star.addEventListener('mouseover', function() {
                    const value = this.getAttribute('data-value');
                    highlightStars(value);
                });
            });
            
            starRatingContainer.addEventListener('mouseleave', function() {
                const value = document.getElementById('ratingValue').value;
                highlightStars(value);
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initStarRating);
        
        // Re-initialize when modal opens
        function openReviewModal(orderId) {
            document.getElementById('reviewOrderId').value = orderId;
            document.getElementById('reviewModal').classList.add('active');
            document.getElementById('reviewOverlay').classList.add('active');
            resetReviewForm();
            setTimeout(initStarRating, 100);
        }

        function setRating(value) {
            document.getElementById('ratingValue').value = value;
            highlightStars(value);
        }

        function highlightStars(value) {
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= parseInt(value)) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Media Preview
        function previewMedia() {
            const input = document.getElementById('mediaInput');
            const preview = document.getElementById('mediaPreview');

        }

        // Payment Proof Upload Functions
        function openUploadModal(orderId) {
            document.getElementById('uploadOrderId').value = orderId;
            document.getElementById('uploadModal').classList.add('active');
            document.getElementById('uploadOverlay').classList.add('active');
            // Reset form
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
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function submitPaymentProof(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Show loading state
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = 'Mengupload...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/upload-payment-proof', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message);
                    closeUploadModal();
                    loadChatThread(); // Reload order history
                } else {
                    alert(data.message || 'Gagal mengupload bukti pembayaran');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload bukti');
            } finally {
                btn.textContent = originalText;
                btn.disabled = false;
            }
        }

        // Media Preview
        function previewMedia() {
            const input = document.getElementById('mediaInput');
            const preview = document.getElementById('mediaPreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-item');
                        preview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Submit Review
        async function submitReview(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const phone = localStorage.getItem('customerPhone');
            
            if (!phone) {
                alert('Sesi kadaluarsa, silakan cek status pesanan lagi.');
                return;
            }
            
            formData.append('phone', phone);

            try {
                const response = await fetch('/reviews', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message);
                    closeReviewModal();
                    // Reload page to show the new review (since we use Server Side Rendering)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Gagal mengirim ulasan.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim ulasan.');
            }
        }
        // Reviews are now loaded via Blade (Server Side)
        // loadReviews function removed

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderProducts('productsGrid', false);
            renderProducts('bestsellerGrid', true);
            renderProducts('bestsellerHome', true);

            // Start Polling for messages
            const savedPhone = localStorage.getItem('customerPhone');
            if (savedPhone) {
                currentPhone = savedPhone;
                startMessagePolling();
            }
        });

        // Polling System
        let messagePollingInterval;

        let lastMessageCount = 0; // Track jumlah pesan terakhir

        function startMessagePolling() {
            console.log('[Polling] Starting message polling for phone:', currentPhone);
            if (messagePollingInterval) {
                console.log('[Polling] Clearing existing interval');
                clearInterval(messagePollingInterval);
            }
            checkNewMessages(); // Check immediately
            messagePollingInterval = setInterval(() => {
                console.log('[Polling] Checking for new messages...');
                checkNewMessages();
            }, 3000); // Check every 3 seconds
            console.log('[Polling] Interval set, checking every 3 seconds');
        }

        function stopMessagePolling() {
            console.log('[Polling] Stopping message polling');
            if (messagePollingInterval) clearInterval(messagePollingInterval);
            lastMessageCount = 0;
        }

        async function checkNewMessages() {
            if (!currentPhone) {
                console.log('[Polling] No currentPhone, skipping check');
                return;
            }

            console.log('[Polling] Checking messages for:', currentPhone);

            try {
                // Check if Chat Modal is OPEN and Thread is Visible
                const messageModal = document.getElementById('messageModal');
                const chatThreadSection = document.getElementById('chatThreadSection');
                const isChatOpen = messageModal && messageModal.classList.contains('active') && 
                                   chatThreadSection && chatThreadSection.style.display !== 'none';

                console.log('[Polling] Chat open status:', isChatOpen);

                if (isChatOpen) {
                     console.log('[Polling] Fetching new messages...');
                     // Check for new content
                     const response = await fetch(`/order-status/${encodeURIComponent(currentPhone)}`);
                     if (response.ok) {
                         const raw = await response.text();
                         try {
                             const data = JSON.parse(raw);
                             
                             // Hitung jumlah pesan baru
                             const msgs = data.notifications || data.messages || [];
                             const currentMessageCount = msgs.length;
                             const hasNewMessages = currentMessageCount > lastMessageCount && lastMessageCount > 0;
                             
                             console.log('[Polling] Message count - Previous:', lastMessageCount, 'Current:', currentMessageCount, 'Has new:', hasNewMessages);
                             
                             // Simpan scroll position sebelum update
                             const chatContainer = document.getElementById('chatMessages');
                             const wasAtBottom = chatContainer ? 
                                 (chatContainer.scrollHeight - chatContainer.scrollTop <= chatContainer.clientHeight + 100) : true;
                             
                             displayStatusAndChat(data);
                             
                             // Update counter
                             lastMessageCount = currentMessageCount;
                             
                             // Auto-scroll jika ada pesan baru atau user sudah di bawah
                             if (chatContainer && (hasNewMessages || wasAtBottom)) {
                                 setTimeout(() => {
                                     chatContainer.scrollTop = chatContainer.scrollHeight;
                                     console.log('[Polling] Auto-scrolled to bottom');
                                 }, 100);
                             }
                             
                             // Tampilkan notifikasi visual jika ada pesan baru
                             if (hasNewMessages) {
                                 console.log('[Polling] New message detected! Showing notification');
                                 showNewMessageNotification();
                             }
                             
                         } catch (e) {
                             console.error('[Polling] JSON parse error:', e);
                             const lastBrace = raw.lastIndexOf('}');
                             if (lastBrace !== -1) {
                                 try {
                                     const data = JSON.parse(raw.substring(0, lastBrace + 1));
                                     displayStatusAndChat(data);
                                     const msgs = data.notifications || data.messages || [];
                                     lastMessageCount = msgs.length;
                                 } catch (e2) {
                                     console.error('[Polling] Recovery failed:', e2);
                                 }
                             }
                         }
                     } else {
                         console.error('[Polling] Response not OK:', response.status);
                     }
                     
                     // Mark as read immediately
                     await fetch('/messages/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ phone: currentPhone })
                     });
                     
                     document.getElementById('msgBadge').style.display = 'none';

                } else {
                    console.log('[Polling] Chat closed, checking unread badge');
                    // Chat is closed (or in login/form view), check for unread count for BADGE
                    const response = await fetch(`/messages/unread/${encodeURIComponent(currentPhone)}`);
                    const data = await response.json();
                    
                     const badge = document.getElementById('msgBadge');
                     if (badge) {
                         if (data.unread_count > 0) {
                             badge.style.display = 'flex';
                             badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                             console.log('[Polling] Unread count:', data.unread_count);
                         } else {
                             badge.style.display = 'none';
                         }
                     }
                }
            } catch (error) {
                console.error('[Polling] Error:', error);
            }
        }

        // Notifikasi visual untuk pesan baru
        function showNewMessageNotification() {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 20px;
                background: linear-gradient(135deg, #4CAF50, #45a049);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
                z-index: 10001;
                font-family: 'Outfit', sans-serif;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                animation: slideInRight 0.3s ease-out;
            `;
            notification.innerHTML = '💬 Pesan baru diterima!';
            
            document.body.appendChild(notification);
            
            // Hapus setelah 3 detik
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Tambahkan CSS animation untuk notifikasi dan pulse
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                    transform: scale(1);
                }
                50% {
                    opacity: 0.5;
                    transform: scale(0.8);
                }
            }
        `;
        document.head.appendChild(style);
        
        // Sticky Navbar with Scroll Effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const nav = document.querySelector('nav');
            const scrollPosition = window.scrollY;
            
            if (scrollPosition > 50) {
                if (header) header.classList.add('scrolled');
                if (nav) nav.classList.add('scrolled');
            } else {
                if (header) header.classList.remove('scrolled');
                if (nav) nav.classList.remove('scrolled');
            }
        });
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <!-- Contact Info -->
            <div class="footer-info">
                <h3>📍 Dapoer Budess Bakery</h3>
                <p style="margin-bottom: 1.5rem; color: rgba(255, 248, 220, 0.9);">Toko roti premium dengan bahan pilihan berkualitas tinggi dan harga terjangkau.</p>
                
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
                            <div class="contact-value">Senin - Minggu: 07:00 - 13:00 WIB<br>Libur pada hari raya nasional</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-details">
                            <div class="contact-label">Instagram</div>
                            <div class="contact-value">
                                <a href="https://www.instagram.com/dapoer_budess?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" style="color: #FFD700; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color: #FFD700;">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1 1 12.324 0 6.162 6.162 0 0 1-12.324 0zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm4.965-10.322a1.44 1.44 0 1 1 2.881.001 1.44 1.44 0 0 1-2.881-.001z"/>
                                    </svg>
                                    @dapoer_budess
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="footer-map">
               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253666.18359730975!2d106.60825187562773!3d-6.58032243989468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x611ecf1a7e8f91ab%3A0x3354703eaba33357!2sRoti%20Panggang%20Dapoer%20Budess!5e0!3m2!1sen!2sus!4v1770439312098!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Dapoer Budess Bakery. Semua hak dilindungi. | Premium Quality & Fresh Every Day</p>
        </div>
    </footer>

    <!-- QRIS Modal -->
    <!-- Purchase Modal -->
    <div class="message-modal" id="purchaseModal">
        <div class="message-modal-content" style="max-width: 400px; text-align: center;">
            <button class="message-close-btn" onclick="closePurchaseModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1rem; font-size: 1.5rem;">Pilih Opsi Pembelian</h2>
            
            <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <p style="color: #666; margin: 0; font-size: 0.95rem;">Apa yang ingin Anda lakukan?</p>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <button onclick="addToCartOnly()" style="background: #f0f0f0; color: #333; border: 2px solid #ddd; padding: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s;">
                    🛒 Masukkan Keranjang
                </button>
                <button onclick="buyNow()" style="background: var(--primary); color: white; border: none; padding: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s;">
                    ⚡ Beli Sekarang
                </button>
            </div>
        </div>
    </div>
    <div class="message-overlay" id="purchaseOverlay" onclick="closePurchaseModal()"></div>

    <!-- Upload Modal -->
    <div class="message-modal" id="uploadModal">
        <div class="message-modal-content" style="max-width: 500px;">
            <button class="message-close-btn" onclick="closeUploadModal()">×</button>
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary); margin-bottom: 1rem;">Scan QR Code Pembayaran</h2>
            <p style="color: #666; margin-bottom: 1rem; font-size: 0.9rem;">Silakan scan QR code di Dapoer Budess untuk menyelesaikan pembayaran QRIS</p>
            
            <form id="uploadProofForm" onsubmit="submitPaymentProof(event)">
                <input type="hidden" id="uploadOrderId" name="order_id">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #555; margin-bottom: 0.5rem;">📸 Foto Bukti Pembayaran (Opsional)</label>
                    <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s;" onclick="document.getElementById('proofInput').click()">
                        <div id="proofPreviewPlaceholder" style="color: #999;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📸</div>
                            <p style="margin: 0; font-size: 0.9rem;">Klik untuk upload foto</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem; color: #bbb;">JPG, PNG (Max 2MB)</p>
                        </div>
                        <img id="proofPreviewImg" style="max-width: 100%; max-height: 300px; display: none; border-radius: 6px;">
                    </div>
                    <input type="file" id="proofInput" name="payment_proof" accept="image/*" style="display: none;" onchange="previewProof()">
                </div>
                
                <button type="submit" class="submit-msg-btn" style="width: 100%; margin-bottom: 0.5rem;">Konfirmasi Pembayaran</button>
                <button type="button" onclick="closeUploadModal()" style="width: 100%; background: #f0f0f0; color: #666; border: none; padding: 0.75rem; border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
            </form>
        </div>
    </div>
    <div class="message-overlay" id="uploadOverlay" onclick="closeUploadModal()"></div>

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
                    <input type="text" name="display_name" placeholder="Nama yang ingin ditampilkan (Boleh pakai inisial)" style="width: 100%; padding: 0.75rem; border: 2px solid #ddd; border-radius: 8px;">
                </div>

                <div class="form-group">
                    <label>Ulasan Anda</label>
                    <textarea name="comment" rows="4" placeholder="Ceritakan pengalaman Anda menikmati roti kami..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Foto/Video (Opsional)</label>
                    <div class="file-upload-container">
                        <input type="file" name="media[]" id="mediaInput" multiple accept="image/*,video/*" onchange="previewMedia()">
                        <label for="mediaInput" class="file-upload-label">
                            <span>📷 Tambah Foto/Video</span>
                        </label>
                    </div>
                    <div id="mediaPreview" class="media-preview-grid"></div>
                </div>

                <button type="submit" class="submit-msg-btn">Kirim Ulasan</button>
            </form>
        </div>
    </div>
    <div id="reviewOverlay" class="message-overlay" onclick="closeReviewModal()"></div>

    <script>
        // Toggle mobile nav menu
        function toggleMenu() {
            const m = document.getElementById('navMenu');
            if (!m) return;
            m.classList.toggle('open');
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderProducts('productsGrid', false);
            renderProducts('bestsellerGrid', true);
            renderProducts('bestsellerHome', true);
            startMessagePolling();

            // Add event listeners for dynamic shipping calculation
            const cityInput = document.querySelector('input[name="city"]');
            if (cityInput) {
                cityInput.addEventListener('input', updateTotals);
            }
            const paymentInputs = document.querySelectorAll('input[name="payment_method"]');
            paymentInputs.forEach(input => {
                input.addEventListener('change', updateTotals);
            });

            // Safety: Escape key closes all overlays
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    // Close Cart
                    const cartModal = document.getElementById('cartModal');
                    const cartOverlay = document.getElementById('cartOverlay');
                    if (cartModal) cartModal.classList.remove('active');
                    if (cartOverlay) cartOverlay.classList.remove('active');

                    // Close Message Modal
                    closeMessageModal();
                    
                    // Close Review Modal
                    closeReviewModal();
                    
                    // Close QRIS Modal
                    closeQrisModal();
                    
                    // Close Upload Modal
                    closeUploadModal();

                    // Close Success Message
                    document.getElementById('successMessage').classList.remove('active');
                    
                    console.log('Safety: All modals closed via Escape key');
                }
            });

            // Chat Input Enter Key
            const chatInput = document.getElementById('chatInput');
            if (chatInput) {
                chatInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendChatMessage(new Event('submit'));
                    }
                });
            }
        });
    </script>


<script>
        // Emergency Fix Button Logic
        const emergencyBtn = document.getElementById('emergency-fix-btn');
        if (emergencyBtn) {
            emergencyBtn.addEventListener('click', function() {
                // 1. Force hide all modals/overlays
                const blockers = [
                    'cartModal', 'cartOverlay', 
                    'messageModal', 'messageOverlay',
                    'successMessage', 'reviewModal', 'reviewOverlay',
                    'qrisModal', 'qrisOverlay', 'uploadModal', 'uploadOverlay'
                ];
                blockers.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.remove('active');
                        el.style.display = 'none';
                    }
                });

                // 2. Clear any potential blocking styles
                document.body.style.pointerEvents = 'auto';
                document.body.style.overflow = 'auto';
                document.documentElement.style.pointerEvents = 'auto';
                
                // 3. Ensure home section is shown
                if (typeof showSection === 'function') {
                    showSection('home');
                } else {
                    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                    const home = document.getElementById('home');
                    if (home) home.classList.add('active');
                }

                // 4. Notification
                alert('Interaksi telah di-reset! Silakan coba lagi.');
                document.getElementById('emergency-fix-container').style.display = 'none';
            });
        }

        // AUTO-FIX on load: ensure nothing is blocking initially
        setTimeout(() => {
            document.body.style.pointerEvents = 'auto';
            document.documentElement.style.pointerEvents = 'auto';
            console.log('Interaction safety check complete');
        }, 500);
    </script>
</body>
</html> 