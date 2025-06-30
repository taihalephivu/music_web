<?php session_start(); ?>
<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();
// Lấy 8 sản phẩm mới nhất
$sql = "SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i
        LEFT JOIN brands b ON i.brand_id = b.id
        LEFT JOIN categories c ON i.category_id = c.id
        ORDER BY i.id DESC LIMIT 8";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Store - Cửa hàng dụng cụ âm nhạc</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="container">
                <h1>Khám phá dụng cụ âm nhạc chất lượng</h1>
                <p>Hàng nghìn dụng cụ âm nhạc từ các thương hiệu nổi tiếng trên toàn thế giới</p>
                <a href="#instruments" class="btn">
                    <i class="fas fa-guitar"></i> Khám phá ngay
                </a>
            </div>
        </section>
        <!-- Search Section -->
        <section class="search-section">
            <div class="container">
                <input type="text" id="searchInput" class="search-input" placeholder="Tìm kiếm dụng cụ âm nhạc, thương hiệu..." oninput="filterHomeProducts()">
            </div>
        </section>
        <!-- Instruments Section -->
        <section id="instruments" class="container">
            <div class="section-header">
                <h2><i class="fas fa-guitar"></i> Dụng cụ âm nhạc nổi bật</h2>
                <p>Khám phá bộ sưu tập dụng cụ âm nhạc chất lượng cao</p>
            </div>
            <div id="musicGrid" class="instruments-grid">
                <?php if (count($products) == 0): ?>
                    <div style="color: #111; text-align: center; grid-column: 1 / -1;">Không có sản phẩm nào.</div>
                <?php else: foreach($products as $p): ?>
                    <div class="instrument-card home-product" data-name="<?php echo strtolower($p['name']); ?>" data-brand="<?php echo strtolower($p['brand_name']); ?>" data-category="<?php echo strtolower($p['category_name']); ?>">
                        <img src="<?php echo $p['image_url'] ? $p['image_url'] : 'assets/images/default.jpg'; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="instrument-image" onerror="this.src='assets/images/default.jpg'">
                        <div class="instrument-info">
                            <h3 class="instrument-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                            <p class="instrument-brand"><?php echo htmlspecialchars($p['brand_name']); ?></p>
                            <p class="instrument-category"><?php echo htmlspecialchars($p['category_name']); ?></p>
                            <p class="instrument-price"><?php echo number_format($p['price'], 0, ',', '.'); ?>₫</p>
                            <p class="instrument-stock"><i class="fas fa-box"></i> Còn lại: <?php echo $p['stock_quantity']; ?> sản phẩm</p>
                            <div class="instrument-actions">
                                <form method="post" action="add_to_cart.php" style="display:inline">
                                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                    <button type="submit" class="btn">Thêm vào giỏ</button>
                                </form>
                                <button class="btn btn-details">Chi tiết</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>
        <!-- Categories Section -->
        <section id="categories" class="categories-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-th-large"></i> Danh mục dụng cụ</h2>
                    <p>Chọn danh mục dụng cụ âm nhạc phù hợp với bạn</p>
                </div>
                <div class="categories-grid">
                    <div class="category-card" onclick="filterByCategory('Guitar')">
                        <i class="fas fa-guitar category-icon"></i>
                        <h3 class="category-name">Guitar</h3>
                        <p class="category-count">Acoustic & Electric</p>
                    </div>
                    <div class="category-card" onclick="filterByCategory('Piano')">
                        <i class="fas fa-piano category-icon"></i>
                        <h3 class="category-name">Piano</h3>
                        <p class="category-count">Digital & Acoustic</p>
                    </div>
                    <div class="category-card" onclick="filterByCategory('Drum')">
                        <i class="fas fa-drum category-icon"></i>
                        <h3 class="category-name">Drum</h3>
                        <p class="category-count">Bộ trống & Phụ kiện</p>
                    </div>
                    <div class="category-card" onclick="filterByCategory('Violin')">
                        <i class="fas fa-violin category-icon"></i>
                        <h3 class="category-name">Violin</h3>
                        <p class="category-count">Đàn dây</p>
                    </div>
                    <div class="category-card" onclick="filterByCategory('Wind Instruments')">
                        <i class="fas fa-wind category-icon"></i>
                        <h3 class="category-name">Nhạc cụ hơi</h3>
                        <p class="category-count">Kèn & Sáo</p>
                    </div>
                    <div class="category-card" onclick="filterByCategory('Accessories')">
                        <i class="fas fa-tools category-icon"></i>
                        <h3 class="category-name">Phụ kiện</h3>
                        <p class="category-count">Dây đàn & Phụ kiện</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brands Section -->
        <section id="brands" class="brands-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="fas fa-crown"></i> Thương hiệu nổi tiếng</h2>
                    <p>Những thương hiệu dụng cụ âm nhạc hàng đầu thế giới</p>
                </div>
                <div class="brands-grid">
                    <div class="brand-card">
                        <img src="assets/images/brands/fender.png" alt="Fender" class="brand-logo">
                        <h3 class="brand-name">Fender</h3>
                    </div>
                    <div class="brand-card">
                        <img src="assets/images/brands/yamaha.jpg" alt="Yamaha" class="brand-logo">
                        <h3 class="brand-name">Yamaha</h3>
                    </div>
                    <div class="brand-card">
                        <img src="assets/images/brands/roland.png" alt="Roland" class="brand-logo">
                        <h3 class="brand-name">Roland</h3>
                    </div>
                    <div class="brand-card">
                        <img src="assets/images/brands/gibson.png" alt="Gibson" class="brand-logo">
                        <h3 class="brand-name">Gibson</h3>
                    </div>
                    <div class="brand-card">
                        <img src="assets/images/brands/pearl.jpg" alt="Pearl" class="brand-logo">
                        <h3 class="brand-name">Pearl</h3>
                    </div>
                    <div class="brand-card">
                        <img src="assets/images/brands/kawai.png" alt="Kawai" class="brand-logo">
                        <h3 class="brand-name">Kawai</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="about-content">
                    <h2>Về Music Store</h2>
                    <p>
                        Music Store là cửa hàng dụng cụ âm nhạc trực tuyến hàng đầu, cung cấp hàng nghìn dụng cụ âm nhạc 
                        chất lượng cao từ các thương hiệu nổi tiếng trên toàn thế giới. Chúng tôi cam kết mang đến trải nghiệm 
                        mua sắm tuyệt vời nhất cho người yêu âm nhạc.
                    </p>
                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="fas fa-award feature-icon"></i>
                            <h3>Chất lượng cao</h3>
                            <p>Dụng cụ âm nhạc chính hãng 100%</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shipping-fast feature-icon"></i>
                            <h3>Giao hàng nhanh</h3>
                            <p>Giao hàng toàn quốc trong 24h</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-headset feature-icon"></i>
                            <h3>Hỗ trợ 24/7</h3>
                            <p>Tư vấn và hỗ trợ mọi lúc</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt feature-icon"></i>
                            <h3>Bảo hành chính hãng</h3>
                            <p>Bảo hành từ 1-3 năm</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Cart Modal -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-content">
            <div class="cart-header">
                <h3 class="cart-title">Giỏ hàng</h3>
                <button class="close-cart" onclick="closeCart()">&times;</button>
            </div>
            <div id="cartItems">
                <!-- Cart items will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        window.isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/user.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/search.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
    // Lọc sản phẩm trên trang chủ bằng JS (chỉ filter DOM, không fetch API)
    function filterHomeProducts() {
        var kw = document.getElementById('searchInput').value.toLowerCase().trim();
        var cards = document.querySelectorAll('.home-product');
        cards.forEach(function(card) {
            var name = card.getAttribute('data-name');
            var brand = card.getAttribute('data-brand');
            var cat = card.getAttribute('data-category');
            if (kw === '' || name.includes(kw) || brand.includes(kw) || cat.includes(kw)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>
    <style>
    /* Dropdown menu styles */
    .nav-menu .dropdown {
        position: relative;
    }
    .nav-menu .dropdown-menu {
        display: none;
        position: absolute;
        left: 0;
        top: 100%;
        background: #fff;
        min-width: 180px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        z-index: 100;
        padding: 0.5rem 0;
        border: 1px solid #e0e0e0;
    }
    .nav-menu .dropdown:hover .dropdown-menu {
        display: block;
    }
    .nav-menu .dropdown-menu li a {
        color: #111;
        padding: 10px 24px;
        display: block;
        text-decoration: none;
        transition: background 0.2s;
    }
    .nav-menu .dropdown-menu li a:hover {
        background: #f4f6fb;
        color: #2196f3;
    }
    @media (max-width: 900px) {
        .nav-menu .dropdown-menu {
            position: static;
            min-width: unset;
            box-shadow: none;
            border-radius: 0;
            background: #f4f6fb;
        }
    }
    </style>
    <?php include 'footer.php'; ?>
</body>
</html> 