// Biến toàn cục
window.cartData = window.cartData || [];

// Thêm vào giỏ hàng
function addToCart(instrumentId) {
    if (!window.isLoggedIn) {
        window.showLoginModal();
        return;
    }
    const instrument = window.instruments.find(i => i.id === instrumentId);
    if (!instrument) return;

    // Gửi AJAX lên server
    const formData = new FormData();
    formData.append('product_id', instrumentId);

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Cập nhật số giỏ hàng
            updateCartCount(data.cartCount || 0);
            showNotification('Đã thêm vào giỏ hàng!');
        } else {
            showNotification('Thêm vào giỏ hàng thất bại!', 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi kết nối!', 'error');
    });
}

// Cập nhật số giỏ hàng đơn giản
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cartCount');
    if (!cartCountElement) {
        // Tạo element nếu chưa có
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon && count > 0) {
            const newCartCount = document.createElement('span');
            newCartCount.id = 'cartCount';
            newCartCount.className = 'cart-count';
            newCartCount.style.cssText = 'position:absolute;top:-8px;right:-10px;background:#ff4757;color:#fff;font-size:0.8rem;font-weight:700;padding:2px 6px;border-radius:10px;min-width:18px;text-align:center;box-shadow:0 2px 8px #ff475799;';
            newCartCount.textContent = count;
            cartIcon.appendChild(newCartCount);
        }
    } else {
        if (count > 0) {
            cartCountElement.textContent = count;
            cartCountElement.style.display = 'block';
        } else {
            cartCountElement.remove();
        }
    }
}

// Cập nhật số giỏ hàng từ server
function refreshCartCount() {
    if (!window.isLoggedIn) return;
    
    fetch('get_cart_count.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.count);
            }
        })
        .catch(error => {
            console.log('Không thể cập nhật số giỏ hàng');
        });
}

function saveCart() {
    localStorage.setItem('musicStoreCart', JSON.stringify(window.cartData || []));
}

function loadCart() {
    if (typeof window.cartData !== 'undefined') {
        // Nếu đã đăng nhập, xóa localStorage cũ để tránh lỗi
        if (window.isLoggedIn) {
            localStorage.removeItem('musicStoreCart');
        }
        return;
    }
    const savedCart = localStorage.getItem('musicStoreCart');
    if (savedCart) {
        window.cartData = JSON.parse(savedCart);
        const totalItems = window.cartData.reduce((sum, item) => sum + item.quantity, 0);
        updateCartCount(totalItems);
    }
}

function showCart() {
    window.location.href = 'cart.php';
}

function updateQuantity(instrumentId, change) {
    const item = window.cartData.find(item => item.id === instrumentId);
    if (!item) return;
    item.quantity += change;
    if (item.quantity <= 0) {
        window.cartData = window.cartData.filter(item => item.id !== instrumentId);
    }
    saveCart();
    updateCartCount();
    showCart();
}

function checkout() {
    window.location.href = 'user/checkout.php';
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

// Hàm clearCart
function clearCart() {
    if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        window.cartData = [];
        saveCart();
        updateCartCount(0);
        showNotification('Đã xóa giỏ hàng!');
    }
}

// Hàm closeCart
function closeCart() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

window.addToCart = addToCart;
window.saveCart = saveCart;
window.loadCart = loadCart;
window.updateCartCount = updateCartCount;
window.refreshCartCount = refreshCartCount;
window.showCart = showCart;
window.updateQuantity = updateQuantity;
window.clearCart = clearCart;
window.checkout = checkout;
window.closeCart = closeCart;
window.formatPrice = formatPrice;
window.showNotification = showNotification;
window.showError = showError;

// Thiết lập các event listeners
function setupEventListeners() {
    // Tìm kiếm
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', window.filterHomeProducts || function(){});
    }
    // Smooth scrolling cho navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Tự động load cart khi trang load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
        setupEventListeners();
    });
} else {
    loadCart();
    setupEventListeners();
} 