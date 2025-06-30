<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cartCount = 0;
$cartData = [];
if (isset($_SESSION['user']['id'])) {
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare('SELECT c.instrument_id as id, i.name, i.price, i.image_url, c.quantity 
                            FROM cart c 
                            JOIN instruments i ON c.instrument_id = i.id 
                            WHERE c.user_id = ?');
    $stmt->execute([$user_id]);
    $cartData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartData as $item) {
        $cartCount += $item['quantity'];
    }
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
            <li><a href="#sale" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Summer Sale</a></li>
            <li><a href="#blog" style="font-weight:600;font-size:1.05rem;color:#222;text-decoration:none;padding:10px 18px;border-radius:8px;transition:background 0.2s;">Bài viết</a></li>
        </ul>
        <!-- Icon bên phải -->
        <div class="nav-right" style="display:flex;align-items:center;gap:18px;">
            <!-- Search icon -->
            <i class="fas fa-search search-icon" onclick="document.getElementById('searchInput') && document.getElementById('searchInput').focus();" style="font-size:1.4rem;color:#111;cursor:pointer;padding:8px;border-radius:50%;transition:background 0.2s;"></i>
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
            <div class="cart-icon" onclick="showCart()" style="position:relative;cursor:pointer;">
                <i class="fas fa-shopping-cart" style="font-size:1.5rem;color:#111;"></i>
                <span class="cart-count" id="cartCount" style="position:absolute;top:-8px;right:-10px;background:#ff4757;color:#fff;font-size:0.95rem;font-weight:700;padding:2px 8px;border-radius:12px;box-shadow:0 2px 8px #ff475799;"><?php echo $cartCount; ?></span>
            </div>
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
<script>window.cartData = <?php echo json_encode($cartData); ?>;</script>
<style>
.header { border-bottom: 1px solid #e0e0e0; }
.nav-menu li, .nav-menu li a { white-space: nowrap; }
.nav-menu li a:hover, .nav-menu li a:focus { background: #e3f2fd; color: #2196f3 !important; }
.nav-menu .dropdown:hover .dropdown-menu, .nav-menu .dropdown:focus-within .dropdown-menu { display: block !important; }
.user-dropdown-content a:hover { background: #e3f2fd; color: #2196f3 !important; }
.user-dropdown-content { transition: box-shadow 0.2s; }
.user-dropdown:hover .user-dropdown-content, .user-dropdown:focus-within .user-dropdown-content { display: block !important; box-shadow: 0 8px 32px rgba(33,150,243,0.18); }
.user-icon:hover { background: #e3f2fd; }
.cart-icon:hover { background: #e3f2fd; border-radius: 50%; }
@media (max-width: 900px) {
    .nav-container { flex-direction: column; height: auto; padding: 0 8px; }
    .nav-menu { flex-direction: column; gap: 0; }
    .nav-right { margin-top: 10px; }
}
</style> 