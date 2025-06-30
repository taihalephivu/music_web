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
    if (typeof window.cartData !== 'undefined') {
        window.cart = window.cartData;
        updateCartCount();
        // Nếu đã đăng nhập, xóa localStorage cũ để tránh lỗi
        if (window.isLoggedIn) {
            localStorage.removeItem('musicStoreCart');
        }
        return;
    }
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

    const cart = window.cartData || [];
    if (!cart.length) {
        cartItems.innerHTML = `
            <div style="text-align: center; padding: 2rem; animation: fadeIn 0.5s;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <p>Giỏ hàng trống</p>
            </div>
        `;
    } else {
        renderCartItems(cartItems, cart);
    }
    modal.style.display = 'block';
}

function renderCartItems(cartItems, cart) {
    if (!cart || cart.length === 0) {
        cartItems.innerHTML = `
            <div style="text-align: center; padding: 2rem; animation: fadeIn 0.5s;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <p>Giỏ hàng trống</p>
            </div>
        `;
    } else {
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartItems.innerHTML = cart.map(item => `
            <div class="cart-item" style="display:flex;align-items:center;gap:16px;padding:12px 0;border-bottom:1px solid #eee;animation:fadeInUp 0.4s;">
                <img src="${item.image_url ? item.image_url : 'assets/images/default.jpg'}" 
                     alt="${item.name}" 
                     class="cart-item-image"
                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.07);"
                     onerror="this.src='assets/images/default.jpg'">
                <div class="cart-item-info" style="flex:1;">
                    <div class="cart-item-name" style="font-weight:600;color:#222;">${item.name}</div>
                    <div class="cart-item-price" style="color:#4caf50;font-weight:500;">${formatPrice(item.price)}</div>
                </div>
                <div class="cart-item-quantity" style="font-size:1rem;color:#2196f3;font-weight:600;">x${item.quantity}</div>
                <button class="btn-delete-item" onclick="deleteCartItem(${item.id})" style="background:#ff5252;color:#fff;border:none;border-radius:6px;padding:8px 12px;cursor:pointer;transition:background 0.2s;"><i class="fas fa-trash"></i></button>
            </div>
        `).join('') + `
            <div class="cart-total" style="text-align:right;font-weight:700;color:#2196f3;font-size:1.1rem;margin-top:18px;animation:fadeIn 0.5s;">
                Tổng cộng: ${formatPrice(total)}
            </div>
            <div style="margin-top: 1.5rem; text-align: center;animation:fadeIn 0.5s;">
                <button class="btn" onclick="checkout()" style="margin-right: 1rem;">
                    <i class="fas fa-credit-card"></i> Thanh toán
                </button>
            </div>
        `;
    }
}

// Hiệu ứng CSS động cho modal/cart-item
const style = document.createElement('style');
style.innerHTML = `
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: none; } }
.cart-item { animation: fadeInUp 0.4s; }
`;
document.head.appendChild(style);

function deleteCartItem(id) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
    // Gọi API xóa sản phẩm khỏi giỏ hàng
    fetch('add_to_cart.php?action=delete_item&id=' + id, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reload lại trang để cập nhật giỏ hàng mới
                location.reload();
            } else {
                showNotification('Xóa sản phẩm thất bại!', 'error');
            }
        })
        .catch(() => showNotification('Lỗi kết nối!', 'error'));
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
    // Lấy lại giỏ hàng từ DB trước khi thanh toán
    fetch('add_to_cart.php?action=get_cart')
        .then(res => res.json())
        .then(cart => {
            if (!cart || cart.length === 0) {
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
            input.value = JSON.stringify(cart);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
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

// Tự động load cart khi trang load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadCart);
} else {
    loadCart();
} 