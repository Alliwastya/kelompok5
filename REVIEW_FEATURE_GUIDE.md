# Fitur Ulasan/Review Customer - Panduan Lengkap

## 📝 Overview

Fitur ini memungkinkan customer memberikan ulasan (rating + komentar) setelah pesanan selesai diterima, baik untuk pengiriman maupun pickup di toko.

## ✨ Fitur Utama

### 1. **Rating Bintang (1-5)**
- ⭐ 1 Bintang: Sangat Buruk
- ⭐⭐ 2 Bintang: Buruk
- ⭐⭐⭐ 3 Bintang: Cukup
- ⭐⭐⭐⭐ 4 Bintang: Baik
- ⭐⭐⭐⭐⭐ 5 Bintang: Sangat Baik

### 2. **Komentar/Pesan**
- Maksimal 1000 karakter
- Opsional (boleh kosong)
- Bisa berisi testimoni, saran, atau kritik

### 3. **Upload Foto (Opsional)**
- Maksimal 2MB per foto
- Format: JPG, PNG, GIF
- Multiple upload (bisa lebih dari 1 foto)
- Preview sebelum submit

### 4. **Display Name (Opsional)**
- Customer bisa pilih nama tampilan
- Jika kosong, pakai nama asli dari order

## 🎯 Kapan Bisa Beri Ulasan?

### **Untuk Pengiriman (Delivery):**
```
Order Status: "delivered" atau "completed"
Kondisi: Pesanan sudah diterima customer
Tombol: "★ Beri Ulasan" muncul di order status
```

### **Untuk Pickup di Toko:**
```
Order Status: "picked_up" atau "completed"
Kondisi: Pesanan sudah diambil di toko
Tombol: "★ Beri Ulasan" muncul di order status
```

## 🔄 Workflow

### **Customer Side:**
```
1. Order selesai (delivered/picked_up)
   ↓
2. Buka "Cek Status Pesanan"
   ↓
3. Lihat tombol "★ Beri Ulasan"
   ↓
4. Klik tombol → Modal review terbuka
   ↓
5. Pilih rating (1-5 bintang)
   ↓
6. Tulis komentar (opsional)
   ↓
7. Upload foto (opsional)
   ↓
8. Klik "Kirim Ulasan"
   ↓
9. Ulasan tersimpan
   ↓
10. Status berubah: "✓ Diulas"
```

### **Admin Side:**
```
1. Ulasan masuk otomatis
   ↓
2. Admin bisa lihat di menu "Ulasan"
   ↓
3. Admin bisa:
   - Approve/Reject
   - Hide/Show
   - Reply (future feature)
```

## 📱 Tampilan UI

### **Tombol Review di Order Status**
```
┌─────────────────────────────────────┐
│ Order #ORD-123                      │
│ Status: ✓ Selesai                   │
│                                     │
│ [★ Beri Ulasan]  [Chat Admin]      │
└─────────────────────────────────────┘
```

### **Modal Review**
```
┌─────────────────────────────────────┐
│           Beri Ulasan               │
├─────────────────────────────────────┤
│                                     │
│  Rating Produk:                     │
│  ⭐ ⭐ ⭐ ⭐ ⭐                        │
│  (Klik bintang untuk rating)        │
│                                     │
│  Komentar:                          │
│  ┌─────────────────────────────┐   │
│  │ Tulis ulasan Anda...        │   │
│  │                             │   │
│  └─────────────────────────────┘   │
│                                     │
│  Nama Tampilan (Opsional):          │
│  ┌─────────────────────────────┐   │
│  │ Ibu Siti                    │   │
│  └─────────────────────────────┘   │
│                                     │
│  Foto/Video (Opsional):             │
│  [📸 Upload]                        │
│                                     │
│  [Kirim Ulasan]                     │
└─────────────────────────────────────┘
```

### **Setelah Review**
```
┌─────────────────────────────────────┐
│ Order #ORD-123                      │
│ Status: ✓ Selesai                   │
│                                     │
│ ✓ Diulas  [Chat Admin]              │
└─────────────────────────────────────┘
```

## 🎨 Tampilan Review di Homepage

### **Review Card**
```
┌─────────────────────────────────────┐
│  👤 Ibu S.        ⭐⭐⭐⭐⭐         │
│  2 hari yang lalu                   │
│                                     │
│  "Rotinya enak banget! Lembut dan   │
│   fresh. Pengiriman cepat. Pasti    │
│   order lagi!"                      │
│                                     │
│  [📷] [📷] [📷]                     │
│  (Foto produk)                      │
└─────────────────────────────────────┘
```

## 🔒 Security & Validation

### **Validasi Input:**
```php
- order_id: Required, must exist
- rating: Required, 1-5 integer
- comment: Optional, max 1000 chars
- media: Optional, image only, max 2MB
- phone: Required (security check)
- display_name: Optional, max 50 chars
```

### **Security Checks:**
```php
1. Phone number harus match dengan order
2. Order harus milik customer yang login
3. Tidak bisa review 2x untuk 1 order
4. CSRF token validation
5. File upload validation
```

### **Prevent Abuse:**
- ✅ 1 order = 1 review (tidak bisa review berkali-kali)
- ✅ Hanya customer yang order bisa review
- ✅ Harus setelah order selesai
- ✅ File size limit (2MB)
- ✅ Content moderation (admin bisa hide)

## 📊 Database Structure

### **Table: reviews**
```sql
- id (primary key)
- order_id (foreign key)
- rating (1-5)
- comment (text, nullable)
- media_urls (json, nullable)
- display_name (string, nullable)
- is_visible (boolean, default true)
- created_at
- updated_at
```

### **Relationship:**
```
Review belongsTo Order
Order hasOne Review
```

## 🎯 Use Cases

### **Case 1: Customer Puas**
```
Rating: ⭐⭐⭐⭐⭐
Comment: "Rotinya enak, pengiriman cepat!"
Photo: [foto roti]
Result: Review ditampilkan di homepage
```

### **Case 2: Customer Komplain**
```
Rating: ⭐⭐
Comment: "Rotinya kurang fresh"
Photo: -
Result: Admin bisa follow up via chat
```

### **Case 3: Customer Tanpa Komentar**
```
Rating: ⭐⭐⭐⭐⭐
Comment: (kosong)
Photo: -
Result: Tetap valid, hanya rating yang ditampilkan
```

## 🚀 API Endpoints

### **Submit Review**
```
POST /reviews
Content-Type: multipart/form-data

Parameters:
- order_id: integer
- rating: integer (1-5)
- comment: string (optional)
- media[]: file[] (optional)
- phone: string
- display_name: string (optional)

Response:
{
  "success": true,
  "message": "Terima kasih! Ulasan Anda telah berhasil dikirim."
}
```

### **Get Reviews**
```
GET /reviews

Response:
{
  "success": true,
  "reviews": [
    {
      "id": 1,
      "customer_name": "Ibu S.",
      "rating": 5,
      "comment": "Rotinya enak!",
      "media_urls": ["storage/reviews/..."],
      "items": ["Roti Coklat", "Roti Keju"],
      "created_at": "2 hari yang lalu"
    }
  ]
}
```

## 💡 Tips untuk Customer

### **Menulis Review yang Baik:**
1. ✅ Jujur dan objektif
2. ✅ Sebutkan produk yang dibeli
3. ✅ Jelaskan pengalaman (rasa, tekstur, pengiriman)
4. ✅ Upload foto produk (lebih meyakinkan)
5. ✅ Berikan saran konstruktif

### **Contoh Review Bagus:**
```
⭐⭐⭐⭐⭐
"Beli Roti Coklat dan Roti Keju. Keduanya enak banget! 
Rotinya lembut, fresh, dan tidak terlalu manis. 
Pengiriman tepat waktu. Packaging rapi. 
Pasti order lagi! Recommended!"
```

### **Contoh Review Kurang Bagus:**
```
⭐⭐⭐⭐⭐
"Enak"
(Terlalu singkat, tidak informatif)
```

## 🎨 Styling & Design

### **Colors:**
- Rating Stars: `#FFD700` (gold)
- Button: `var(--accent)` (orange/brown)
- Card Background: White with gradient
- Text: `#555` (dark gray)

### **Animations:**
- Fade in on load
- Hover effect on cards
- Smooth transitions
- Star rating animation

### **Responsive:**
- Desktop: Grid 3 columns
- Tablet: Grid 2 columns
- Mobile: Single column
- Touch-optimized buttons

## 📈 Benefits

### **Untuk Bisnis:**
- ✅ Social proof (bukti kepuasan customer)
- ✅ Meningkatkan trust calon pembeli
- ✅ Feedback untuk improve produk
- ✅ Marketing gratis (word of mouth)
- ✅ SEO boost (user-generated content)

### **Untuk Customer:**
- ✅ Bisa berbagi pengalaman
- ✅ Membantu calon pembeli lain
- ✅ Apresiasi untuk produk bagus
- ✅ Channel untuk komplain/saran

## 🔧 Admin Features

### **Review Management:**
- ✅ View all reviews
- ✅ Filter by rating
- ✅ Hide/Show reviews
- ✅ Delete spam reviews
- ✅ Reply to reviews (future)

### **Analytics:**
- ✅ Average rating
- ✅ Total reviews
- ✅ Rating distribution
- ✅ Most reviewed products

## 🐛 Troubleshooting

### **Tombol "Beri Ulasan" tidak muncul**
```
Cek:
1. Order status = "delivered" atau "completed"?
2. Sudah pernah review sebelumnya?
3. JavaScript error di console?
```

### **Gagal submit review**
```
Cek:
1. Rating sudah dipilih?
2. File size < 2MB?
3. Internet connection stable?
4. CSRF token valid?
```

### **Review tidak muncul di homepage**
```
Cek:
1. is_visible = true?
2. Admin sudah approve?
3. Cache cleared?
```

---

**Status**: ✅ Fully Implemented
**Version**: 1.0
**Last Updated**: 2026-04-26

## 📞 Support

Jika ada masalah dengan fitur review, customer bisa:
1. Chat dengan admin via tombol "Chat Admin"
2. Hubungi WhatsApp toko
3. Email ke support@dapoerbudess.com
