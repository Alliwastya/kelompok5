# Payment Page - Full Width Responsive Design

## 🎯 Design Strategy

Halaman pembayaran sekarang menggunakan **full-width layout** dengan konten yang **centered** untuk pengalaman optimal di semua device.

## 📐 Layout Architecture

### **Container Strategy**
```
Mobile (≤767px):
├─ Full width container
├─ Content padding: 1-1.5rem
└─ No max-width constraint

Tablet (768-1199px):
├─ Full width container
├─ Content padding: 2-2.5rem
├─ Content max-width: 800px (centered)
└─ Spacious layout

Desktop (≥1200px):
├─ Full width container
├─ Content padding: 2.5-3rem
├─ Content max-width: 1200px (centered)
└─ Premium spacing
```

## 🎨 Responsive Breakdown

### **📱 Mobile (≤480px)**
```css
Container: 100% width
Top Bar: Full width, 1rem padding
Order Info: Full width, 1.25rem padding
Content: Full width, 1.25rem padding
QR Code: 200px × 200px
Sections: Full width, no max-width
Buttons: Stacked vertically
```

### **📱 Large Mobile (481-767px)**
```css
Container: 100% width
Top Bar: Full width, 1rem padding
Order Info: Full width, 1.5rem padding
Content: Full width, 1.5rem padding
QR Code: 240px × 240px
Sections: Full width, no max-width
Buttons: Horizontal layout
```

### **💻 Tablet (768-1199px)**
```css
Container: 100% width
Top Bar: Full width, 2.5rem padding
  └─ Content: Max 1200px centered
Order Info: Full width, 2rem padding
  └─ Content: Max 1200px centered
Content: 2rem padding
  └─ Sections: Max 800px centered
QR Code: 300px × 300px
Total Amount: 2rem font
Buttons: Max 500px centered
```

### **🖥️ Desktop (≥1200px)**
```css
Container: 100% width
Top Bar: Full width, 3rem padding
  └─ Content: Max 1200px centered
Order Info: Full width, 2.5rem padding
  └─ Content: Max 1200px centered
Content: 2.5rem padding
  └─ Sections: Max 800px centered
QR Code: 320px × 320px
Total Amount: 2.25rem font
Buttons: Max 600px centered
```

## 🔧 Technical Implementation

### **Full Width with Centered Content**
```css
/* Container is full width */
.container {
    width: 100%;
    max-width: 100%;
}

/* Content sections are centered */
.qr-section,
.instructions,
.upload-section,
.alert-box,
.btn-group {
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* Order info rows centered */
.order-row {
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}
```

### **Top Bar Structure**
```html
<div class="top-bar"> <!-- Full width -->
    <div class="top-bar-content"> <!-- Max 1200px centered -->
        <button>Back</button>
        <h1>Title</h1>
    </div>
</div>
```

## 📊 Spacing System

### **Horizontal Padding**
```
Device          | Top Bar | Order Info | Content
-------------------------------------------------
Mobile          | 1rem    | 1.25rem    | 1.25rem
Large Mobile    | 1rem    | 1.5rem     | 1.5rem
Tablet          | 2.5rem  | 2rem       | 2rem
Desktop         | 3rem    | 2.5rem     | 2.5rem
```

### **Content Max-Width**
```
Element         | Max Width | Purpose
-----------------------------------------
Top Bar Content | 1200px    | Consistent with order info
Order Rows      | 1200px    | Wide but readable
QR Section      | 800px     | Focused attention
Instructions    | 800px     | Comfortable reading
Upload Section  | 800px     | Consistent with QR
Alert Box       | 800px     | Aligned with content
Button Group    | 600px (tablet) | Centered actions
                | 800px (mobile) | Full section width
```

## 🎯 Visual Hierarchy

### **Desktop/Tablet View**
```
┌─────────────────────────────────────────┐
│         Top Bar (Full Width)            │
│  ┌───────────────────────────────────┐  │
│  │  Back Button | Title (Max 1200px)│  │
│  └───────────────────────────────────┘  │
├─────────────────────────────────────────┤
│       Order Info (Full Width)           │
│  ┌───────────────────────────────────┐  │
│  │  Order Details (Max 1200px)       │  │
│  └───────────────────────────────────┘  │
├─────────────────────────────────────────┤
│                                         │
│        ┌─────────────────┐              │
│        │   QR Section    │ (Max 800px)  │
│        │   (Centered)    │              │
│        └─────────────────┘              │
│                                         │
│        ┌─────────────────┐              │
│        │  Instructions   │ (Max 800px)  │
│        └─────────────────┘              │
│                                         │
│        ┌─────────────────┐              │
│        │  Upload Section │ (Max 800px)  │
│        └─────────────────┘              │
│                                         │
│        ┌─────────────────┐              │
│        │    Buttons      │ (Max 600px)  │
│        └─────────────────┘              │
│                                         │
└─────────────────────────────────────────┘
```

### **Mobile View**
```
┌─────────────────┐
│    Top Bar      │
├─────────────────┤
│   Order Info    │
├─────────────────┤
│                 │
│   QR Section    │
│   (Full Width)  │
│                 │
├─────────────────┤
│  Instructions   │
├─────────────────┤
│ Upload Section  │
├─────────────────┤
│  [Cancel Btn]   │
│  [Submit Btn]   │
└─────────────────┘
```

## ✨ Key Features

### **1. Full Width Background**
- ✅ Top bar spans entire screen width
- ✅ Order info section spans entire width
- ✅ Content area spans entire width
- ✅ Professional full-screen appearance

### **2. Centered Content**
- ✅ QR section centered (max 800px)
- ✅ Instructions centered (max 800px)
- ✅ Upload form centered (max 800px)
- ✅ Buttons centered (max 600px)
- ✅ Optimal reading width maintained

### **3. Responsive Padding**
- ✅ Mobile: Compact (1-1.5rem)
- ✅ Tablet: Comfortable (2-2.5rem)
- ✅ Desktop: Spacious (2.5-3rem)
- ✅ Scales smoothly between breakpoints

### **4. QR Code Scaling**
```
Mobile Small:  200px × 200px
Mobile Large:  240px × 240px
Tablet:        300px × 300px
Desktop:       320px × 320px
```

### **5. Typography Scaling**
```
Element          | Mobile  | Tablet  | Desktop
-------------------------------------------------
Page Title       | 0.95rem | 1.15rem | 1.25rem
Total Amount     | 1.4rem  | 2rem    | 2.25rem
Body Text        | 0.8rem  | 0.875rem| 0.875rem
```

## 🎨 Design Benefits

### **For Tablet Users**
- ✅ Full-width utilization
- ✅ Larger QR code (300px)
- ✅ Comfortable padding (2-2.5rem)
- ✅ Centered content for focus
- ✅ Professional appearance

### **For Desktop Users**
- ✅ Full-width professional layout
- ✅ Maximum QR code size (320px)
- ✅ Generous spacing (2.5-3rem)
- ✅ Centered content prevents eye strain
- ✅ Premium feel

### **For Mobile Users**
- ✅ Full-width maximizes screen space
- ✅ Appropriate QR size (200-240px)
- ✅ Compact but readable
- ✅ Touch-optimized
- ✅ No wasted space

## 📱 Testing Results

### **Tablet Landscape (1024×768)**
- ✅ Full width utilized
- ✅ QR code 300px (clearly visible)
- ✅ Content centered at 800px
- ✅ Comfortable padding
- ✅ Professional appearance

### **Desktop (1920×1080)**
- ✅ Full width utilized
- ✅ QR code 320px (optimal size)
- ✅ Content centered at 800px
- ✅ Generous white space
- ✅ Premium feel

### **Mobile (375×667)**
- ✅ Full width utilized
- ✅ QR code 200px (scannable)
- ✅ Compact layout
- ✅ No horizontal scroll
- ✅ Touch-friendly

## 🚀 Performance

- ✅ No layout shifts
- ✅ Smooth transitions
- ✅ Optimized rendering
- ✅ Fast paint times
- ✅ Minimal reflows

---

**Status**: ✅ Fully Responsive (Full Width)
**Layout**: Full width container with centered content
**Tested**: Mobile, Tablet, Desktop
**Last Updated**: 2026-04-23
