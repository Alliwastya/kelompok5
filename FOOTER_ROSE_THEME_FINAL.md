# Footer Premium - Rose/Pink Theme - Dapoer Budess Bakery

## 🎨 Design Overview

Footer telah didesain dengan **tema rose/pink** sebagai warna aksen, background coklat tua (#2C1A0E), dan gambar dekoratif dari Unsplash yang memberikan nuansa bakery premium dan elegan.

---

## ✨ Key Features

### 1. **Color Scheme - Rose/Pink Theme**

| Element | Color | Hex Code | Usage |
|---------|-------|----------|-------|
| Background | Dark Brown | #2C1A0E | Main footer background |
| Text Primary | Cream/Beige | #F5F5DC | Body text |
| Accent/Highlight | Rose/Pink | #E8748A | Titles, icons, links |
| Accent Hover | Light Pink | #FF9DB5 | Link hover state |

### 2. **Decorative Images from Unsplash**

#### Left Side - Wheat/Bread Ingredients
```css
background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=600&fit=crop');
```
**Specifications:**
- Position: Left side, full height
- Width: 300px
- Opacity: 0.15
- Mask: Linear gradient fade to right
- Effect: Subtle, tidak mengganggu teks

#### Right Side - Artisan Bread
```css
background-image: url('https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=400&h=600&fit=crop');
```
**Specifications:**
- Position: Right side, full height
- Width: 350px
- Opacity: 0.18
- Mask: Linear gradient fade to left
- Effect: Frame alami, overflow sedikit keluar

### 3. **Layout Structure (2 Columns)**

```
Footer (#2C1A0E background, 60px padding)
├── Left Decorative Image (wheat/ingredients)
├── Right Decorative Image (artisan bread)
├── Footer Content (max-width: 1200px)
│   ├── Left Column (55%)
│   │   ├── Title (📍 Dapoer Budess Bakery) - Rose #E8748A
│   │   ├── Tagline
│   │   └── Contact Items (5)
│   │       ├── 📍 Lokasi
│   │       ├── 📞 Telepon
│   │       ├── ✉️ Email
│   │       ├── ⏰ Jam Operasional
│   │       └── 📱 Instagram (clickable, rose color)
│   └── Right Column (45%)
│       └── Google Maps Embed (rounded, shadow)
└── Footer Bottom
    ├── Wheat Ornament (🌾)
    └── Copyright Text (centered)
```

### 4. **Typography**

#### Title (H3)
```css
font-family: 'Playfair Display', serif
font-size: 2.2rem
color: #E8748A (rose/pink)
font-weight: 700
text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6)
letter-spacing: 0.5px
```

#### Tagline/Description
```css
font-family: 'Lora', serif
font-size: 0.95rem
color: #F5F5DC (cream)
line-height: 1.7
text-shadow: 0 1px 4px rgba(0, 0, 0, 0.7)
```

#### Contact Labels
```css
font-family: 'Outfit', sans-serif
font-size: 0.85rem
color: #E8748A (rose/pink)
font-weight: 700
text-transform: uppercase
letter-spacing: 0.5px
text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6)
```

#### Contact Values
```css
font-size: 0.95rem
color: #F5F5DC (cream)
line-height: 1.7
text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7)
```

#### Links (Instagram)
```css
color: #E8748A (rose/pink)
font-weight: 600
display: inline-flex
align-items: center
gap: 0.5rem

Hover:
color: #FF9DB5 (light pink)
text-shadow: 0 0 12px rgba(232, 116, 138, 0.6)
transform: scale(1.03)
```

### 5. **Icons**

All icons use **rose/pink color (#E8748A)**:
- 📍 Location pin
- 📞 Phone
- ✉️ Email
- ⏰ Clock
- 📱 Instagram (with SVG icon)

```css
.contact-icon {
    font-size: 1.4rem
    color: #E8748A
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5))
}
```

### 6. **Google Maps Card**

```css
border-radius: 12px
height: 320px (desktop), 280px (tablet), 250px (mobile)
box-shadow: 
    0 10px 35px rgba(0, 0, 0, 0.5),
    0 0 0 2px rgba(232, 116, 138, 0.2) /* rose border */
background: rgba(0, 0, 0, 0.3)

Hover Effect:
- box-shadow: Enhanced with rose glow
- transform: translateY(-3px)
- transition: 0.3s ease
```

**Map Embed:**
- Location: Roti Panggang Dapoer Budess, Bogor
- Coordinates: -6.5839, 106.7716
- Filter: brightness(0.95) contrast(1.05) saturate(0.9)

### 7. **Footer Bottom**

```css
margin-top: 3.5rem
padding-top: 2rem
border-top: 1px solid rgba(232, 116, 138, 0.2) /* rose divider */
text-align: center
font-size: 0.85rem
color: rgba(245, 245, 220, 0.7)

Wheat Ornament:
- content: '🌾'
- font-size: 1.5rem
- opacity: 0.6
- margin-bottom: 1rem
```

**Copyright Text:**
```
© 2026 Dapoer Budess Bakery. Semua hak dilindungi. | Premium Quality & Fresh Every Day
```

---

## 📱 Responsive Design

### Desktop (> 768px)
- Grid: 55% / 45% (2 columns)
- Gap: 3rem
- Map height: 320px
- Decorative images: Full size (300px left, 350px right)
- Title: 2.2rem
- Padding: 60px top/bottom

### Tablet (≤ 768px)
- Grid: 1 column
- Gap: 2.5rem
- Map height: 280px
- Decorative images: Reduced (200px, opacity 0.1)
- Title: 1.9rem
- Padding: 50px top/bottom

### Mobile (≤ 480px)
- Grid: 1 column
- Map height: 250px
- Decorative images: Hidden
- Title: 1.6rem
- Icon size: 1.3rem
- Padding: 40px top/bottom

---

## 🎭 Visual Effects

### 1. **Textures & Overlays**
```css
Wood Texture:
- repeating-linear-gradient (subtle lines)
- opacity: 0.4

Dark Overlay:
- rgba(0, 0, 0, 0.2)
- Improves text readability
```

### 2. **Shadows**
```css
Text Shadow: 0 1px 3px rgba(0, 0, 0, 0.7)
Title Shadow: 0 2px 8px rgba(0, 0, 0, 0.6)
Icon Shadow: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5))
Card Shadow: 0 10px 35px rgba(0, 0, 0, 0.5)
```

### 3. **Hover Interactions**
```css
Contact Items: translateX(5px)
Links: scale(1.03) + rose glow
Map Card: translateY(-3px) + enhanced shadow
All transitions: 0.3s ease
```

### 4. **Image Masks**
```css
Left Image:
mask-image: linear-gradient(to right, rgba(0,0,0,0.8) 0%, transparent 100%)

Right Image:
mask-image: linear-gradient(to left, rgba(0,0,0,0.8) 0%, transparent 100%)
```

---

## 🔧 Customization Guide

### Change Accent Color (Rose/Pink)

Find and replace `#E8748A` with your preferred color:

```css
/* Title */
.footer-info h3 { color: #YOUR_COLOR; }

/* Icons */
.contact-icon { color: #YOUR_COLOR; }

/* Labels */
.contact-label { color: #YOUR_COLOR; }

/* Links */
.contact-value a { color: #YOUR_COLOR; }

/* Map border */
.footer-map { box-shadow: 0 0 0 2px rgba(YOUR_RGB, 0.2); }

/* Footer divider */
.footer-bottom { border-top: 1px solid rgba(YOUR_RGB, 0.2); }
```

### Replace Decorative Images

#### Option 1: Use Different Unsplash Images
```css
.footer-deco-left {
    background-image: url('YOUR_UNSPLASH_URL_HERE');
}

.footer-deco-right {
    background-image: url('YOUR_UNSPLASH_URL_HERE');
}
```

#### Option 2: Use Local Images
```css
.footer-deco-left {
    background-image: url('{{ asset("images/wheat-decoration.jpg") }}');
}

.footer-deco-right {
    background-image: url('{{ asset("images/bread-decoration.jpg") }}');
}
```

### Adjust Spacing
```css
/* Padding */
footer { padding: 60px 2rem 40px; }

/* Column gap */
.footer-content { gap: 3rem; }

/* Contact items gap */
.footer-contact { gap: 1.4rem; }
```

---

## ✅ Quality Checklist

- [x] Rose/pink theme (#E8748A) as accent color
- [x] Dark brown background (#2C1A0E)
- [x] Cream/beige text (#F5F5DC)
- [x] Decorative images from Unsplash (left & right)
- [x] Images with gradient mask (fade effect)
- [x] 2-column layout (55% / 45%)
- [x] Google Maps embed with rose border
- [x] All icons in rose/pink color
- [x] Instagram link clickable with SVG icon
- [x] Wheat ornament divider (🌾)
- [x] Soft shadows on all text
- [x] Hover effects on links and map
- [x] Responsive design (desktop/tablet/mobile)
- [x] Professional typography (serif + sans-serif)
- [x] No layout breaks
- [x] No CSS/JS errors
- [x] Images don't cover text
- [x] Clean and elegant design

---

## 📝 Important Notes

### Image Sources
1. **Left Image**: Unsplash photo by Wesual Click
   - URL: `https://images.unsplash.com/photo-1509440159596-0249088772ff`
   - Content: Wheat, bread ingredients
   
2. **Right Image**: Unsplash photo by Jude Infantini
   - URL: `https://images.unsplash.com/photo-1586444248902-2f64eddc13df`
   - Content: Artisan bread

### Performance
- Images loaded via CDN (Unsplash)
- Optimized with `?w=400&h=600&fit=crop` parameters
- Lazy loading via CSS background-image
- Lightweight implementation

### Browser Compatibility
- Tested on: Chrome, Firefox, Safari, Edge
- CSS Grid: Supported on all modern browsers
- Mask-image: Supported with -webkit- prefix
- Fallback: Single column on older browsers

---

## 🎯 Design Goals Achieved

✅ **Rose/Pink Theme**: Elegant accent color throughout
✅ **Premium Look**: Dark background with decorative images
✅ **Warm Bakery Feel**: Bread images and warm colors
✅ **Professional**: Clean layout, elegant typography
✅ **Modern**: Smooth transitions, hover effects
✅ **Readable**: High contrast, text shadows
✅ **Responsive**: Mobile-friendly design
✅ **Performant**: Optimized images, efficient CSS

---

## 🚀 Production Ready

Footer is **100% ready for production** with:
- ✅ No placeholder images (using real Unsplash photos)
- ✅ Proper Google Maps embed
- ✅ Working Instagram link
- ✅ Responsive design tested
- ✅ Cross-browser compatible
- ✅ Performance optimized
- ✅ No errors or warnings

---

**Implementation Status**: ✅ **COMPLETED**
**Theme**: Rose/Pink Accent (#E8748A)
**Last Updated**: April 26, 2026
**Version**: 3.0 (Final - Rose Theme)
