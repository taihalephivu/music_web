<?php session_start(); ?>
<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9; // 9 sản phẩm mỗi trang
$offset = ($page - 1) * $per_page;

// Đếm tổng số sản phẩm
$count_sql = "SELECT COUNT(*) as total FROM instruments";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute();
$total_products = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_products / $per_page);

// Lấy sản phẩm theo trang
$sql = "SELECT i.*, b.name as brand_name, c.name as category_name FROM instruments i
        LEFT JOIN brands b ON i.brand_id = b.id
        LEFT JOIN categories c ON i.category_id = c.id
        ORDER BY i.id DESC LIMIT $per_page OFFSET $offset";
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
    <!-- Music Banner chạy nốt nhạc -->
    <div class="music-banner">
        <div class="music-notes-track">
            <span class="music-note"><i class="fas fa-music"></i></span>
            <span class="music-note"><i class="fas fa-guitar"></i></span>
            <span class="music-note"><i class="fas fa-drum"></i></span>
            <span class="music-note"><i class="fas fa-headphones"></i></span>
            <span class="music-note"><i class="fas fa-microphone"></i></span>
            <span class="music-note"><i class="fas fa-music"></i></span>
            <span class="music-note"><i class="fas fa-guitar"></i></span>
            <span class="music-note"><i class="fas fa-drum"></i></span>
            <span class="music-note"><i class="fas fa-headphones"></i></span>
            <span class="music-note"><i class="fas fa-microphone"></i></span>
            <!-- Lặp lại lần 2 để chạy liền mạch -->
            <span class="music-note"><i class="fas fa-music"></i></span>
            <span class="music-note"><i class="fas fa-guitar"></i></span>
            <span class="music-note"><i class="fas fa-drum"></i></span>
            <span class="music-note"><i class="fas fa-headphones"></i></span>
            <span class="music-note"><i class="fas fa-microphone"></i></span>
            <span class="music-note"><i class="fas fa-music"></i></span>
            <span class="music-note"><i class="fas fa-guitar"></i></span>
            <span class="music-note"><i class="fas fa-drum"></i></span>
            <span class="music-note"><i class="fas fa-headphones"></i></span>
            <span class="music-note"><i class="fas fa-microphone"></i></span>
        </div>
                    </div>
        <!-- Hero Section -->
        <section class="hero" id="home">
        <div class="hero-bg-slides"></div>
        <div class="container hero-content">
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
            <h2><i class="fas fa-guitar"></i> Tất cả dụng cụ âm nhạc</h2>
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
        
        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <div class="pagination-info">
                Hiển thị <?php echo ($offset + 1); ?> - <?php echo min($offset + $per_page, $total_products); ?> 
                trong tổng số <?php echo $total_products; ?> sản phẩm
            </div>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1" class="page-link" title="Trang đầu">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?page=<?php echo $page - 1; ?>" class="page-link" title="Trang trước">
                        <i class="fas fa-angle-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="page-link" title="Trang sau">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="?page=<?php echo $total_pages; ?>" class="page-link" title="Trang cuối">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
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

    </main>

    <!-- Customer Reviews Section -->
    <section class="customer-reviews-section">
        <div class="reviews-header">
            <h2><i class="fas fa-star"></i> Đánh giá từ khách hàng</h2>
            <p>Những gì khách hàng nói về chúng tôi</p>
        </div>
        <div class="reviews-banner">
            <div class="reviews-track">
                <?php
                // Lấy đánh giá từ database
                $stmt = $conn->query('SELECT sr.*, u.username FROM service_reviews sr LEFT JOIN users u ON sr.user_id = u.id ORDER BY sr.created_at DESC LIMIT 8');
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Tạo mảng đánh giá để hiển thị
                $displayReviews = [];
                foreach ($reviews as $review) {
                    $username = $review['username'] ? htmlspecialchars($review['username']) : 'Khách hàng';
                    $overall_rating = round(($review['product_quality'] + $review['delivery_service'] + $review['customer_service']) / 3, 1);
                    $comment = htmlspecialchars($review['comment']);
                    
                    // Cắt comment nếu quá dài
                    if (strlen($comment) > 120) {
                        $comment = substr($comment, 0, 120) . '...';
                    }
                    
                    $displayReviews[] = [
                        'username' => $username,
                        'rating' => $overall_rating,
                        'comment' => $comment
                    ];
                }
                
                // Nếu không có đánh giá, tạo dữ liệu mẫu
                if (empty($displayReviews)) {
                    $displayReviews = [
                        ['username' => 'Nguyễn Văn A', 'rating' => 5.0, 'comment' => 'Sản phẩm chất lượng rất tốt, giao hàng nhanh chóng và nhân viên phục vụ rất nhiệt tình!'],
                        ['username' => 'Trần Thị B', 'rating' => 4.8, 'comment' => 'Dịch vụ giao hàng tuyệt vời, sản phẩm đúng như mô tả, rất hài lòng với trải nghiệm mua sắm'],
                        ['username' => 'Lê Văn C', 'rating' => 5.0, 'comment' => 'Rất hài lòng với chất lượng sản phẩm và dịch vụ khách hàng. Sẽ quay lại mua sắm!'],
                        ['username' => 'Phạm Thị D', 'rating' => 4.7, 'comment' => 'Sản phẩm đúng như mô tả, hỗ trợ khách hàng tốt, giao hàng đúng hẹn'],
                        ['username' => 'Hoàng Văn E', 'rating' => 4.9, 'comment' => 'Guitar rất đẹp, âm thanh hay, giao hàng đúng hẹn và được bảo hành tốt'],
                        ['username' => 'Vũ Thị F', 'rating' => 5.0, 'comment' => 'Cửa hàng uy tín, sản phẩm chất lượng cao, giá cả hợp lý và dịch vụ tốt'],
                        ['username' => 'Đặng Văn G', 'rating' => 4.6, 'comment' => 'Mua piano cho con học, âm thanh rất hay và giá cả phù hợp'],
                        ['username' => 'Bùi Thị H', 'rating' => 4.8, 'comment' => 'Nhân viên tư vấn rất chuyên nghiệp, giúp chọn được sản phẩm phù hợp']
                    ];
                }
                
                // Lặp lại 3 lần để tạo hiệu ứng liền mạch
                for ($i = 0; $i < 3; $i++) {
                    foreach ($displayReviews as $review): ?>
                        <div class="review-item">
                            <div class="review-stars">
                                <?php
                                $rating = $review['rating'];
                                $full_stars = floor($rating);
                                $has_half = ($rating - $full_stars) >= 0.5;
                                $empty_stars = 5 - $full_stars - ($has_half ? 1 : 0);
                                
                                echo str_repeat('<i class="fas fa-star"></i>', $full_stars);
                                if ($has_half) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                }
                                echo str_repeat('<i class="far fa-star"></i>', $empty_stars);
                                ?>
                            </div>
                            <div class="review-comment">"<?php echo $review['comment']; ?>"</div>
                            <div class="review-author">- <?php echo $review['username']; ?></div>
                        </div>
                    <?php endforeach;
                } ?>
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

    <script>
        window.isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/user.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/search.js"></script>
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
    .header, .music-banner, .hero {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .music-banner {
        width: 100%;
        overflow: hidden;
        background: linear-gradient(90deg, #e3f2fd 0%, #fff 100%);
        border-bottom: 1px solid #e0e0e0;
        height: 48px;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 20;
        margin-bottom: 0 !important;
    }
    .music-notes-track {
        display: flex;
        align-items: center;
        gap: 32px;
        white-space: nowrap;
        width: max-content;
        animation: music-marquee 16s linear infinite;
        font-size: 1.7rem;
        color: #2196f3;
        font-weight: 700;
    }
    .music-note {
        display: inline-block;
        margin-right: 16px;
        filter: drop-shadow(0 2px 6px #2196f355);
        opacity: 0.85;
        transition: transform 0.2s;
    }
    .music-note:hover {
        transform: scale(1.2) rotate(-10deg);
        color: #ff5252;
    }
    @keyframes music-marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .hero {
        position: relative;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0 !important;
    }
    .hero-bg-slides {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%;
        z-index: 1;
    }
    .hero-bg-slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 1s;
        z-index: 1;
    }
    .hero-bg-slide.active {
        opacity: 1;
        z-index: 2;
    }
    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        color: #fff;
        text-shadow: 0 2px 16px rgba(0,0,0,0.25);
    }
    .hero .btn {
        background: #2196f3;
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        padding: 12px 32px;
        font-size: 1.1rem;
        margin-top: 18px;
        box-shadow: 0 2px 8px rgba(33,150,243,0.13);
    }

    /* Customer Reviews Section */
    .customer-reviews-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 50px 0;
        margin: 60px 0;
        overflow: hidden;
        position: relative;
    }
    .customer-reviews-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.08"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.08"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.08"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.08"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }
    .reviews-header {
        text-align: center;
        margin-bottom: 40px;
        color: #fff;
        position: relative;
        z-index: 10;
    }
    .reviews-header h2 {
        font-size: 2.5rem;
        margin-bottom: 12px;
        color: #fff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        font-weight: 700;
    }
    .reviews-header h2 i {
        color: #ffd600;
        margin-right: 12px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    .reviews-header p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    .reviews-banner {
        width: 100%;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(15px);
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        height: 140px;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 20;
    }
    .reviews-track {
        display: flex;
        align-items: center;
        gap: 35px;
        white-space: nowrap;
        width: max-content;
        animation: reviews-marquee 45s linear infinite;
        font-size: 1rem;
        color: #fff;
        font-weight: 500;
        will-change: transform;
    }
    .review-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 320px;
        max-width: 380px;
        padding: 22px 18px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-right: 25px;
        position: relative;
        overflow: hidden;
    }
    .review-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s;
    }
    .review-item:hover::before {
        left: 100%;
    }
    .review-item:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        background: rgba(255, 255, 255, 0.16);
        border-color: rgba(255, 255, 255, 0.25);
    }
    .review-stars {
        color: #ffd600;
        font-size: 1.3rem;
        margin-bottom: 12px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: 2px;
    }
    .review-comment {
        font-size: 0.95rem;
        line-height: 1.5;
        text-align: center;
        margin-bottom: 12px;
        font-style: italic;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        max-width: 300px;
        word-wrap: break-word;
        white-space: normal;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 400;
    }
    .review-author {
        font-size: 0.9rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        margin-top: auto;
    }
    @keyframes reviews-marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    /* Responsive cho reviews */
    @media (max-width: 768px) {
        .customer-reviews-section {
            padding: 40px 0;
            margin: 40px 0;
        }
        .reviews-header h2 {
            font-size: 2rem;
        }
        .reviews-header p {
            font-size: 1rem;
        }
        .reviews-banner {
            height: 120px;
        }
        .review-item {
            min-width: 280px;
            max-width: 320px;
            padding: 18px 16px;
        }
        .review-comment {
            font-size: 0.9rem;
            max-width: 260px;
        }
        .reviews-track {
            gap: 30px;
            animation-duration: 35s;
        }
    }
    @media (max-width: 480px) {
        .customer-reviews-section {
            padding: 30px 0;
            margin: 30px 0;
        }
        .reviews-header h2 {
            font-size: 1.6rem;
        }
        .reviews-header p {
            font-size: 0.9rem;
        }
        .reviews-banner {
            height: 100px;
        }
        .review-item {
            min-width: 240px;
            max-width: 280px;
            padding: 15px 14px;
        }
        .review-comment {
            font-size: 0.85rem;
            max-width: 220px;
        }
        .reviews-track {
            gap: 25px;
            animation-duration: 30s;
        }
    }
    
    /* Phân trang */
    .pagination-container {
        margin-top: 40px;
        text-align: center;
    }
    .pagination-info {
        margin-bottom: 20px;
        color: #666;
        font-size: 0.9rem;
    }
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 8px 12px;
        background: #fff;
        color: #333;
        text-decoration: none;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .page-link:hover {
        background: #2196f3;
        color: #fff;
        border-color: #2196f3;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(33,150,243,0.2);
    }
    .page-link.active {
        background: #2196f3;
        color: #fff;
        border-color: #2196f3;
        box-shadow: 0 4px 8px rgba(33,150,243,0.2);
    }
    .page-link i {
        font-size: 0.9rem;
    }
    
    /* Responsive cho phân trang */
    @media (max-width: 768px) {
        .pagination {
            gap: 6px;
        }
        .page-link {
            min-width: 36px;
            height: 36px;
            padding: 6px 10px;
            font-size: 0.9rem;
        }
        .pagination-info {
            font-size: 0.8rem;
        }
    }
    @media (max-width: 480px) {
        .pagination {
            gap: 4px;
        }
        .page-link {
            min-width: 32px;
            height: 32px;
            padding: 4px 8px;
            font-size: 0.8rem;
        }
    }
    </style>
    <script>
    const heroImages = [
        'assets/images/hero/hero1.jpg',
        'assets/images/hero/hero2.jpg',
        'assets/images/hero/hero3.jpg'
    ];
    const heroBg = document.querySelector('.hero-bg-slides');
    if (heroBg) {
        heroImages.forEach((src, idx) => {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'hero-bg-slide' + (idx === 0 ? ' active' : '');
            img.alt = 'Hero Slide ' + (idx+1);
            heroBg.appendChild(img);
        });
        let current = 0;
        setInterval(() => {
            const slides = heroBg.querySelectorAll('.hero-bg-slide');
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 4000);
    }
    </script>
    <?php include 'footer.php'; ?>
</body>
</html> 