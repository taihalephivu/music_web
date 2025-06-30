// Biến toàn cục
window.cart = window.cart || [];

// Thêm vào giỏ hàng
function addToCart(instrumentId) {
    if (!window.isLoggedIn) {
        window.showLoginModal();
        return;
    }
    const instrument = window.instruments.find(i => i.id === instrumentId);
    if (!instrument) return;
    const existingItem = window.cart.find(item => item.id === instrumentId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        window.cart.push({
            id: instrument.id,
            name: instrument.name,
            price: instrument.price,
            image_url: instrument.image_url,
            quantity: 1
        });
    }
    saveCart();
    updateCartCount();
    showNotification('Đã thêm vào giỏ hàng!');
}

function saveCart() {
    localStorage.setItem('musicStoreCart', JSON.stringify(window.cart));
}

function loadCart() {
    const savedCart = localStorage.getItem('musicStoreCart');
    if (savedCart) {
        window.cart = JSON.parse(savedCart);
        updateCartCount();
    }
}

function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = window.cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

function showCart() {
    const modal = document.getElementById('cartModal');
    if (!modal) return;
    const cartItems = document.getElementById('cartItems');
    if (!cartItems) return;
    if (window.cart.length === 0) {
        cartItems.innerHTML = `
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <p>Giỏ hàng trống</p>
            </div>
        `;
    } else {
        const total = window.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartItems.innerHTML = window.cart.map(item => `
            <div class="cart-item">
                <img src="${item.image_url ? item.image_url : 'assets/images/default.jpg'}" 
                     alt="${item.name}" 
                     class="cart-item-image"
                     onerror="this.src='assets/images/default.jpg'">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${formatPrice(item.price)}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                </div>
            </div>
        `).join('') + `
            <div class="cart-total">
                Tổng cộng: ${formatPrice(total)}
            </div>
            <div style="margin-top: 1rem; text-align: center;">
                <button class="btn" onclick="checkout()" style="margin-right: 1rem;">
                    <i class="fas fa-credit-card"></i> Thanh toán
                </button>
                <button class="btn" onclick="clearCart()" style="background: #ff4757;">
                    <i class="fas fa-trash"></i> Xóa giỏ hàng
                </button>
            </div>
        `;
    }
    modal.style.display = 'block';
}

function updateQuantity(instrumentId, change) {
    const item = window.cart.find(item => item.id === instrumentId);
    if (!item) return;
    item.quantity += change;
    if (item.quantity <= 0) {
        window.cart = window.cart.filter(item => item.id !== instrumentId);
    }
    saveCart();
    updateCartCount();
    showCart();
}

function clearCart() {
    if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        window.cart = [];
        saveCart();
        updateCartCount();
        showCart();
        showNotification('Đã xóa giỏ hàng!');
    }
}

function checkout() {
    if (window.cart.length === 0) {
        showNotification('Giỏ hàng trống!', 'error');
        return;
    }
    // Gửi dữ liệu giỏ hàng qua form ẩn tới PHP
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'user/checkout.php';
    form.style.display = 'none';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'cart_json';
    input.value = JSON.stringify(window.cart);
    form.appendChild(input);
    document.body.appendChild(form);
    // Xóa giỏ hàng localStorage sau khi submit
    form.addEventListener('submit', function() {
        window.cart = [];
        saveCart();
        updateCartCount();
    });
    form.submit();
}

function closeCart() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? '#ff4757' : '#27ae60'};
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        z-index: 10001;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function showError(message) {
    const grid = document.getElementById('musicGrid');
    if (grid) {
        grid.innerHTML = `
            <div style="color: white; text-align: center; grid-column: 1 / -1; padding: 2rem;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; color: #ff4757;"></i>
                <p>${message}</p>
                <button class="btn" onclick="loadInstruments()" style="margin-top: 1rem;">
                    <i class="fas fa-redo"></i> Thử lại
                </button>
            </div>
        `;
    }
}

window.addToCart = addToCart;
window.saveCart = saveCart;
window.loadCart = loadCart;
window.updateCartCount = updateCartCount;
window.showCart = showCart;
window.updateQuantity = updateQuantity;
window.clearCart = clearCart;
window.checkout = checkout;
window.closeCart = closeCart;
window.formatPrice = formatPrice;
window.showNotification = showNotification;
window.showError = showError; 