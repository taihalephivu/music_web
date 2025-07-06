<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tính số lượng giỏ hàng đơn giản
$cartCount = 0;
if (isset($_SESSION['user']['id'])) {
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare('SELECT SUM(quantity) as total FROM cart WHERE user_id = ?');
    $stmt->execute([$_SESSION['user']['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cartCount = $result['total'] ?? 0;
}
?>
<!-- Header -->
<header class="header" style="background:#fff;box-shadow:0 2px 16px rgba(33,150,243,0.07);padding:0;">
    <nav class="nav-container" style="display:flex;align-items:center;justify-content:space-between;max-width:1300px;margin:0 auto;padding:0 32px;height:70px;">
        <!-- Logo bên trái -->
        <div class="nav-left" style="display:flex;align-items:center;gap:10px;">
            <a href="index.php" class="logo" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <img src="assets/images/logo/logo.png" alt="Logo" style="height:44px;width:auto;vertical-align:middle;margin-right:8px;display:inline-block;filter:drop-shadow(0 2px 8px #2196f3aa);">
                <span class="logo-text" style="font-size:1.5rem;font-weight:700;color:#2196f3;letter-spacing:1px;">Music Store</span>
            </a>
        </div>
        <!-- Menu căn giữa -->
        <ul class="nav-menu nav-center" style="display:flex;align-items:center;gap:32px;list-style:none;margin:0;padding:0;flex:1;justify-content:center;flex-wrap:nowrap;min-width:0;">
            <li class="dropdown" style="position:relative;">
                <a href="#categories" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Danh mục <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu" style="display:none;position:absolute;left:0;top:100%;background:#fff;min-width:180px;border-radius:0 0 12px 12px;box-shadow:0 8px 24px rgba(33,150,243,0.13);z-index:100;padding:0.5rem 0;border:1px solid #e0e0e0;">
                    <li><a href="category.php?cat=Guitar">Guitar</a></li>
                    <li><a href="category.php?cat=Piano">Piano</a></li>
                    <li><a href="category.php?cat=Drum">Drum</a></li>
                    <li><a href="category.php?cat=Violin">Violin</a></li>
                    <li><a href="category.php?cat=Wind Instruments">Nhạc cụ hơi</a></li>
                    <li><a href="category.php?cat=Accessories">Phụ kiện</a></li>
                </ul>
            </li>
            <li class="dropdown" style="position:relative;">
                <a href="#brands" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Thương hiệu <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu" style="display:none;position:absolute;left:0;top:100%;background:#fff;min-width:180px;border-radius:0 0 12px 12px;box-shadow:0 8px 24px rgba(33,150,243,0.13);z-index:100;padding:0.5rem 0;border:1px solid #e0e0e0;">
                    <li><a href="brand.php?brand=Fender">Fender</a></li>
                    <li><a href="brand.php?brand=Yamaha">Yamaha</a></li>
                    <li><a href="brand.php?brand=Roland">Roland</a></li>
                    <li><a href="brand.php?brand=Gibson">Gibson</a></li>
                    <li><a href="brand.php?brand=Pearl">Pearl</a></li>
                    <li><a href="brand.php?brand=Kawai">Kawai</a></li>
                </ul>
            </li>
            <li><a href="summer_sale.php" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Summer Sale</a></li>
            <li><a href="#blog" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Bài viết</a></li>
        </ul>
        <!-- Icon bên phải -->
        <div class="nav-right" style="display:flex;align-items:center;gap:18px;">
            <!-- Notification icon -->
            <div class="notification-dropdown" style="position:relative;">
                <i class="fas fa-bell notification-icon" tabindex="0" style="font-size:1.4rem;color:#111;cursor:pointer;padding:8px;border-radius:50%;transition:background 0.2s;"></i>
                <span class="notification-count" id="notificationCount" style="position:absolute;top:-2px;right:-2px;background:#ff4757;color:#fff;font-size:0.7rem;font-weight:700;padding:1px 4px;border-radius:8px;min-width:14px;text-align:center;box-shadow:0 2px 4px #ff475799;display:none;">0</span>
                <div class="notification-dropdown-content" style="display:none;position:absolute;right:0;top:120%;background:#fff;min-width:280px;border-radius:16px;box-shadow:0 8px 32px rgba(33,150,243,0.13);padding:18px 0;z-index:200;">
                    <div class="notification-header" style="font-weight:600;color:#2196f3;text-align:center;margin-bottom:15px;padding:0 20px;">Thông báo</div>
                    <div class="notification-list" id="notificationList" style="max-height:300px;overflow-y:auto;"></div>
                </div>
            </div>
            <!-- User icon -->
            <div class="user-dropdown" style="position:relative;">
                <i class="fas fa-user user-icon" tabindex="0" style="font-size:1.6rem;color:#111;cursor:pointer;border-radius:50%;padding:8px;transition:background 0.2s;"></i>
                <div class="user-dropdown-content" style="display:none;position:absolute;right:0;top:120%;background:#fff;min-width:210px;border-radius:16px;box-shadow:0 8px 32px rgba(33,150,243,0.13);padding:18px 0;z-index:200;">
<?php if (isset($_SESSION['user'])): ?>
    <div class="user-name" style="font-weight:600;color:#2196f3;text-align:center;margin-bottom:10px;">👋 <?php echo htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['username']); ?></div>
    <a href="user/profile.php" style="display:block;padding:10px 28px;color:#222;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-id-card" style="color:#111;"></i> Hồ sơ</a>
    <a href="user/history.php" style="display:block;padding:10px 28px;color:#222;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-history" style="color:#111;"></i> Lịch sử mua hàng</a>
    <a href="user/logout.php" style="display:block;padding:10px 28px;color:#ff4757;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-sign-out-alt" style="color:#111;"></i> Đăng xuất</a>
<?php elseif (isset($_SESSION['admin'])): ?>
    <div class="user-name" style="font-weight:600;color:#2196f3;text-align:center;margin-bottom:10px;">👨‍💼 <?php echo htmlspecialchars($_SESSION['admin']['full_name'] ?? $_SESSION['admin']['username']); ?></div>
    <a href="admin/index.php" style="display:block;padding:10px 28px;color:#222;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-cog" style="color:#111;"></i> Admin Panel</a>
    <a href="admin/logout.php" style="display:block;padding:10px 28px;color:#ff4757;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-sign-out-alt" style="color:#111;"></i> Đăng xuất</a>
<?php else: ?>
    <a href="login.php" style="display:block;padding:10px 28px;color:#2196f3;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-sign-in-alt" style="color:#111;"></i> Đăng nhập</a>
    <a href="register.php" style="display:block;padding:10px 28px;color:#2196f3;text-decoration:none;font-weight:500;transition:background 0.2s;"><i class="fas fa-user-plus" style="color:#111;"></i> Đăng ký</a>
<?php endif; ?>
                </div>
            </div>
            <!-- Cart icon -->
            <a href="cart.php" class="cart-icon" style="position:relative;cursor:pointer;text-decoration:none;">
                <i class="fas fa-shopping-cart" style="font-size:1.5rem;color:#111;"></i>
                <?php if ($cartCount > 0): ?>
                <span class="cart-count" id="cartCount" style="position:absolute;top:-8px;right:-10px;background:#ff4757;color:#fff;font-size:0.8rem;font-weight:700;padding:2px 6px;border-radius:10px;min-width:18px;text-align:center;box-shadow:0 2px 8px #ff475799;"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>
</header>
<!-- Nút lên đầu trang -->
<button id="backToTopBtn" title="Lên đầu trang" style="display:none;position:fixed;bottom:32px;right:32px;z-index:9999;background:#2196f3;color:#fff;border:none;border-radius:50%;width:48px;height:48px;box-shadow:0 4px 16px #2196f355;cursor:pointer;font-size:1.6rem;transition:background 0.2s;">
  <i class="fas fa-arrow-up"></i>
</button>
<script>
// Hiện/ẩn nút khi cuộn
window.addEventListener('scroll', function() {
  document.getElementById('backToTopBtn').style.display = (window.scrollY > 200) ? 'block' : 'none';
});
// Cuộn lên đầu trang khi nhấn
if(document.getElementById('backToTopBtn')) {
    document.getElementById('backToTopBtn').onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
}
</script>
<style>
.header { border-bottom: 1px solid #e0e0e0; }
.nav-menu li, .nav-menu li a { white-space: nowrap; }
.nav-menu li a {
  color: #222;
  font-weight: 600;
  font-size: 1.05rem;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 8px;
  transition: background 0.2s, color 0.2s;
}
.nav-menu li a:hover, .nav-menu li a:focus, .nav-menu li a.active {
  background: #e3f2fd;
  color: #2196f3 !important;
}
.nav-menu .dropdown:hover .dropdown-menu, .nav-menu .dropdown:focus-within .dropdown-menu {
  display: block !important;
}
.dropdown-menu {
  display: none;
  position: absolute;
  left: 0;
  top: 100%;
  background: #fff;
  min-width: 180px;
  border-radius: 0 0 12px 12px;
  box-shadow: 0 8px 24px rgba(33,150,243,0.13);
  z-index: 100;
  padding: 0.5rem 0;
  border: 1px solid #e0e0e0;
}
.dropdown-menu li a {
  color: #222;
  font-weight: 500;
  padding: 10px 24px;
  border-radius: 0;
  background: none;
  transition: background 0.2s, color 0.2s;
}
.dropdown-menu li a:hover, .dropdown-menu li a:focus {
  background: #e3f2fd;
  color: #2196f3 !important;
}
.user-dropdown-content a:hover { background: #e3f2fd; color: #2196f3 !important; }
.user-dropdown-content { transition: box-shadow 0.2s; }
.user-dropdown:hover .user-dropdown-content, .user-dropdown:focus-within .user-dropdown-content { display: block !important; box-shadow: 0 8px 32px rgba(33,150,243,0.18); }
.user-icon:hover { background: #e3f2fd; }
.cart-icon:hover { background: #e3f2fd; border-radius: 50%; }
.notification-dropdown:hover .notification-dropdown-content, .notification-dropdown:focus-within .notification-dropdown-content { display: block !important; box-shadow: 0 8px 32px rgba(33,150,243,0.18); }
.notification-icon:hover { background: #e3f2fd; }
.notification-item:hover { background: #e3f2fd; }
@media (max-width: 900px) {
    .nav-container { flex-direction: column; height: auto; padding: 0 8px; }
    .nav-menu { flex-direction: column; gap: 0; }
    .nav-right { margin-top: 10px; }
}

/* Notification styles */
.notification-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f1f1f1;
    cursor: pointer;
    transition: background 0.2s;
}

.notification-item:hover {
    background: #e3f2fd;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-title {
    font-weight: 600;
    color: #222;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.notification-message {
    color: #666;
    font-size: 0.8rem;
    margin-bottom: 4px;
}

.notification-time {
    font-size: 0.8rem;
    color: #999;
}

.notification-empty {
    text-align: center;
    padding: 20px;
    color: #666;
}

.notification-product {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-product img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
}

.notification-status {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #d1ecf1;
    color: #0c5460;
}

.status-shipped {
    background: #d4edda;
    color: #155724;
}

.status-delivered {
    background: #c3e6cb;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.status-default {
    background: #e2e3e5;
    color: #383d41;
}

.notification-footer button:hover {
    background: #e3f2fd;
}
</style>

<script>
// JavaScript cho thông báo
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['user']['id'])): ?>
    loadNotifications();
    loadNotificationCount();
    setInterval(function() { loadNotificationCount(); }, 30000);
    const notificationIcon = document.querySelector('.notification-icon');
    const notificationDropdown = document.querySelector('.notification-dropdown-content');
    if (notificationIcon && notificationDropdown) {
        notificationIcon.addEventListener('click', function() {
            loadNotifications();
            markNotificationsAsRead();
            // Hiện dropdown
            notificationDropdown.style.display = 'block';
        });
        // Ẩn dropdown khi click ngoài
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && !notificationIcon.contains(e.target)) {
                notificationDropdown.style.display = 'none';
            }
        });
    }
    <?php endif; ?>
});
function loadNotificationCount() {
    fetch('notifications.php?action=get_count')
        .then(res => res.json())
        .then(data => {
            const countElement = document.getElementById('notificationCount');
            if (countElement) {
                if (data.count > 0) {
                    countElement.textContent = data.count;
                    countElement.style.display = 'block';
                } else {
                    countElement.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error loading notification count:', error));
}
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    if (!notificationList) return;
    fetch('notifications.php?action=get_notifications')
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                notificationList.innerHTML = '<div class="notification-empty">Không có thông báo mới</div>';
                return;
            }
            let html = '';
            data.forEach(notification => {
                html += `<div class="notification-item" onclick="viewOrder(${notification.order_id})">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${notification.time}</div>
                </div>`;
            });
            notificationList.innerHTML = html;
        })
        .catch(error => {
            notificationList.innerHTML = '<div class="notification-empty">Lỗi tải thông báo</div>';
        });
}
function markNotificationsAsRead() {
    fetch('notifications.php?action=mark_read')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const countElement = document.getElementById('notificationCount');
                if (countElement) {
                    countElement.style.display = 'none';
                }
            }
        });
}
function viewOrder(orderId) {
    window.location.href = 'user/history.php?order_id=' + orderId;
}



function markAllAsRead() {
    fetch('notifications.php?action=mark_read')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Ẩn badge số lượng thông báo
                const countElement = document.getElementById('notificationCount');
                if (countElement) {
                    countElement.style.display = 'none';
                }
                
                // Hiển thị thông báo đã đánh dấu xem tất cả
                const notificationList = document.getElementById('notificationList');
                if (notificationList) {
                    notificationList.innerHTML = '<div class="notification-empty">Đã đánh dấu xem tất cả thông báo</div>';
                }
                
                // Tự động ẩn dropdown sau 2 giây
                setTimeout(() => {
                    const dropdown = document.querySelector('.notification-dropdown-content');
                    if (dropdown) {
                        dropdown.style.display = 'none';
                    }
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
            alert('Có lỗi xảy ra khi đánh dấu thông báo');
        });
}
</script> 