// Biến toàn cục
window.instruments = [];
window.filteredInstruments = [];
window.cart = window.cart || [];

// Khởi tạo trang web
window.addEventListener('DOMContentLoaded', function() {
    window.loadCart && window.loadCart();
    setupEventListeners();
});

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

// Thêm vào giỏ hàng
function addToCart(instrumentId) {
    if (!window.isLoggedIn) {
        showLoginModal();
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
            window.saveCart();
            window.updateCartCount();
            showNotification('Đã thêm vào giỏ hàng!');
        } else {
            showNotification('Thêm vào giỏ hàng thất bại!', 'error');
        }
    })
    .catch(error => {
        showNotification('Lỗi kết nối!', 'error');
    });
}
window.addToCart = addToCart;

// Lưu giỏ hàng vào localStorage
function saveCart() {
    localStorage.setItem('musicStoreCart', JSON.stringify(window.cart));
}

// Tải giỏ hàng từ localStorage
function loadCart() {
    const savedCart = localStorage.getItem('musicStoreCart');
    if (savedCart) {
        window.cart = JSON.parse(savedCart);
        window.updateCartCount();
    }
}

// Cập nhật số lượng trong giỏ hàng
function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = window.cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// Hiển thị giỏ hàng
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

// Cập nhật số lượng trong giỏ hàng
function updateQuantity(instrumentId, change) {
    const item = window.cart.find(item => item.id === instrumentId);
    if (!item) return;

    item.quantity += change;
    
    if (item.quantity <= 0) {
        window.cart = window.cart.filter(item => item.id !== instrumentId);
    }
    
    window.saveCart();
    window.updateCartCount();
    showCart(); // Refresh cart display
}

// Xóa giỏ hàng
function clearCart() {
    if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        window.cart = [];
        window.saveCart();
        window.updateCartCount();
        showCart();
        showNotification('Đã xóa giỏ hàng!');
    }
}

// Thanh toán
function checkout() {
    if (window.cart.length === 0) {
        showNotification('Giỏ hàng trống!', 'error');
        return;
    }
    
    // Ở đây bạn có thể thêm logic thanh toán
    alert('Chức năng thanh toán sẽ được phát triển sau!');
}

// Đóng giỏ hàng
function closeCart() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Hiển thị chi tiết dụng cụ âm nhạc
function showInstrumentDetails(instrumentId) {
    const instrument = window.instruments.find(i => i.id === instrumentId);
    if (!instrument) return;

    const modal = document.createElement('div');
    modal.className = 'cart-modal';
    modal.style.display = 'block';
    
    modal.innerHTML = `
        <div class="cart-content" style="max-width: 800px;">
            <div class="cart-header">
                <h3 class="cart-title">Chi tiết sản phẩm</h3>
                <button class="close-cart" onclick="this.closest('.cart-modal').remove()">&times;</button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
                <div>
                    <img src="${instrument.image_url ? instrument.image_url : 'assets/images/default.jpg'}" 
                         alt="${instrument.name}" 
                         style="width: 100%; border-radius: 10px;"
                         onerror="this.src='assets/images/default.jpg'">
                </div>
                <div>
                    <h2 style="color: #333; margin-bottom: 1rem;">${instrument.name}</h2>
                    <p style="color: #667eea; font-weight: 600; margin-bottom: 0.5rem;">${instrument.brand_name || 'Thương hiệu'}</p>
                    <p style="color: #666; margin-bottom: 1rem;">${instrument.category_name || 'Danh mục'}</p>
                    <h3 style="color: #ff4757; font-size: 1.5rem; margin-bottom: 1rem;">${instrument.formatted_price}</h3>
                    <p style="color: #27ae60; margin-bottom: 1rem;">
                        <i class="fas fa-box"></i> Còn lại: ${instrument.stock_quantity} sản phẩm
                    </p>
                    <p style="color: #666; line-height: 1.6; margin-bottom: 2rem;">${instrument.description || 'Không có mô tả'}</p>
                    ${instrument.specifications ? `
                        <div style="margin-bottom: 2rem;">
                            <h4 style="color: #333; margin-bottom: 0.5rem;">Thông số kỹ thuật:</h4>
                            <p style="color: #666; line-height: 1.6;">${instrument.specifications}</p>
                        </div>
                    ` : ''}
                    <button class="btn-add-cart" onclick="addToCart(${instrument.id}); this.closest('.cart-modal').remove();" style="width: 100%; padding: 15px;">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Hiển thị thông báo
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

// Hiển thị lỗi
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

// Thêm CSS cho animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Đóng modal khi click bên ngoài
document.addEventListener('click', function(event) {
    const modal = document.getElementById('cartModal');
    if (modal && event.target === modal) {
        closeCart();
    }
});

// Đóng modal khi nhấn ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCart();
    }
});

// Hiển thị modal hồ sơ user (lấy từ database)
function showProfileModal() {
    const modal = document.createElement('div');
    modal.className = 'cart-modal';
    modal.style.display = 'block';
    modal.innerHTML = '<div style="padding:2rem; text-align:center;"><div class="loading"></div>Đang tải hồ sơ...</div>';
    document.body.appendChild(modal);
    fetch('page/profile.php')
        .then(res => res.text())
        .then(html => { modal.innerHTML = html; })
        .catch(() => { modal.innerHTML = '<div style="padding:2rem; color:#ff4757;">Lỗi tải hồ sơ!</div>'; });
}

// Hiện modal đăng nhập nếu chưa đăng nhập
function showLoginModal() {
    const modal = document.createElement('div');
    modal.className = 'cart-modal';
    modal.style.display = 'block';
    modal.innerHTML = `
        <div class="cart-content" style="max-width: 350px;">
            <div class="cart-header">
                <h3 class="cart-title">Bạn cần đăng nhập</h3>
                <button class="close-cart" onclick="this.closest('.cart-modal').remove()">&times;</button>
            </div>
            <div style="padding: 1.5rem 0; text-align: center;">
                <i class="fas fa-sign-in-alt" style="font-size: 2.5rem; color: #4f8cff;"></i>
                <p style="margin: 1rem 0;">Vui lòng đăng nhập để sử dụng chức năng giỏ hàng.</p>
                <a href="page/login.php" class="btn" style="width: 100%;">Đăng nhập</a>
                <a href="page/register.php" class="btn" style="width: 100%; background: #51cf66; color: #fff; margin-top: 0.7rem;">Đăng ký</a>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
} 