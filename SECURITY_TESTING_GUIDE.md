# Security System Testing Guide

## Quick Test Scenarios

### ✅ Scenario 1: Normal Customer (Should Work)
**Steps:**
1. Browse products
2. Click "Add to Cart" → CAPTCHA appears
3. Complete CAPTCHA
4. Product added to cart
5. Click "Lanjut ke Checkout" → CAPTCHA appears again
6. Complete CAPTCHA
7. Fill form with valid data:
   - Name: "John Doe" (min 3 chars)
   - Phone: "08123456789" (min 10 digits)
   - Address: Complete address
8. Submit order
9. **Expected**: Order created successfully ✅

**Console Check:**
```javascript
// No errors
// localStorage.lastCheckoutTime should be set
```

---

### ⚠️ Scenario 2: Suspicious Activity (Should Still Work)
**Steps:**
1. Create 4 orders within 1 minute
2. Each order should work
3. **Expected**: 
   - All orders created ✅
   - Orders marked as "suspicious" in admin panel ⚠️
   - Risk score: 40-69

**Admin Check:**
- Go to `/admin/fraud`
- See suspicious orders with risk factors
- Orders are NOT blocked, just flagged

---

### 🚫 Scenario 3: Blocked User (Should NOT Work)
**Steps:**
1. Admin blocks customer phone or IP
2. User tries to checkout
3. **Expected**: 
   - Blocked message appears 🚫
   - "⚠️ Akses dibatasi sementara. Silakan hubungi admin."
   - Order NOT created ❌

**How to Block:**
```php
// In admin panel or tinker
$reputation = CustomerReputation::where('phone', '08123456789')->first();
$reputation->update(['status' => 'blacklisted']);

// OR block IP
SecurityLog::blockIp('127.0.0.1', 60); // 60 minutes
```

---

### ⏱️ Scenario 4: Rate Limiting (Should Show Cooldown)
**Steps:**
1. Complete a checkout
2. Immediately try to checkout again (within 10 seconds)
3. **Expected**: 
   - Message: "⏳ Mohon tunggu X detik sebelum melakukan pesanan lagi"
   - Cannot proceed until cooldown expires

**Test in Console:**
```javascript
// Check last checkout time
localStorage.getItem('lastCheckoutTime')

// Clear cooldown (for testing)
localStorage.removeItem('lastCheckoutTime')
```

---

### ❌ Scenario 5: Invalid Input (Should Show Warnings)

#### Test 5a: Invalid Phone
**Steps:**
1. Enter phone: "123" (less than 10 digits)
2. Try to submit
3. **Expected**: "⚠️ Nomor telepon harus minimal 10 digit."

#### Test 5b: Invalid Name
**Steps:**
1. Enter name: "AB" (less than 3 chars)
2. Try to submit
3. **Expected**: "⚠️ Nama harus diisi minimal 3 karakter."

#### Test 5c: Too Many Items
**Steps:**
1. Add 11 items to cart
2. Try to checkout
3. **Expected**: "⚠️ Maksimal 10 item per pesanan."

---

### 🔐 Scenario 6: CAPTCHA Verification
**Steps:**
1. Click "Add to Cart"
2. **Expected**: CAPTCHA modal appears
3. Complete CAPTCHA
4. **Expected**: Product added to cart
5. Try to checkout without completing CAPTCHA
6. **Expected**: Cannot proceed

**Test CAPTCHA Bypass:**
```javascript
// This should NOT work
window.recaptchaToken = null;
verifyCaptchaAndCheckout();
// Expected: "Silakan selesaikan verifikasi reCAPTCHA terlebih dahulu"
```

---

## Admin Panel Testing

### View Suspicious Orders
1. Go to `/admin/fraud`
2. Check orders with risk_score >= 40
3. View risk factors
4. Manually approve/reject

### View Security Logs
1. Go to `/admin/security`
2. Check IP addresses
3. Check order attempts
4. Block/unblock IPs

### Manage Customer Reputation
1. View customer list
2. Check reputation status (normal/suspicious/trusted/blacklisted)
3. Manually change status
4. Block/unblock customers

---

## Console Commands for Testing

### Check Fraud Detection
```bash
php artisan tinker

# Get fraud detection for order
$fraud = \App\Models\FraudDetection::where('order_id', 1)->first();
echo $fraud->risk_score;
print_r($fraud->risk_factors);
```

### Check Security Logs
```bash
php artisan tinker

# Get today's order attempts
$logs = \App\Models\SecurityLog::whereDate('created_at', today())->get();
foreach($logs as $log) {
    echo "{$log->ip_address} - {$log->phone_number} - {$log->order_count} orders\n";
}
```

### Block/Unblock IP
```bash
php artisan tinker

# Block IP for 60 minutes
\App\Models\SecurityLog::blockIp('127.0.0.1', 60);

# Check if IP is blocked
\App\Models\SecurityLog::isIpBlocked('127.0.0.1'); // true

# Unblock IP
\App\Models\SecurityLog::where('ip_address', '127.0.0.1')
    ->update(['is_blocked' => false, 'blocked_until' => null]);
```

### Manage Customer Reputation
```bash
php artisan tinker

# Get customer reputation
$rep = \App\Models\CustomerReputation::where('phone', '08123456789')->first();

# Blacklist customer
$rep->update(['status' => 'blacklisted']);

# Check if blacklisted
$rep->isBlacklisted(); // true

# Unblock customer
$rep->update(['status' => 'normal']);
```

---

## Browser Console Testing

### Check Rate Limiting
```javascript
// Check last checkout time
console.log('Last checkout:', localStorage.getItem('lastCheckoutTime'));

// Calculate time since last checkout
const lastTime = parseInt(localStorage.getItem('lastCheckoutTime'));
const now = Date.now();
const secondsSince = (now - lastTime) / 1000;
console.log('Seconds since last checkout:', secondsSince);

// Clear cooldown (for testing)
localStorage.removeItem('lastCheckoutTime');
```

### Check Cart Items
```javascript
// View cart
console.log('Cart:', cart);

// Count total items
const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
console.log('Total items:', totalItems);

// Add 11 items (should fail)
cart = [{id: 1, quantity: 11}];
updateCart();
goToCheckout(); // Should show error
```

### Test CAPTCHA
```javascript
// Check CAPTCHA token
console.log('reCAPTCHA token:', window.recaptchaToken);

// Simulate CAPTCHA success
onRecaptchaSuccess('test-token-123');
console.log('Token after success:', window.recaptchaToken);
```

---

## Expected Behavior Summary

| Scenario | User Action | System Response | Order Created? |
|----------|-------------|-----------------|----------------|
| Normal user | Valid checkout | Success ✅ | Yes ✅ |
| Suspicious (4 orders/min) | Valid checkout | Success ⚠️ | Yes (flagged) ⚠️ |
| Blocked IP | Try checkout | Blocked message 🚫 | No ❌ |
| Blocked customer | Try checkout | Blocked message 🚫 | No ❌ |
| Invalid phone | Submit form | Warning message ⚠️ | No ❌ |
| Invalid name | Submit form | Warning message ⚠️ | No ❌ |
| Too many items | Try checkout | Warning message ⚠️ | No ❌ |
| Within cooldown | Try checkout | Cooldown message ⏱️ | No ❌ |
| No CAPTCHA | Try checkout | CAPTCHA required 🔐 | No ❌ |

---

## Troubleshooting

### Issue: CAPTCHA not working
**Solution:**
1. Check `.env` file:
   ```
   RECAPTCHA_SITE_KEY=your_site_key
   RECAPTCHA_SECRET_KEY=your_secret_key
   ```
2. Check browser console for errors
3. Verify reCAPTCHA script loaded: `typeof grecaptcha`

### Issue: Rate limiting not working
**Solution:**
1. Check localStorage: `localStorage.getItem('lastCheckoutTime')`
2. Clear localStorage: `localStorage.clear()`
3. Check browser console for errors

### Issue: Blocked message not showing
**Solution:**
1. Check if customer is actually blocked:
   ```php
   $rep = CustomerReputation::where('phone', '08123456789')->first();
   echo $rep->status; // Should be 'blacklisted'
   ```
2. Check if IP is blocked:
   ```php
   SecurityLog::isIpBlocked('127.0.0.1'); // Should return true
   ```
3. Check browser console for 403 error

### Issue: Fraud detection not working
**Solution:**
1. Check logs: `tail -f storage/logs/laravel.log`
2. Check database: `SELECT * FROM fraud_detections ORDER BY id DESC LIMIT 10;`
3. Verify fail-safe is working (should create record with risk_score = 0 on error)

---

## Performance Testing

### Load Test
```bash
# Test 100 concurrent requests
ab -n 100 -c 10 http://localhost:8000/

# Expected: No errors, response time < 500ms
```

### Database Query Test
```bash
php artisan tinker

# Test fraud detection performance
$start = microtime(true);
$fraud = \App\Models\FraudDetection::analyzeOrder($order, '127.0.0.1');
$end = microtime(true);
echo "Time: " . ($end - $start) . " seconds\n";
// Expected: < 0.1 seconds
```

---

## Security Checklist

- [x] CAPTCHA required before checkout
- [x] Rate limiting (10s cooldown)
- [x] Phone validation (min 10 digits)
- [x] Name validation (min 3 chars)
- [x] Max items validation (10 items)
- [x] IP blocking works
- [x] Customer blacklist works
- [x] Fraud detection works
- [x] Fail-safe mechanism works
- [x] Admin can view suspicious orders
- [x] Admin can block/unblock users
- [x] Security logs working
- [x] No false positives (normal users not blocked)
- [x] Performance not affected

---

**Testing Status**: Ready for QA ✅
**Last Updated**: April 26, 2026
