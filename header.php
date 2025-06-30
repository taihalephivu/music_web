<?php
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!-- Header -->
<header class="header">
    <nav class="nav-container">
        <!-- Logo bên trái -->
        <div class="nav-left">
            <a href="index.php" class="logo">
                <img src="assets/images/logo/logo.png" alt="" style="height:36px;vertical-align:middle;margin-right:8px;display:inline-block;">
                <i class="fas fa-music"></i> <span class="logo-text">Music Store</span>
            </a>
        </div>
        <!-- Menu căn giữa -->
        <ul class="nav-menu nav-center">
            <li class="dropdown">
                <a href="#categories">Danh mục <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="category.php?cat=Guitar">Guitar</a></li>
                    <li><a href="category.php?cat=Piano">Piano</a></li>
                    <li><a href="category.php?cat=Drum">Drum</a></li>
                    <li><a href="category.php?cat=Violin">Violin</a></li>
                    <li><a href="category.php?cat=Wind Instruments">Nhạc cụ hơi</a></li>
                    <li><a href="category.php?cat=Accessories">Phụ kiện</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#brands">Thương hiệu <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="brand.php?brand=Fender">Fender</a></li>
                    <li><a href="brand.php?brand=Yamaha">Yamaha</a></li>
                    <li><a href="brand.php?brand=Roland">Roland</a></li>
                    <li><a href="brand.php?brand=Gibson">Gibson</a></li>
                    <li><a href="brand.php?brand=Pearl">Pearl</a></li>
                    <li><a href="brand.php?brand=Kawai">Kawai</a></li>
                </ul>
            </li>
            <li><a href="#sale">Summer Sale</a></li>
            <li><a href="#blog">Bài viết</a></li>
        </ul>
        <!-- Icon bên phải -->
        <div class="nav-right">
            <!-- User icon -->
            <div class="user-dropdown">
                <i class="fas fa-user user-icon" tabindex="0"></i>
                <div class="user-dropdown-content">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="user-name">👋 <?php echo htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['username']); ?></div>
                        <a href="user/profile.php"><i class="fas fa-id-card"></i> Hồ sơ</a>
                        <a href="user/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    <?php elseif (isset($_SESSION['admin'])): ?>
                        <div class="user-name">👨‍💼 <?php echo htmlspecialchars($_SESSION['admin']['full_name'] ?? $_SESSION['admin']['username']); ?></div>
                        <a href="admin/index.php"><i class="fas fa-cog"></i> Admin Panel</a>
                        <a href="admin/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    <?php else: ?>
                        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                        <a href="register.php"><i class="fas fa-user-plus"></i> Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Search icon -->
            <i class="fas fa-search search-icon" onclick="document.getElementById('searchInput').focus()"></i>
            <!-- Cart icon -->
            <div class="cart-icon" onclick="showCart()">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cartCount"><?php echo $cartCount; ?></span>
            </div>
        </div>
    </nav>
</header> 