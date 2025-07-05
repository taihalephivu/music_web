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
            // Cập nhật localStorage
            const existingItem = (window.cartData || []).find(item => item.id === instrumentId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                if (!window.cartData) window.cartData = [];
                window.cartData.push({
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
        } else {
            showNotification('Thêm vào giỏ hàng thất bại!', 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi kết nối!', 'error');
    });
}

function saveCart() {
    localStorage.setItem('musicStoreCart', JSON.stringify(window.cartData || []));
}

function loadCart() {
    if (typeof window.cartData !== 'undefined') {
        updateCartCount();
        // Nếu đã đăng nhập, xóa localStorage cũ để tránh lỗi
        if (window.isLoggedIn) {
            localStorage.removeItem('musicStoreCart');
        }
        return;
    }
    const savedCart = localStorage.getItem('musicStoreCart');
    if (savedCart) {
        window.cartData = JSON.parse(savedCart);
        updateCartCount();
    }
}

function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = (window.cartData || []).reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

function showCart() {
    window.location.href = 'cart.php';
}



function updateQuantity(instrumentId, change) {
    fetch('add_to_cart.php?action=update_quantity', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: instrumentId, change: change })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            showNotification('Cập nhật số lượng thất bại!', 'error');
        }
    })
    .catch(() => {
        showNotification('Lỗi kết nối!', 'error');
    });
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
window.toggleSelectAll = toggleSelectAll;
window.updateDeleteButton = updateDeleteButton;
window.deleteSelectedItems = deleteSelectedItems;

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