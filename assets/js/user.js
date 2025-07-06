// Hiển thị modal hồ sơ user (lấy từ database)
function showProfileModal() {
    const modal = document.createElement('div');
    modal.className = 'cart-modal';
    modal.style.display = 'block';
    modal.innerHTML = '<div style="padding:2rem; text-align:center;"><div class="loading"></div>Đang tải hồ sơ...</div>';
    document.body.appendChild(modal);
    fetch('user/profile.php')
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
                <a href="login.php" class="btn" style="width: 100%;">Đăng nhập</a>
                <a href="register.php" class="btn" style="width: 100%; background: #51cf66; color: #fff; margin-top: 0.7rem;">Đăng ký</a>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

window.showProfileModal = showProfileModal;
window.showLoginModal = showLoginModal; 