// Dapoer Budess - Main Application JavaScript

// Global Variables
let cart = [];
let currentPhone = null;
let messagePollingInterval = null;
let lastMessageCount = 0;
let currentSlide = 0;

// Products Data
const products = [
    { id: 1, name: 'Roti Coklat Premium', price: 15000, discount: 12000, image: '🍫', description: 'Roti lembut dengan coklat premium', stock: 20, bestseller: true },
    { id: 2, name: 'Roti Keju Spesial', price: 18000, discount: 15000, image: '🧀', description: 'Roti dengan keju mozzarella asli', stock: 15, bestseller: true },
    { id: 3, name: 'Roti Kismis Manis', price: 12000, discount: 10000, image: '🍇', description: 'Roti manis dengan kismis pilihan', stock: 25, bestseller: false },
    { id: 4, name: 'Roti Tawar Gandum', price: 20000, discount: 17000, image: '🌾', description: 'Roti gandum sehat dan bergizi', stock: 18, bestseller: true },
    { id: 5, name: 'Roti Pisang Coklat', price: 16000, discount: 13000, image: '🍌', description: 'Kombinasi pisang dan coklat', stock: 12, bestseller: false },
    { id: 6, name: 'Roti Abon Sapi', price: 22000, discount: 19000, image: '🥩', description: 'Roti dengan abon sapi premium', stock: 10, bestseller: true }
];

// Initialize on DOM Load
document.addEventListener('DOMContentLoaded', () => {
    initializeApp();
    setupEventListeners();
    loadSavedSession();
});

function initializeApp() {
    renderProducts('productsGrid', false);
    renderProducts('bestsellerGrid', true);
    renderProducts('bestsellerHome', true);
    initStarRating();
    initSlider();
}

function setupEventListeners() {
    document.addEventListener('keydown', handleEscapeKey);
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.addEventListener('keydown', handleChatEnter);
    }
}

function handleEscapeKey(e) {
    if (e.key === 'Escape') {
        closeAllModals();
    }
}

function handleChatEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendChatMessage(new Event('submit'));
    }
}

function closeAllModals() {
    toggleCart();
    closeMessageModal();
    closeReviewModal();
    closeCaptchaModal();
    closeUploadModal();
    closePurchaseModal();
    document.getElementById('successMessage')?.classList.remove('active');
}

function loadSavedSession() {
    const savedPhone = localStorage.getItem('customerPhone');
    if (savedPhone) {
        currentPhone = savedPhone;
        startMessagePolling();
    }
}

// Slider Functions
function initSlider() {
    const slides = document.querySelectorAll('.slide');
    if (slides.length === 0) return;
    
    slides[0].classList.add('active');
    setInterval(() => nextSlide(), 5000);
}

function nextSlide() {
    const slides = document.querySelectorAll('.slide');
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
}

function prevSlide() {
    const slides = document.querySelectorAll('.slide');
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
}

function goToSlide(index) {
    const slides = document.querySelectorAll('.slide');
    slides[currentSlide].classList.remove('active');
    currentSlide = index;
    slides[currentSlide].classList.add('active');
}

// Product Functions
function renderProducts(containerId, bestsellersOnly) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const filtered = bestsellersOnly ? products.filter(p => p.bestseller) : products;
    container.innerHTML = filtered.map(product => createProductCard(product)).join('');
}

function createProductCard(product) {
    const discountPercent = Math.round(((product.price - product.discount) / product.price) * 100);
    return `
        <div class="bg-white rounded-2xl overflow-visible shadow-lg transition-all duration-400 hover:-translate-y-2 hover:shadow-2xl relative border-3 border-yellow-600 product-card">
            ${product.bestseller ? '<div class="bestseller-badge">⭐ TERLARIS</div>' : ''}
            <div class="relative pt-[75%] overflow-hidden bg-gray-100">
                <div class="absolute inset-0 flex items-center justify-center text-6xl">${product.image}</div>
                ${product.discount < product.price ? `<div class="product-promo-badge">HEMAT ${discountPercent}%</div>` : ''}
            </div>
            <div class="p-6">
                <h3 class="font-playfair text-xl font-bold text-gray-800 mb-2">${product.name}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${product.description}</p>
                <div class="stock-badge">Stok: ${product.stock}</div>
                <div class="flex items-baseline gap-3 mb-4">
                    ${product.discount < product.price ? `<span class="text-sm text-gray-400 line-through">Rp ${product.price.toLocaleString()}</span>` : ''}
                    <span class="text-xl font-bold text-amber-800">Rp ${product.discount.toLocaleString()}</span>
                </div>
                <button onclick="addToCart(${product.id})" ${product.stock === 0 ? 'disabled' : ''} 
                    class="w-full py-3 rounded-xl bg-amber-800 text-white font-semibold hover:bg-amber-700 transition-all duration-300 flex items-center justify-center gap-2 disabled:bg-gray-300 disabled:cursor-not-allowed">
                    🛒 Tambah ke Keranjang
                </button>
            </div>
        </div>
    `;
}

// Cart Functions
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || product.stock === 0) return;
    
    const existing = cart.find(item => item.id === productId);
    if (existing) {
        if (existing.quantity < product.stock) {
            existing.quantity++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    
    updateCart();
    showNotification('Produk ditambahkan ke keranjang!');
}

function updateCart() {
    const cartCount = document.getElementById('cartCount');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
    
    renderCartItems();
}

function renderCartItems() {
    const container = document.getElementById('cartItemsContainer');
    if (!container) return;
    
    if (cart.length === 0) {
        container.innerHTML = '<div class="empty-cart"><div class="empty-cart-icon">🛒</div><p>Keranjang kosong</p></div>';
        return;
    }
    
    container.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-image">${item.image}</div>
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">Rp ${item.discount.toLocaleString()}</div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    <button class="remove-item" onclick="removeFromCart(${item.id})">Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
    
    updateCartSummary();
}

function updateQuantity(productId, change) {
    const item = cart.find(i => i.id === productId);
    const product = products.find(p => p.id === productId);
    
    if (!item || !product) return;
    
    const newQuantity = item.quantity + change;
    if (newQuantity <= 0) {
        removeFromCart(productId);
    } else if (newQuantity <= product.stock) {
        item.quantity = newQuantity;
        updateCart();
    } else {
        alert('Stok tidak mencukupi!');
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCart();
}

function updateCartSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.discount * item.quantity), 0);
    const shipping = 15000;
    const total = subtotal + shipping;
    
    document.getElementById('cartSubtotal').textContent = `Rp ${subtotal.toLocaleString()}`;
    document.getElementById('cartShipping').textContent = `Rp ${shipping.toLocaleString()}`;
    document.getElementById('cartTotal').textContent = `Rp ${total.toLocaleString()}`;
}

function toggleCart() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('cartOverlay');
    
    if (modal && overlay) {
        modal.classList.toggle('active');
        overlay.classList.toggle('active');
    }
}

// Notification Function
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-20 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slideInRight';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Star Rating
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
        star.addEventListener('mouseover', function() {
            highlightStars(this.getAttribute('data-value'));
        });
    });
    
    container.addEventListener('mouseleave', function() {
        highlightStars(document.getElementById('ratingValue').value);
    });
}

function highlightStars(value) {
    document.querySelectorAll('.star').forEach(star => {
        star.classList.toggle('active', parseInt(star.getAttribute('data-value')) <= parseInt(value));
    });
}

// Message Polling
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
        const response = await fetch(`/messages/unread/${encodeURIComponent(currentPhone)}`);
        const data = await response.json();
        const badge = document.getElementById('msgBadge');
        
        if (badge) {
            if (data.unread_count > 0) {
                badge.style.display = 'flex';
                badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
            } else {
                badge.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('[Polling] Error:', error);
    }
}

// Scroll Handler
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (header) {
        header.classList.toggle('scrolled', window.scrollY > 50);
    }
});

// reCAPTCHA
function onRecaptchaSuccess(token) {
    window.recaptchaToken = token;
    const btn = document.getElementById('verifyCaptchaBtn');
    if (btn) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }
}

// Export functions to global scope
window.addToCart = addToCart;
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;
window.toggleCart = toggleCart;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.goToSlide = goToSlide;
window.onRecaptchaSuccess = onRecaptchaSuccess;
