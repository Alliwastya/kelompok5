# Star Rating System - Panduan Penggunaan

## ⭐ Overview

Sistem rating bintang yang interaktif dan user-friendly untuk customer memberikan penilaian produk.

## 🎯 Fitur

### **1. Interactive Stars**
- ✅ Klik bintang untuk memilih rating
- ✅ Hover untuk preview rating
- ✅ Visual feedback (warna emas)
- ✅ Default rating: 5 bintang

### **2. Rating Levels**
```
⭐ (1 bintang)   = Sangat Buruk
⭐⭐ (2 bintang)  = Buruk
⭐⭐⭐ (3 bintang) = Cukup
⭐⭐⭐⭐ (4 bintang) = Baik
⭐⭐⭐⭐⭐ (5 bintang) = Sangat Baik
```

## 🎨 Cara Kerja

### **Customer Side:**
```
1. Klik tombol "★ Beri Ulasan"
   ↓
2. Modal review terbuka
   ↓
3. Rating default: 5 bintang (emas)
   ↓
4. Hover bintang → Preview rating
   ↓
5. Klik bintang → Set rating
   ↓
6. Bintang berubah warna emas
   ↓
7. Submit form → Rating tersimpan
```

### **Visual Feedback:**
```
Hover:
- Bintang yang di-hover: Emas
- Bintang sebelumnya: Emas
- Bintang setelahnya: Abu-abu

Click:
- Bintang terpilih: Emas (permanent)
- Bintang sebelumnya: Emas
- Bintang setelahnya: Abu-abu

Mouse Leave:
- Kembali ke rating yang dipilih
```

## 💻 Technical Details

### **HTML Structure:**
```html
<div class="star-rating" id="starRating">
    <span class="star" data-value="1">★</span>
    <span class="star" data-value="2">★</span>
    <span class="star" data-value="3">★</span>
    <span class="star" data-value="4">★</span>
    <span class="star" data-value="5">★</span>
</div>
<input type="hidden" id="ratingValue" name="rating" value="5">
```

### **CSS Styling:**
```css
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
    transition: all 0.2s;
}

.star:hover,
.star:hover ~ .star {
    color: #FFD700;
}

.star.active {
    color: #FFD700 !important;
}
```

### **JavaScript Functions:**

**1. Initialize Star Rating:**
```javascript
function initStarRating() {
    const container = document.getElementById('starRating');
    const stars = container.querySelectorAll('.star');
    
    stars.forEach(star => {
        // Click event
        star.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('ratingValue').value = this.getAttribute('data-value');
            highlightStars(this.getAttribute('data-value'));
        });
        
        // Hover event
        star.addEventListener('mouseover', function() {
            highlightStars(this.getAttribute('data-value'));
        });
    });
    
    // Mouse leave event
    container.addEventListener('mouseleave', function() {
        highlightStars(document.getElementById('ratingValue').value);
    });
}
```

**2. Highlight Stars:**
```javascript
function highlightStars(value) {
    document.querySelectorAll('.star').forEach(star => {
        star.classList.toggle('active', 
            parseInt(star.getAttribute('data-value')) <= parseInt(value)
        );
    });
}
```

**3. Open Review Modal:**
```javascript
function openReviewModal(orderId) {
    document.getElementById('reviewOrderId').value = orderId;
    document.getElementById('reviewModal').classList.add('active');
    document.getElementById('reviewForm').reset();
    
    // Initialize star rating
    setTimeout(initStarRating, 100);
    
    // Set default 5 stars
    highlightStars(5);
}
```

## 🎯 User Experience

### **Smooth Interactions:**
- ✅ Instant visual feedback
- ✅ Smooth color transitions (0.2s)
- ✅ Hover preview before click
- ✅ Clear active state
- ✅ Touch-friendly (mobile)

### **Accessibility:**
- ✅ Large click targets (2.5rem)
- ✅ Clear visual states
- ✅ Keyboard accessible (future)
- ✅ Screen reader friendly (future)

## 📱 Mobile Responsive

### **Touch Optimization:**
```css
@media (max-width: 768px) {
    .star {
        font-size: 2rem;
        padding: 0.5rem;
    }
}
```

### **Touch Events:**
- ✅ Tap to select
- ✅ No hover on mobile
- ✅ Immediate feedback
- ✅ Large touch targets

## 🎨 Color Scheme

### **States:**
```
Default:   #ddd (light gray)
Hover:     #FFD700 (gold)
Active:    #FFD700 (gold)
Disabled:  #ccc (lighter gray)
```

### **Transitions:**
```css
transition: all 0.2s ease;
```

## 🔧 Customization

### **Change Star Size:**
```css
.star {
    font-size: 3rem; /* Larger */
}
```

### **Change Star Color:**
```css
.star.active {
    color: #FF6B6B; /* Red */
}
```

### **Change Default Rating:**
```javascript
// In openReviewModal()
highlightStars(3); // Default 3 stars
```

## 🐛 Troubleshooting

### **Stars tidak bisa diklik:**
```
Cek:
1. initStarRating() dipanggil?
2. Event listener attached?
3. JavaScript error di console?
4. z-index conflict?
```

### **Stars tidak berubah warna:**
```
Cek:
1. CSS .star.active loaded?
2. highlightStars() berfungsi?
3. classList.toggle() working?
```

### **Rating tidak tersimpan:**
```
Cek:
1. Hidden input #ratingValue ada?
2. Value ter-update saat klik?
3. Form submit dengan benar?
```

## ✨ Best Practices

### **For Developers:**
1. ✅ Always initialize after modal opens
2. ✅ Reset rating on modal close
3. ✅ Validate rating before submit
4. ✅ Provide visual feedback
5. ✅ Test on mobile devices

### **For Users:**
1. ✅ Hover untuk preview
2. ✅ Klik untuk confirm
3. ✅ Bisa ubah rating sebelum submit
4. ✅ Default 5 bintang (bisa diubah)

## 📊 Analytics

### **Track Rating Distribution:**
```javascript
// Example
const ratings = {
    1: 5,   // 5 reviews dengan 1 bintang
    2: 10,  // 10 reviews dengan 2 bintang
    3: 20,  // 20 reviews dengan 3 bintang
    4: 50,  // 50 reviews dengan 4 bintang
    5: 100  // 100 reviews dengan 5 bintang
};

// Average rating
const avgRating = (1*5 + 2*10 + 3*20 + 4*50 + 5*100) / 185;
// = 4.27
```

## 🎯 Future Enhancements

### **Possible Improvements:**
- [ ] Half-star ratings (4.5 stars)
- [ ] Keyboard navigation (arrow keys)
- [ ] Screen reader support (ARIA)
- [ ] Animation on select
- [ ] Sound feedback (optional)
- [ ] Emoji ratings (😞 😐 😊 😄 😍)

---

**Status**: ✅ Fully Functional
**Version**: 1.0
**Browser Support**: All modern browsers
**Mobile**: Touch-optimized
