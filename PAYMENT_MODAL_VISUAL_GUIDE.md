# Payment Modal - Visual Design Guide

## 🎨 New Fintech Design (Current)

### Color Scheme
```
Header Background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%)
                   (Chocolate → Saddle Brown)

QR Section: linear-gradient(135deg, #FFF8DC 0%, #FFFAF0 100%)
           (Cornsilk → Floral White)
           Border: #F4A460 (Sandy Brown)

Upload Area: linear-gradient(135deg, #FFFAF0 0%, #FFF8DC 100%)
            Border: #D2691E dashed (Chocolate)

Status Badge: #FFF3CD background (Light Yellow)
             #856404 text (Dark Yellow)

Buttons:
- Cancel: #f1f3f5 (Light Gray)
- Submit: linear-gradient(135deg, #D2691E 0%, #8B4513 100%)
```

### Layout Structure
```
┌─────────────────────────────────────────┐
│  HEADER (Brown Gradient)                │
│  • Decorative circles                   │
│  • "Payment Confirmation" title         │
│  • Subtitle                             │
│  • Close button (top-right)             │
│                                         │
│  ┌───────────────────────────────────┐ │
│  │ ORDER SUMMARY CARD (Floating)     │ │
│  │ Order ID: #ORD-12345              │ │
│  │ ─────────────────────────────────  │ │
│  │ Total Payment: Rp 150.000         │ │
│  └───────────────────────────────────┘ │
└─────────────────────────────────────────┘
┌─────────────────────────────────────────┐
│  CONTENT SECTION (White)                │
│                                         │
│  ┌───────────────────────────────────┐ │
│  │ QR CODE SECTION (Cream)           │ │
│  │ [Scan to Pay Badge]               │ │
│  │                                   │ │
│  │    ┌─────────────────┐            │ │
│  │    │                 │            │ │
│  │    │   QR CODE       │            │ │
│  │    │   200x200px     │            │ │
│  │    │                 │            │ │
│  │    └─────────────────┘            │ │
│  │                                   │ │
│  │ [QRIS Logo] QRIS Payment          │ │
│  │ Dapoer Budess Bakery              │ │
│  └───────────────────────────────────┘ │
│                                         │
│  ┌───────────────────────────────────┐ │
│  │ INSTRUCTIONS (Gray)               │ │
│  │ ℹ️ How to Pay                     │ │
│  │                                   │ │
│  │ ① Open your e-wallet...           │ │
│  │ ② Select "Scan QR"...             │ │
│  │ ③ Scan the QR code...             │ │
│  │ ④ Complete payment...             │ │
│  └───────────────────────────────────┘ │
│                                         │
│  Upload Payment Proof                   │
│  ┌───────────────────────────────────┐ │
│  │ [Upload Icon]                     │ │
│  │ Click to upload                   │ │
│  │ JPG, PNG (Max 2MB)                │ │
│  └───────────────────────────────────┘ │
│                                         │
│  ⏳ Waiting for verification...         │
│                                         │
│  [Cancel]  [Submit Payment Proof]       │
└─────────────────────────────────────────┘
```

### Key Visual Elements

#### 1. Header
- Brown gradient background with decorative circles
- White text with good contrast
- Glassmorphism close button (backdrop-filter blur)

#### 2. Order Summary Card
- Floats above header with negative margin
- White background with subtle shadow
- Dashed separator line
- Large, bold total amount in brown

#### 3. QR Code Section
- Cream gradient background
- Sandy brown border (2px solid)
- White container for QR with shadow
- "Scan to Pay" badge with brown gradient
- QRIS logo integration
- Merchant name below

#### 4. Instructions
- Light gray background (#f8f9fa)
- Info icon in brown circle
- Numbered steps with brown circle badges
- Clear, concise text

#### 5. Upload Area
- Brown dashed border (2px)
- Cream gradient background
- Brown gradient upload icon (56px circle)
- Hover effect: border darkens, gradient reverses

#### 6. Status Badge
- Yellow warning style
- Hourglass emoji
- Sets expectation for verification

#### 7. Buttons
- Cancel: Gray with hover lift
- Submit: Brown gradient with shadow and lift
- Smooth transitions (0.2s)

### Animations

#### Modal Entry
```css
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```
- Duration: 0.3s
- Easing: ease-out
- Effect: Slides up from bottom with fade-in

#### Button Hover
- Transform: translateY(-2px)
- Shadow increases
- Smooth transition

#### Upload Area Hover
- Border color darkens
- Gradient reverses
- Smooth color transition

### Typography

```
Header Title: 1.5rem, 700 weight, 'Outfit' font
Subtitle: 0.9rem, normal weight
Order ID: 0.9rem, 600 weight, 'Courier New' (monospace)
Total Payment: 1.8rem, 800 weight, 'Outfit' font
Section Labels: 0.9rem, 700 weight
Instructions: 0.85rem, normal weight
Button Text: 0.95rem, 600-700 weight
```

### Spacing

```
Modal Padding: 0 (header/content have own padding)
Header Padding: 1.5rem 1.5rem 3rem 1.5rem
Content Padding: 0 1.5rem 1.5rem 1.5rem
Card Padding: 1.5rem
Section Margins: 1.5rem bottom
Button Gap: 0.75rem
```

### Shadows

```
Modal: 0 20px 60px rgba(0,0,0,0.15)
Order Card: 0 8px 24px rgba(0,0,0,0.08)
QR Container: 0 4px 12px rgba(0,0,0,0.08)
Upload Icon: 0 4px 12px rgba(210, 105, 30, 0.3)
Submit Button: 0 4px 12px rgba(210, 105, 30, 0.3)
  Hover: 0 6px 16px rgba(210, 105, 30, 0.4)
```

### Border Radius

```
Modal: 24px
Order Card: 16px
QR Section: 16px
QR Container: 12px
Instructions: 12px
Upload Area: 12px
Status Badge: 8px
Buttons: 12px
Badges: 20px (pill shape)
Circles: 50% (perfect circle)
```

## 📱 Mobile Responsive

```css
@media (max-width: 768px) {
    Modal Width: 95%
    Margin: 1rem
    All padding scales proportionally
}
```

## 🎯 Design Principles

1. **Warm & Welcoming**: Brown/cream colors match bakery brand
2. **Clear Hierarchy**: Important info (Order ID, Total) prominently displayed
3. **Guided Experience**: Numbered steps with visual badges
4. **Trust & Security**: Professional fintech-inspired design
5. **Mobile-First**: Compact 480px width, responsive design
6. **Smooth Interactions**: All hover effects and animations
7. **Status Awareness**: Clear indication of next steps

## 🔄 Comparison with Old Design

| Feature | Old (Purple) | New (Brown) |
|---------|-------------|-------------|
| Width | 750px | 480px |
| QR Size | 320px | 200px |
| Header Color | Purple gradient | Brown gradient |
| Order Summary | None | Floating card |
| Instructions | Numbered list | Circle badges |
| Status Badge | None | Yellow warning |
| Upload Icon | Purple gradient | Brown gradient |
| Button Color | Purple gradient | Brown gradient |
| Animation | None | slideUp |
| Backdrop | Solid | Blur effect |

## ✨ Fintech Inspiration

The design draws inspiration from:
- **GoPay**: Clean layout, prominent QR code
- **OVO**: Warm colors, friendly interface
- **ShopeePay**: Clear instructions, status indicators
- **Modern Banking Apps**: Professional, trustworthy design

---

**Design Status**: ✅ Implemented
**Brand Alignment**: ✅ Matches bakery theme
**User Experience**: ✅ Clear and intuitive
**Mobile Friendly**: ✅ Responsive design
